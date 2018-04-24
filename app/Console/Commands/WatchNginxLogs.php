<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WatchNginxLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goldaccess:watch-nginx-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'I watch the Nginx logs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $container_stream = app('dockerbot')->containerAttach(config('goldaccess.dockerbot.services.nginx.container_name'), [
            'stream' => true,
            'stdin' => true,
            'stdout' => true,
            'stderr' => true
        ]);

        $container_stream->onStdout(function($stdout) {
            foreach (explode("\n", $stdout) as $line)
            {
                app('logbot')->log($line, 'info');
                $this->line($line);
            }
        });

        $container_stream->onStderr(function($stderr) {
            foreach (explode("\n", $stderr) as $line)
            {
                app('logbot')->log($line, 'err');
                $this->line($line);
            }
        });

        $this->info('Listening...');

        $container_stream->wait();
    }
}
