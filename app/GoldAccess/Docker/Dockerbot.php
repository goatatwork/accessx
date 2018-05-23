<?php

namespace App\GoldAccess\Docker;

use Carbon\Carbon;
use Docker\Docker;

class Dockerbot
{
    /**
     * @var \Docker\Docker $docker
     */
    public $docker;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->docker = Docker::create();
    }

    /**
     * Proxy method calls to the docker object if they're not present here.
     * @param  string $method     The function that was called
     * @param  array  $parameters The parameters, if any, that were included
     * with $method
     * @return mixed
     */
    public function __call($method, array $parameters = array())
    {

        return (in_array($method, $this->getDockerMethods())) ? $this->docker->$method(...$parameters) : $this->$method(...$parameters);

    }

    /**
     * @return array Array of methods that can be used on \Docker\Docker
     */
    public function apiMethods()
    {

        return $this->getDockerMethods();

    }

    /**
     * Check to see if a container by the name of $name exists with or without
     * a leading "/"
     * @param  string $name The name of the container you are looking for
     * @return mixed either the container ID or false
     */
    public function getContainerId(string $name)
    {
        $name = (starts_with($name, '/')) ? $name : '/' . $name;

        return $this->containerExists($name);
    }

    /**
     * Inspect the container to determine whether or not it is running
     * @param  string $name The name of the container you are looking for
     * @return boolean
     */
    public function containerIsRunning(string $name)
    {
        $name = (starts_with($name, '/')) ? $name : '/' . $name;

        if ( ! $this->containerExists($name) ) {
            return false;
        }

        $container_id = $this->containerExists($name);

        $container = $this->docker->containerInspect($container_id); // Docker\API\Model\ContainersIdJsonGetResponse200

        $state = $container->getState(); // Docker\API\Model\ContainersIdJsonGetResponse200State
        $status = $state->getStatus(); // "running"

        return ($status == 'running') ? true : false;
    }


    /**
     * Get the started at timestamp and return it ->diffForHumans()'d'
     * @param  string $name The name of the container you are looking for
     * @return string
     */
    public function containerUptime(string $name)
    {
        $name = (starts_with($name, '/')) ? $name : '/' . $name;

        if ( ! $this->containerExists($name) ) {
            return false;
        }

        $container_id = $this->containerExists($name);

        $container = $this->docker->containerInspect($container_id); // Docker\API\Model\ContainersIdJsonGetResponse200

        $state = $container->getState();

        $timestamp = explode('.', $state->getStartedAt());

        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', str_replace_first('T', ' ', $timestamp[0]));

        return $carbon->diffForHumans();
    }

    /**
     * Restart a container by name
     * @param  string $name The name of the container you are looking for
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function containerRestart(string $name)
    {
        $name = (starts_with($name, '/')) ? $name : '/' . $name;

        if ( ! $this->containerExists($name) ) {
            return false;
        }

        $container_id = $this->containerExists($name);

        return $this->docker->containerRestart($container_id);
    }

    /**
     * Check to see if a container by the name of $name exists with or without
     * a leading "/"
     * @param  string $name The name of the container you are looking for
     * @return mixed either the container ID or false
     */
    protected function containerExists(string $name)
    {
        $name = (starts_with($name, '/')) ? $name : '/' . $name;

        $dnsmasq_server_container_id = null;

        foreach ($this->docker->containerList() as $container)
        {
            if (in_array($name, $container->getNames())) {
                $dnsmasq_server_container_id = $container->getId();
            }
        }
            return (is_null($dnsmasq_server_container_id)) ? false : $dnsmasq_server_container_id;
    }

    /**
     * Get an array of methods from \Docker\Docker and strip __construct out
     *
     * @return array
     */
    protected function getDockerMethods()
    {
        $methods = collect(get_class_methods($this->docker));
        $methods = $methods->filter(function($value,$key) {
            return $value != '__construct';
        });

        return array_values($methods->toArray());
    }
}
