<?php

namespace Tests\Feature;

use App\Ont;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddOntSoftwareCommandTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        config()->set('medialibrary.disk_name', 'media_test');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_test_works()
    {
        $onts = factory(Ont::class, 5)->create();

        $this->artisan('goldaccess:add-ont-software')
            ->expectsQuestion('Which ONT would you like to add software to?', $onts->first()->model_number)
            ->expectsQuestion('Which file would you like to add to the ' . $onts->first()->model_number . '?', 'ZNID-24xx-301243-SIP.img')
            ->expectsOutput('Software was added to the ' . $onts->first()->model_number)
            ->assertExitCode(0);

        $this->assertFileExists($onts->first()->ont_software()->first()->file->getPath());
    }
}
