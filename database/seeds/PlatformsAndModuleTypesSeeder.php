<?php

use App\Platform;
use Illuminate\Database\Seeder;

class PlatformsAndModuleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Platform::count())
        {
            $this->makeIcx();
            $this->makeSx800();
            $this->makeSx1600();
            echo 'Platforms seeded';
            return 0;
        }
        echo 'The platforms table is not empty. Skipping seeding Platforms.';
        return 0;
    }

    protected function makeIcx()
    {
        $icx = Platform::create([
            'name' => 'ICX Stack',
            'number_of_slots' => 12,
            'notes' => 'The Brocade ICX platform.'
        ]);
        $icx->module_types()->create([
            'name' => 'ICX 6610',
            'number_of_ports' => 48,
            'notes' => 'The ICX 6610'
        ]);
        $icx->module_types()->create([
            'name' => 'ICX 7450',
            'number_of_ports' => 48,
            'notes' => 'The ICX 7450'
        ]);
        $icx->module_types()->create([
            'name' => 'ICX 7250',
            'number_of_ports' => 48,
            'notes' => 'The ICX 7250'
        ]);
    }

    protected function makeSx800()
    {
        $sx800 = Platform::create([
            'name' => 'SX800',
            'number_of_slots' => 8,
            'notes' => 'The Brocade SX800 platform.'
        ]);
        $sx800->module_types()->create([
            'name' => '24 Port Fiber Module',
            'number_of_ports' => 24,
            'notes' => ''
        ]);
        $sx800->module_types()->create([
            'name' => '48 Port Fiber Module',
            'number_of_ports' => 48,
            'notes' => ''
        ]);
    }

    protected function makeSx1600()
    {
        $sx800 = Platform::create([
            'name' => 'SX1600',
            'number_of_slots' => 16,
            'notes' => 'The Brocade SX1600 platform.'
        ]);
        $sx800->module_types()->create([
            'name' => '24 Port Fiber Module',
            'number_of_ports' => 24,
            'notes' => ''
        ]);
        $sx800->module_types()->create([
            'name' => '48 Port Fiber Module',
            'number_of_ports' => 48,
            'notes' => ''
        ]);
    }
}
