<?php

// UNUSED

namespace App\GoldAccess\Dhcp;

use SSH;
use JJG\Ping;
use App\GoldAccess\Dhcp\DnsmasqConfigFile;

class DnsmasqServer
{
    /**
     * @var  \App\GoldAccess\DnsmasqConfigFile
     */
    public $config_file;

    /**
     * @var  string The IP address
     */
    public $ip;

    /**
     * @var  string The location of the PID file
     */
    protected $pid;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->config_file = new DnsmasqConfigFile();
        $this->ip = config('goldaccess.dhcp.ip');
        $this->pid = config('goldaccess.dhcp.pid');
    }

    /**
     * Check to see if the dnsmasq service is running
     * @return boolean [description]
     */
    public function isRunning()
    {
        return $this->pidFileExists();
    }

    /**
     * Check to see if the dnsmasq server is  up
     * @return boolean [description]
     */
    public function isUp()
    {
        $host = new Ping($this->ip);
        $host->setPort(22);
        $host->setTtl(128);
        $host->setTimeout(5);
        $latency = $host->ping();
        return ($latency !== false) ? true : false;
    }

    /**
     * Restart dnsmasq
     * @return string output
     */
    public function restart()
    {
        return SSH::into('dhcp')->run(['systemctl restart dnsmasq']);
    }

    /**
     * Check whether or not the configured PID files exists on the server
     * @return boolean
     */
    protected function pidFileExists()
    {
        return SSH::into('dhcp')->exists($this->pid);
    }
}

