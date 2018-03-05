<?php

namespace Tests\Unit;

use App\Ont;
use App\OntProfile;
use Tests\TestCase;
use App\OntSoftware;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntThingsTest extends TestCase
{
    public function test_ont_can_exist()
    {
        $ont = factory(Ont::class)->create();
        $this->assertDatabaseHas('onts', ['model_number' => $ont->model_number]);
    }

    public function test_ont_can_add_ont_software()
    {
        $ont= factory(Ont::class)->create();
        $software = factory(OntSoftware::class)->make(['ont_id' => null]);

        $ont->ont_software()->save($software);

        $this->assertDatabaseHas('ont_software', ['version' => $software->version]);
    }

    public function test_ont_software_can_add_ont_profiles()
    {
        $software = factory(OntSoftware::class)->create();
        $profile = factory(OntProfile::class)->make(['ont_software_id' => null]);

        $software->ont_profiles()->save($profile);

        $this->assertDatabasehas('ont_profiles', ['name' => $profile->name]);
    }
}
