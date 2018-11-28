<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\OntSoftware;
use App\GoldAccess\Ont\ZhoneConfigFile;
use Illuminate\Foundation\Testing\WithFaker;
use App\GoldAccess\Ont\ZhoneFilenameConverter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ZhoneFilenameConverterTest extends TestCase
{
    /**
     * @test
     */
    public function test_the_converter_requires_a_filename_string_to_work_with_and_will_throw_a_typeerror_if_it_is_omitted()
    {
        $this->expectException('TypeError');
        $converter = new ZhoneFilenameConverter();
    }

    public function test_the_converter_can_explode_the_filename_string_and_get_an_array_of_its_parts()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $this->assertEquals($converter->getFilenameParts(), ["ZNID","24xxA","301266","SIP"]);
    }


    /**
     *
     * @return void
     */
    public function test_the_converter_has_an_oem_property_that_can_be_set_to_true_when_object_is_created()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img', 1);

        $this->assertEquals(true, $converter->oem);
    }

    /**
     *
     * @return void
     */
    public function test_the_converter_has_an_oem_property_that_is_false_if_not_specified()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $this->assertEquals(false, $converter->oem);
    }

    public function test_the_converter_converts_non_oem_filenames_to_spec()
    {
        $spec = 'ZNID24xxASIP_0301266_image_with_cfe.img';
        $filename = 'ZNID-24xxA-301266-SIP.img';

        $converter = new ZhoneFilenameConverter($filename);

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), $spec);

        print $this->makeOutput($filename,$spec);
        // print "\n\n\tFilename\t\t\tConverts To\n\tZNID-24xxA-301266-SIP.img\tZNID24xxASIP_0301266_image_with_cfe.img\n";
    }

    public function test_the_converter_converts_oem_filenames_to_spec()
    {
        $spec = 'ZNID24xxA_GRSIP_0301266_image_with_cfe.img';
        $filename = 'ZNID-24xxA-301266-SIP.img';

        $converter = new ZhoneFilenameConverter($filename, true);

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), $spec);

        print $this->makeOutput($filename,$spec);
    }

    public function test_the_converter_converts_27xx_filenames_to_spec()
    {
        $spec = 'ZNID27xxA1SIP_0401086_image_with_cfe.img';
        $filename = 'ZNID-27xxA1-401086-SIP.img';

        $converter = new ZhoneFilenameConverter($filename);

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), $spec);

        print $this->makeOutput($filename,$spec);
    }

    public function test_the_converter_can_generate_dhcp_string_for_zhone_onts()
    {
        $expected_dhcp_string = 'S0301266';
        $filename = 'ZNID-24xxA-301266-SIP.img';

        $converter = new ZhoneFilenameConverter($filename);

        $converter->calculate();

        $this->assertEquals($expected_dhcp_string, $converter->dhcp_config_string);

        print $this->makeOutput($filename,$expected_dhcp_string);
    }

    public function test_the_converter_can_generate_software_version_string_for_database()
    {
        $expected_software_version_string = 'S03.01.266';
        $filename = 'ZNID-24xxA-301266-SIP.img';

        $converter = new ZhoneFilenameConverter($filename);

        $converter->calculate();

        $this->assertEquals($expected_software_version_string, $converter->version_string_for_database);

        print $this->makeOutput($filename,$expected_software_version_string);
    }

    protected function makeOutput($filename, $spec)
    {
        return "\n\n\tFilename\t\t\tConverts To\n\t".$filename."\t".$spec."\n";
    }
}
