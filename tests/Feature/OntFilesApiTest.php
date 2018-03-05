<?php

namespace Tests\Feature;

use App\Ont;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OntFilesApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->ont = factory(Ont::class)->create();
        $this->user = factory(User::class)->create();
        config()->set('medialibrary.default_filesystem', 'media_test');
    }

    // public function tearDown()
    // {
    //     $this->ont->clearMediaCollection('default');
    // }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_can_list_files_for_an_ont()
    {
        $this->withoutExceptionHandling();
        $file_to_upload1 = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo1.jpg'), 'description' => 'Description for uploaded_file1'];
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $this->ont->id . '/files', $file_to_upload1);
        $file_to_upload2 = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo2.jpg'), 'description' => 'Description for uploaded_file2'];
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $this->ont->id . '/files', $file_to_upload2);
        $file_to_upload3 = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo3.jpg'), 'description' => 'Description for uploaded_file3'];
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $this->ont->id . '/files', $file_to_upload3);

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/onts/' . $this->ont->id . '/files');

        $response->assertJson([
            0 => [
                'file_name' => 'photo1.jpg',
                'description' => 'Description for uploaded_file1'
            ],
            1 => [
                'file_name' => 'photo2.jpg',
                 'description' => 'Description for uploaded_file2'
            ],
            2 => [
                'file_name' => 'photo3.jpg',
                'description' => 'Description for uploaded_file3'
            ]
        ]);
        $this->ont->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_can_upload_ont_files()
    {
        $this->withoutExceptionHandling();

        $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg'), 'description' => 'Description for file'];
        // $file_to_upload = ['uploaded_file' => UploadedFile::fake()->create('photo.jpg', 2048)];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $this->ont->id . '/files', $file_to_upload);

        $response->assertJson([
            'file_name' => 'photo.jpg',
            'collection_name' => 'default',
            'description' => 'Description for file'
        ]);

        $response->assertStatus(200);

        $files = $this->ont->getMedia();

        $this->assertFileExists($files->first()->getPath());
        $this->ont->clearMediaCollection('default');
    }

    /**
     * @group onts
     * @return  void
     */
    public function test_api_can_delete_media_files_when_ont_is_deleted()
    {
        $this->withoutExceptionHandling();
        $file_to_upload = ['uploaded_file' => \Illuminate\Http\Testing\File::image('photo.jpg'), 'description' => 'Description for file'];
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/onts/' . $this->ont->id . '/files', $file_to_upload);
        $response->assertJson([
            'file_name' => 'photo.jpg',
            'collection_name' => 'default',
            'description' => 'Description for file'
        ]);
        $response->assertStatus(200);
        $file = $this->ont->getFirstMedia();
        $this->assertFileExists($file->getPath());

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/onts/' . $this->ont->id);

        $this->assertFileNotExists($file->getPath());
        $this->ont->clearMediaCollection('default');
    }
}
