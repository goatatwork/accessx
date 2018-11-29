<?php

namespace App\Console\Commands;

use App\Ont;
use Illuminate\Console\Command;
use App\GoldAccess\Ont\ZhoneFilenameConverter;

class AddOntSoftware extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goldaccess:add-ont-software';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a software image to an ONT';

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
        $headers = ['ID', 'ONT Name', 'Created'];

        $model_number = $this->choice('Which ONT would you like to add software to?', Ont::pluck('model_number')->toArray());

        $file = $this->choice('Which file would you like to add to the ' . $model_number . '?', $this->imageFiles());

        // $file = $this->ask('Where is the file?');

        $this->addOntSoftware($model_number, $file);
    }

    protected function addOntSoftware($model_number, $file)
    {
        $ont = Ont::whereModelNumber($model_number)->first();

        $converter = new ZhoneFilenameConverter($file, $ont->oem, $ont->model_number);

        $converter->calculate();

        $software = $ont->ont_software()->create(['version' => $converter->version_string_for_database, 'notes' => 'Added from command line']);

        $software->addMedia($file)
            ->preservingOriginal()
            ->usingFileName($converter->getDestinationFilename())
            ->withCustomProperties(['dhcp_string' => $converter->dhcp_config_string])
            ->toMediaCollection('default');

        $this->line('Software was added to the ' . $model_number);
    }

    /**
     * Get an array of all .img files in the base_path() of the project
     * @return array
     */
    protected function imageFiles()
    {
        return array_where(scandir(base_path()), function($value,$key) {
            return ends_with($value, '.img');
        });
    }
}
