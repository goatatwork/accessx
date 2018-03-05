<?php

namespace Tests\Feature;

use App\User;
use App\OntProfile;
use Tests\TestCase;
use App\OntSoftware;
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
        $ont_software = factory(OntSoftware::class)->create();
        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];

        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles', $form_data);

        $response->assertJson([
            'name' => $ont_profile->name
        ]);

        $profile = OntProfile::whereName($ont_profile->name)->first();
        $profile->clearMediaCollection('default');
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
        $ont_software = factory(OntSoftware::class)->create();
        $ont_profile = factory(OntProfile::class)->make(['ont_software_id' => null]);
        $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photoooo.jpg', 2048)];
        $form_data = array_merge($ont_profile->toArray(), $file_to_upload);
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/ont_software/' . $ont_software->id . '/ont_profiles', $form_data);
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
}
