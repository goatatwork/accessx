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
    public function setup()
    {
        parent::setUp();

        print "\n";
    }

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
    public function test_given_any_filename_zhoneconfigfilegenerator_will_generate_the_config_filename_for_a_nonoem_ont()
    {
        $spec = 'S0301266_0GN_generic.conf';
        $filename = 'anything.conf';

        $ont = factory(Ont::class)->states(['nonoem'])->create();
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id, 'version' => 'S03.01.266']);

        $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);

        $this->assertEquals($spec, $config_filename_generator->generate());

        print $this->makeOutput($filename,$spec);
    }

    /**
     *
     * @return void
     */
    public function test_given_any_filename_zhoneconfigfilegenerator_will_generate_the_config_filename_for_an_oem_ont()
    {
        $spec = 'S0301266_0GF_generic.conf';
        $filename = 'anything.conf';

        $ont = factory(Ont::class)->states(['oem'])->create();
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id, 'version' => 'S03.01.266']);

        $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);

        $this->assertEquals($spec, $config_filename_generator->generate());

        print $this->makeOutput($filename,$spec);
    }

    /**
     *
     * @return void
     */
    public function test_given_any_filename_zhoneconfigfilegenerator_will_generate_the_config_filename_for_for_27xx_models()
    {
        $spec = 'S0401086_2728A1_generic.conf';
        $filename = 'anything.conf';

        $ont = factory(Ont::class)->create(['model_number' => '2728A1']);
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id, 'version' => 'S04.01.086']);

        $config_filename_generator = new ZhoneConfigFilenameGenerator($ont_software);

        $this->assertEquals($spec, $config_filename_generator->generate());

        print $this->makeOutput($filename,$spec);
    }

    protected function makeOutput($filename, $spec)
    {
        return "\n\n\tUser Provided Filename\t\tConverts To\n\t".$filename."\t\t\t".$spec."\n";
    }
}
