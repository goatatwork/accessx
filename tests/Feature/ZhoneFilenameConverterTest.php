<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\GoldAccess\Ont\ZhoneFilenameConverter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ZhoneFilenameConverterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_filename_converter_has_default_values()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $this->assertEquals(false, $converter->oem);
        $this->assertEquals('ZNID-24xxA-301266-SIP.img', $converter->original_filename);
    }

    public function test_converter_can_explode_filenames_and_get_an_array_of_their_parts()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $this->assertEquals($converter->getFilenameParts(), ["ZNID","24xxA","301266","SIP"]);
    }

    public function test_converter_converts_non_oem_filenames()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), 'ZNID24xxASIP_0301266_image_with_cfe.img');
    }

    public function test_converter_converts_oem_filenames()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img', true);

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), 'ZNID24xxA_GRSIP_0301266_image_with_cfe.img');
    }

    public function test_converter_converts_27xx_filenames()
    {
        $converter = new ZhoneFilenameConverter('ZNID-27xxA1-401086-SIP.img');

        $converter->calculate();

        $this->assertEquals($converter->getDestinationFilename(), 'ZNID27xxA1SIP_0401086_image_with_cfe.img');
    }

    public function test_converter_can_generate_dhcp_string_for_zhone_onts()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $converter->calculate();

        $this->assertEquals('S0301266', $converter->dhcp_config_string);
    }

    public function test_converter_can_generate_software_version_string_for_database()
    {
        $converter = new ZhoneFilenameConverter('ZNID-24xxA-301266-SIP.img');

        $converter->calculate();

        $this->assertEquals('S03.01.266', $converter->version_string_for_database);
    }
}
