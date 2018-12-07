<?php

namespace App\GoldAccess\Docker;

use jarkt\docker\Response;
use jarkt\docker\ApiClient;
use jarkt\docker\responseHandlers\Json;

class Dockerbot
{
    /**
     * Inspect the container to determine whether or not it is running
     * @param  string $name The name of the container you are looking for
     * @return boolean
     */
    public function containerIsRunning(string $name)
    {
        $container = $this->getContainerInfoFromDocker($name);

        $state = (count($container)) ? $container[0]['State'] : null;

        return ($state == 'running') ? true : false;
    }

    /**
     * Restart a container by name
     * @param  string $name The name of the container you are looking for
     * @return boolean
     */
    public function containerRestart(string $name)
    {
        return $this->restartContainer($name);
    }

    /**
     * Get the started at timestamp and return it ->diffForHumans()'d'
     * @param  string $name The name of the container you are looking for
     * @return mixed string|null
     */
    public function containerUptime(string $name)
    {
        $container = $this->getContainerInfoFromDocker($name);

        return (count($container)) ? $container[0]['Status'] : null;
    }

    /**
     * Get basic data for a container by name
     * @param  string $name
     * @return mixed array|integer array with container data or the failure status code
     */
    protected function getContainerInfoFromDocker(string $name)
    {
        $docker = new ApiClient('socat','2375');
        $response = $docker->get('/containers/json?filters={"name":["'.$name.'"]}');

        if ($response->getStatus() === 200) {
            $responseHandler = new Json($response);
            return $responseHandler->getData();
        }

        return $response->getStatus();
    }

    /**
     * Check to see if a container by the name of $name exists with or without
     * a leading "/"
     * @param  string $name The name of the container you are looking for
     * @return mixed string|null
     */
    public function getContainerId(string $name)
    {
        $container = $this->getContainerInfoFromDocker($name);

        return (count($container)) ? $container[0]['Id'] : null;
    }

    /**
     * Get the container's basic info
     * @param  string $name The name of the container you are looking for
     * @return mixed array|null
     */
    public function getContainerInfo(string $name)
    {
        $container = $this->getContainerInfoFromDocker($name);

        return (count($container)) ? $container[0] : null;
    }

    /**
     * @param  string $name
     * @return mixed boolean
     */
    public function restartContainer(string $name)
    {
        $id = $this->getContainerId($name);

        $docker = new ApiClient('socat', '2375');
        $response = $docker->post('/containers/'.$id.'/restart', []);

        if ($response->getStatus() === 204 ) {
            return true;
        }

        return false;
    }
}
