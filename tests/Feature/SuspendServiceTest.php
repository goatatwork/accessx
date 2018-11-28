<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\OntProfile;
use App\OntSoftware;
use App\Jobs\RebootOnt;
use App\ProvisioningRecord;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\ProvisioningRecordWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspendServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_ont_software_knows_if_it_has_a_suspend_config()
    {
        $pr = factory(ProvisioningRecord::class)->create();

        $software = $pr->ont_profile->ont_software;

        $this->assertFalse($software->has_suspend_config);

        $suspended_config = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Suspended']);

        $this->assertTrue($software->has_suspend_config);
    }

    public function test_provisioning_record_knows_if_it_is_suspended()
    {
        $ont_profile = factory(OntProfile::class)->create(['name' => 'Suspended']);

        $pr = factory(ProvisioningRecord::class)->create(['ont_profile_id' => $ont_profile->id]);

        $this->assertTrue($pr->is_suspended);
    }

    public function test_provisioning_record_knows_if_it_is_not_suspended()
    {
        $pr = factory(ProvisioningRecord::class)->create();

        $this->assertFalse($pr->is_suspended);
    }

    public function test_a_prs_last_profile_id_is_the_first_profile_id_for_the_software_when_there_is_no_previous_profile()
    {
        $software = factory(OntSoftware::class)->create();

        $unlimited = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Unlimited', 'slug' => 'unlimited']);
        $tenbyten = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => '10x10', 'slug' => '10x10']);
        $suspended = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Suspended', 'slug' => 'suspended']);
        $fiftybyfifty = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => '50x50', 'slug' => '50x50']);

        $pr = factory(ProvisioningRecord::class)->create(['ont_profile_id' => $suspended->id]);

        $this->assertEquals($pr->previous_profile_id, $unlimited->id);
    }

    public function test_a_pr_knows_its_last_profile_id()
    {
        $software = factory(OntSoftware::class)->create();

        $unlimited = factory(OntProfile::class)->create([
            'ont_software_id' => $software->id,
            'name' => 'Unlimited',
            'slug' => 'unlimited'
        ]);

        $tenbyten = factory(OntProfile::class)->create([
            'ont_software_id' => $software->id,
            'name' => '10x10',
            'slug' => '10x10'
        ]);

        $suspended = factory(OntProfile::class)->create([
            'ont_software_id' => $software->id,
            'name' => 'Suspended',
            'slug' => 'suspended'
        ]);

        $fiftybyfifty = factory(OntProfile::class)->create([
            'ont_software_id' => $software->id,
            'name' => '50x50',
            'slug' => '50x50'
        ]);

        // First, provision with Unlimited
        $pr = factory(ProvisioningRecord::class)->create(['ont_profile_id' => $fiftybyfifty->id]);
        $this->assertEquals($pr->ont_profile->name, '50x50');

        // Then change to 10x10
        $pr->update(['ont_profile_id' => $tenbyten->id]);

        $pr = ProvisioningRecord::find($pr->id);

        $this->assertEquals($pr->ont_profile->name, '10x10');
        $this->assertEquals($pr->previous_profile_id, $fiftybyfifty->id);
    }

    public function test_patch_can_suspend_provisioning_record()
    {
        Event::fake();
        Queue::fake();

        $software = factory(OntSoftware::class)->create();

        $regular_config = factory(OntProfile::class)->create(['ont_software_id' => $software->id]);
        $suspend_config = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Suspended', 'slug' => 'suspended']);

        $pr = factory(ProvisioningRecord::class)->create(['ont_profile_id' => $regular_config->id]);

        $pr->fresh();

        $this->assertFalse($pr->is_suspended);

        $response = $this->actingAs($this->user)->patch('/provisioning/'.$pr->id.'/suspend');

        $response->assertStatus(302);

        $pr_again = ProvisioningRecord::find($pr->id);
        $this->assertTrue($pr_again->is_suspended);
        $this->assertEquals($pr_again->previous_profile_id, $regular_config->id);

        Event::assertDispatched(ProvisioningRecordWasUpdated::class);
        Queue::assertPushed(RebootOnt::class);
    }

    public function test_patch_can_unsuspend_provisioning_record()
    {
        Event::fake();
        Queue::fake();

        $software = factory(OntSoftware::class)->create();

        $unlimited = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Unlimited', 'slug' => 'unlimited']);
        $suspend_config = factory(OntProfile::class)->create(['ont_software_id' => $software->id, 'name' => 'Suspended', 'slug' => 'suspended']);

        $pr = factory(ProvisioningRecord::class)->create(['ont_profile_id' => $suspend_config->id]);

        $pr->fresh();

        $this->assertTrue($pr->is_suspended);

        $response = $this->actingAs($this->user)->patch('/provisioning/'.$pr->id.'/unsuspend');

        $response->assertStatus(302);

        $pr_again = ProvisioningRecord::find($pr->id);
        $this->assertFalse($pr_again->is_suspended);

        $this->assertEquals($pr_again->previous_profile_id, $unlimited->id);

        Event::assertDispatched(ProvisioningRecordWasUpdated::class);
        Queue::assertPushed(RebootOnt::class);
    }
}
