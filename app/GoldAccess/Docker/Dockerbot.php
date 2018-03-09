<?php

namespace App\GoldAccess\Docker;

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
     * Check to see if there is a container running by the name of $name
     * @param  string $name The name of the container you are looking for
     * @return boolean
     */
    public function containerIsRunning(string $name)
    {
        $names = [];
        foreach ($this->docker->containerList() as $container)
        {
            $names = array_merge($names, $container->getNames());
        }

        $names = collect($names);

        return ($names->contains($name)) ? true : (($names->contains("/" . $name)) ? true : false);
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
