<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\BillingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingRecordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @return void
     */
    public function test_api_can_update_billing_record()
    {
        $billing_record = factory(BillingRecord::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/billing_records/' . $billing_record->id, ['phone1' => '111-111-1111']);

        $response->assertJson([
            'phone1' => '111-111-1111'
        ]);

        $b_record = BillingRecord::find($billing_record->id);
        $this->assertEquals($b_record->phone1, '111-111-1111');

    }

    /**
     * @return void
     */
    public function test_web_can_update_billing_record()
    {
        $billing_record = factory(BillingRecord::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/billing_records/' . $billing_record->id, ['phone1' => '111-111-1111']);

        $b_record = BillingRecord::find($billing_record->id);
        $this->assertEquals($b_record->phone1, '111-111-1111');

    }

}
