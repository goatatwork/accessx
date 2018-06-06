<?php

namespace App\GoldAccess\Ont;

use Bestnetwork\Telnet\TelnetClient;
use Bestnetwork\Telnet\TelnetException;

class ZhoneOnt extends TelnetClient
{
    /**
     * @var string
     */
    public $default_privilege_level = 'user';

    /**
     * @var string
     */
    public $default_uplink = 'eth0';

    /**
     * @var array
     */
    public $privilege_levels = ['user', 'privileged', 'config', 'development'];

    /**
     * Constructor. Initialises host, port and timeout parameters
     * defaults to localhost port 23 (standard telnet port)
     *
     * @param string $host    Host name or IP address
     * @param int    $port    TCP port number
     * @param int    $timeout Connection timeout in seconds
     * @param string $prompt
     * @param string $err_prompt
     * @throws TelnetException
     */
    public function __construct( $host = '127.0.0.1', $port = 23, $timeout = 10, $prompt = '>', $err_prompt = 'ERROR' ){
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->prompt = $prompt;
        $this->err_prompt = $err_prompt;

        // set some telnet special characters
        $this->NULL = chr(0);
        $this->CR = chr(13);
        $this->DC1 = chr(17);
        $this->WILL = chr(251);
        $this->WONT = chr(252);
        $this->DO = chr(253);
        $this->DONT = chr(254);
        $this->IAC = chr(255);

        $this->connect();
    }

    /**
     * Attempts connection to remote host. Returns TRUE if successful.
     *
     * @return bool
     * @throws TelnetException
     */
    public function connect(){
        // check if we need to convert host to IP
        if( !preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host) ){
            $ip = gethostbyname($this->host);

            if( $this->host == $ip ){
                throw new TelnetException('Cannot resolve ' . $this->host);
            }else{
                $this->host = $ip;
            }
        }

        // attempt connection
        $this->socket = @fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);

        if( !$this->socket ){
            // throw new TelnetException('Cannot connect to ' . $this->host . ' on port ' . $this->port);
            return false;
        }
    }

    /**
     * @param string Like 'eth2' or 'wl0' or 'wl0_3'
     */
    public function disableInterface($interface)
    {
        $this->refreshEnvironment();
        $this->setPrompt('#');
        $this->execute('en');
        $this->execute('config t');
        $this->execute('interface ' . $interface);
        $disabled = $this->execute('ethernet disable');
        $this->execute('exit');
        $output = $this->cleanUpDisableInterfaceOutput($disabled);
        return $output;
    }

    /**
     * @param string Like 'eth2' or 'wl0' or 'wl0_3'
     */
    public function enableInterface($interface)
    {
        $this->refreshEnvironment();
        $this->setPrompt('#');
        $this->execute('en');
        $this->execute('config t');
        $this->execute('interface ' . $interface);
        $enabled = $this->execute('ethernet enable');
        $this->execute('exit');
        $output = $this->cleanUpEnableInterfaceOutput($enabled);
        return $output;
    }

    /**
     * Perform a factory reset
     */
    public function factoryReset()
    {
        $this->refreshEnvironment();
        $this->setPrompt('#');
        $this->execute('en');
        $this->execute('config t');
        $output = $this->execute('system restore factory-defaults');

        \Log::info('factoryReset output was a ' . typeof($output));

        // $this->execute('interface ' . $interface);
        // $enabled = $this->execute('ethernet enable');
        // $this->execute('exit');
        // $output = $this->cleanUpEnableInterfaceOutput($enabled);
        // Log::info('Factory resetting ' . $output);
        return $output;
    }

    public function interfaces()
    {
        $this->execute('en', '#');
        $output = preg_split('/\r\r\n/',$this->execute('show interface statistics all', '#'));
        $interfaces_array = [];
        foreach ($output as $line)
        {
            $line_parts = preg_split('/\s++/', $line);
            array_push($interfaces_array, $line_parts[0]);
        }
        $the_dashes = array_search('------', $interfaces_array);
        $splice_index = (count($interfaces_array) - $the_dashes) - 1;
        $just_the_interfaces = array_splice($interfaces_array, -$splice_index);
        // return array_where($just_the_interfaces, function($value,$index) {
        //     return $value != 'eth0';
        // });
        return $just_the_interfaces;
    }

    /**
     * Attempts login to remote host.
     * This method is a wrapper for lower level private methods and should be
     * modified to reflect telnet implementation details like login/password
     * and line prompts. Defaults to standard unix non-root prompts
     *
     * @param string $username Username
     * @param string $password Password
     * @throws TelnetException
     */
    public function login( $username, $password ){
        try{
            $this->read('Login:');
            $this->write((string) $username);
            $this->read('Password:');
            $this->write((string) $password);
            $this->read('>');
            return true;
        } catch( TelnetException $e ){
            // throw new TelnetException('Login failed.', 0, $e);
            return false;
        }
    }

    /**
     * Perform a reboot
     * @return  "" currently
     */
    public function reboot()
    {
        $this->refreshEnvironment();
        $this->setPrompt('#');
        $this->execute('en');
        $this->execute('config t');
        $reboot = $this->execute('system reboot');

        // $this->execute('interface ' . $interface);
        // $enabled = $this->execute('ethernet enable');
        // $this->execute('exit');
        $output = $this->cleanUpRebootOutput($reboot);
        // Log::info('Factory resetting ' . $output);
        return $output;
    }

    /**
     * @return string
     */
    protected function cleanUpDisableInterfaceOutput($output)
    {
        $output_array = preg_split('/\r\r\n/', $output);

        return $output_array[1];
    }

    /**
     * @return string
     */
    protected function cleanUpEnableInterfaceOutput($output)
    {
        $output_array = preg_split('/\r\r\n/', $output);

        return $output_array[1];
    }

    /**
     * @return string
     */
    protected function cleanUpRebootOutput($output)
    {
        \Log::info('reboot output was a ' . typeof($output));

        $output_array = preg_split('/\r\r\n/', $output);

        return $output_array[1];
    }

    /**
     * @return boolean
     */
    public function getSocket()
    {
        if (!$this->socket)
        {
            return false;
        }
        return true;
    }

    /**
     * @return string '>' or '#' for Zhone
     */
    protected function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * Get us back to the default mode
     *
     * @return void
     */
    protected function refreshEnvironment()
    {
        if ($this->getPrompt() == '#')
        {
            $this->setPrompt('>');
            $this->execute('top');
        }
    }
}
