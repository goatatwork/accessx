<?php

namespace Tests\Feature;

use App\Ont;
use Tests\TestCase;
use App\OntSoftware;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\GoldAccess\Ont\ZhoneConfigFilenameGenerator;

class ZhoneConfigFilenameGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function test_the_generator_requires_the_ontsoftware_to_work_with()
    {
        $this->expectException('TypeError');

        $config_filename_generator = new ZhoneConfigFilenameGenerator();
    }

    /**
     *
     * @return void
     */
    public function test_the_generator_can_generate_config_filename_that_the_ont_will_ask_for_based_on_the_ontsoftware()
    {
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id, 'version' => 'S03.01.266']);

        $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);

        $this->assertEquals('S0301266_0GF_generic.conf', $config_filename_generator->generate());
    }

    /**
     *
     * @return void
     */
    public function test_the_generator_can_generate_config_filename_for_27xx_models_that_the_ont_will_ask_for_based_on_the_ontsoftware()
    {
        $ont = factory(Ont::class)->create(['model_number' => '2728A1']);
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id, 'version' => 'S04.01.086']);

        $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);

        $this->assertEquals('S0401086_2728A1_generic.conf', $config_filename_generator->generate());
    }
}
