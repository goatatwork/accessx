<?php

namespace Tests\Feature;

use App\Package;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group packages
     * @test
     * @return void
     */
    public function test_packages_can_exist()
    {
        $packages = factory(Package::class, 5)->create();

        $this->assertCount(5, Package::all());
    }

    /**
     * @group packages
     * @test
     * @return void
     */
    public function test_provisioning_records_have_packages()
    {
        $package = factory(Package::class)->create();
        $pr = factory(ProvisioningRecord::class)->create();

        $pr->packages()->save($package);

        $this->assertCount(1, $pr->packages);
    }
}
