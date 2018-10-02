<?php

namespace Tests\Feature;

use App\Ont;
use App\User;
use Tests\TestCase;
use App\OntSoftware;
use App\ProvisioningRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntSoftwareApiTest extends TestCase
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
    public function test_api_will_fetch_software_for_an_ont()
    {
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class,5)->make(['ont_id' => null]);
        foreach ($ont_software as $software)
        {
            $ont->ont_software()->save($software);
        }

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts/' . $ont->id . '/software');

        $response->assertJson([
            0 => [
                'version' => $ont_software[0]->version,
            ],
            1 => [
                'version' => $ont_software[1]->version,
            ],
            4 => [
                'version' => $ont_software[4]->version,
            ],
        ]);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_software_to_onts()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'NotZhone']);
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        // $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg')];
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);


        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => $ont_software->version,
            'file' => [
                'file_name' => 'photoooo.jpg'
            ]
        ]);

        $software = OntSoftware::whereVersion($ont_software->version)->first();
        $software->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_software_to_zhone_onts()
    {
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('ZNID-24xxA-301266-SIP.img', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => 'S03.01.266',
            'file' => [
                'file_name' => 'ZNID24xxASIP_0301266_image_with_cfe.img'
            ]
        ]);

        $software = OntSoftware::whereVersion('S03.01.266')->first();
        $this->assertFileExists($software->file->getPath());
        $software->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_software_to_zhone_27xx_onts()
    {
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('ZNID-27xxA1-401086-SIP.img', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);

        $response->assertJson([
            'version' => 'S04.01.086',
            'file' => [
                'file_name' => 'ZNID27xxA1SIP_0401086_image_with_cfe.img'
            ]
        ]);

        $software = OntSoftware::whereVersion('S04.01.086')->first();
        $this->assertFileExists($software->file->getPath());
        $software->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_add_software_to_zhone_goldfield_oem_onts()
    {
        $ont = factory(Ont::class)->create(['oem' => true]);
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
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
        $software->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_fetch_media_files_for_ont_sofware()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'NotZhone']);
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);
        $response->assertJson([
            'version' => $ont_software->version,
            'file' => [
                'file_name' => 'photoooo.jpg'
            ]
        ]);

        $software = OntSoftware::whereVersion($ont_software->version)->first();
        $media_response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts/software/' . $software->id . '/files');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_update_ont_software()
    {
        $software = factory(OntSoftware::class)->create();

        $response = $this->actingAs($this->user, 'api')->json(
            'PATCH',
            '/api/onts/ont_software/' . $software->id,
            ['version' => '4']);

        $response->assertJson([
            'version' => '4'
        ]);

        $this->assertDatabaseHas('ont_software', ['version' => '4']);
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_will_delete_ont_software()
    {
        $ont = factory(Ont::class)->create(['manufacturer' => 'NotZhone']);
        $ont_software = factory(OntSoftware::class)->make(['ont_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];
        $form_data = array_merge($ont_software->toArray(), $file_to_upload);
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $ont->id . '/software', $form_data);
        $response->assertJson([
            'version' => $ont_software->version,
            'file' => [
                'file_name' => 'photoooo.jpg'
            ]
        ]);

        $software = OntSoftware::whereVersion($ont_software->version)->first();
        $this->assertFileExists($software->file->getPath());
        $media_response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/onts/ont_software/' . $software->id);

        $this->assertDatabaseMissing('ont_software', ['version' => $software->version]);
        $this->assertFileNotExists($software->file->getPath());
    }

    public function test_ont_software_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $ont_software = $provrec->ont_profile->ont_software;

        $this->assertTrue($ont_software->has_provisioning_records);
    }

    public function test_ont_software_knows_if_it_does_not_have_provisioning_records()
    {
        $ont_software = factory(OntSoftware::class)->create();

        $this->assertFalse($ont_software->has_provisioning_records);
    }
}
