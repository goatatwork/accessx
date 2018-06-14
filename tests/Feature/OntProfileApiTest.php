<?php

namespace Tests\Feature;

use App\Ont;
use App\Port;
use App\User;
use App\IpAddress;
use App\OntProfile;
use Tests\TestCase;
use App\OntSoftware;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        config()->set('medialibrary.default_filesystem', 'media_test');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_fetch_profiles_for_ont_software()
    {
        $ont_software = factory(OntSoftware::class)->create();
        $ont_profiles = factory(OntProfile::class, 3)->make(['ont_software_id' => null]);
        foreach ($ont_profiles as $profile)
        {
            $ont_software->ont_profiles()->save($profile);
        }

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles');

        $response->assertJson([
            0 => [
                'name' => $ont_profiles[0]->name,
            ],
            1 => [
                'name' => $ont_profiles[1]->name,
            ],
            2 => [
                'name' => $ont_profiles[2]->name,
            ],
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_profiles_to_onts()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'NotZhone']);
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);
        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];

        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'name' => $ont_profile->name,
            'file' => [
                'file_name' => 'photoooo.jpg'
            ]
        ]);

        $profile = OntProfile::whereName($ont_profile->name)->first();
        $profile->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_profiles_to_zhone_onts()
    {
        // Start adding the Software
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        // $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg')];
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('ZNID-24xxA-301266-SIP.img', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);


        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => 'S03.01.266',
            'file' => [
                'file_name' => 'ZNID24xxA_GRSIP_0301266_image_with_cfe.img'
            ]
        ]);

        $software = OntSoftware::whereVersion('S03.01.266')->first();
        $this->assertFileExists($software->file->getPath());
        // End adding the Software

        // Attempt to create the OntProfile
        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.conf', 1024)];
        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'name' => $ont_profile->name,
            'file' => [
                'file_name' => 'S0301266_0GF_generic.conf'
            ]
        ]);

        $the_new_profile = OntProfile::whereName($ont_profile->name)->first();
        $this->assertFileExists($the_new_profile->file->getPath());

        // Clear the Media Collections
        $software->clearMediaCollection('default');
        $the_new_profile->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_update_ont_profile()
    {
        $ont_profile = factory(OntProfile::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/onts/ont_profiles/' . $ont_profile->id, ['name' => 'Profile Name']);

        $response->assertJson([
            'name' => 'Profile Name'
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_delete_profiles()
    {
        // Start adding the Software
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        // $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg')];
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('ZNID-24xxA-301266-SIP.img', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);


        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => 'S03.01.266',
            'file' => [
                'file_name' => 'ZNID24xxA_GRSIP_0301266_image_with_cfe.img'
            ]
        ]);

        $software = OntSoftware::whereVersion('S03.01.266')->first();
        $this->assertFileExists($software->file->getPath());
        // End adding the Software

        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];
        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $software->id . '/ont_profiles', $form_data);
        $response->assertJson([
            'name' => $ont_profile->name
        ]);

        $profile = OntProfile::whereName($ont_profile->name)->first();
        $this->assertFileExists($profile->file->getPath());

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/onts/ont_profiles/' . $profile->id);

        $this->assertDatabaseMissing('ont_profiles', ['name' => $ont_profile->name]);
        $this->assertFileNotExists($profile->file->getPath());

        $profile->clearMediaCollection('default');
    }

    /**
     * Test that ProvisioningRecord has a dhcp string
     */
    public function test_that_a_provisioning_record_knows_its_own_dhcp_string()
    {

        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        // $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg')];
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('ZNID-24xxA-301266-SIP.img', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);


        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => 'S03.01.266',
            'file' => [
                'file_name' => 'ZNID24xxA_GRSIP_0301266_image_with_cfe.img'
            ]
        ]);

        $software = OntSoftware::whereVersion('S03.01.266')->first();
        $this->assertFileExists($software->file->getPath());


        ///////////////////////////////////////////////////////////////////////

        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];

        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'name' => $ont_profile->name,
            'file' => [
                'file_name' => 'S0301266_0GF_generic.conf'
            ]
        ]);

        $profile = OntProfile::whereName($ont_profile->name)->first();
        $this->assertFileExists($profile->file->getPath());
        // \Log::info($profile->file->getUrl());

        $provisioning_record = factory(ProvisioningRecord::class)->create([
            'service_location_id' => '1',
            'ont_profile_id' => $profile->id,
            'port_id' => '1',
            'ip_address_id' => '1',
            'len' => 'A LEN For A Circuit',
            'circuit_id' => 'CIRCUIT01',
            'notes' => 'Some notes',
        ]);

        $this->assertEquals(
            $provisioning_record->dhcp_string,
            'ont_profiles/'.$provisioning_record->ont_profile->ont_software->ont->slug.'/S03.01.266/'.$profile->slug.'/S0301266'
        );


        $software->clearMediaCollection('default');
        $profile->clearMediaCollection('default');
    }

    public function test_ont_profile_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $ont_profile = $provrec->ont_profile;

        $this->assertTrue($ont_profile->has_provisioning_records);
    }

    public function test_ont_profile_knows_if_it_has_no_provisioning_records()
    {
        $profile = factory(OntProfile::class)->create();

        $this->assertFalse($profile->has_provisioning_records);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_validation_for_profile_name_if_profile_name_is_not_present()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'Zhone']);
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);
        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null, 'name' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];

        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'errors' => [
                'name' => [
                    'The name field is required.'
                ]
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_validation_for_profile_name_if_profile_name_is_duplicate_for_that_software_version()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'Zhone']);
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);
        $ont_profile = factory(OntProfile::class)->create(['ont_software_id' => $ont_software->id, 'name' => 'Suspended']);


        $new_ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null, 'name' => 'Suspended']);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];

        $form_data = array_merge($new_ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'errors' => [
                'name' => [
                    'A profile with that name already exists for this software version.'
                ]
            ]
        ]);
    }
}
