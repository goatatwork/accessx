<?php

namespace Tests\Feature;

use App\Ont;
use App\User;
use Tests\TestCase;
use App\OntSoftware;
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
        $ont = factory(Ont::class)->create();
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
    public function test_api_will_fetch_media_files_for_ont_sofware()
    {
        $ont = factory(Ont::class)->create();
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
        $ont = factory(Ont::class)->create();
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
}
