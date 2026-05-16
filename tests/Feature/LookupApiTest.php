<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\Region;
use Tests\TestCase;

class LookupApiTest extends TestCase
{
    public function test_districts_endpoint_returns_all_when_no_region(): void
    {
        $this->actingAsAdmin();

        $r = Region::create(['name_uz' => 'Test viloyat', 'name_oz' => 'Тест']);
        District::create(['region_id' => $r->id, 'name_uz' => 'A tumani', 'name_oz' => 'А']);
        District::create(['region_id' => $r->id, 'name_uz' => 'B tumani', 'name_oz' => 'Б']);

        $this->get(route('lookups.districts'))
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_districts_filtered_by_region(): void
    {
        $this->actingAsAdmin();

        $r1 = Region::create(['name_uz' => 'V1', 'name_oz' => 'В1']);
        $r2 = Region::create(['name_uz' => 'V2', 'name_oz' => 'В2']);
        District::create(['region_id' => $r1->id, 'name_uz' => 'A1', 'name_oz' => 'А1']);
        District::create(['region_id' => $r2->id, 'name_uz' => 'A2', 'name_oz' => 'А2']);

        $resp = $this->get(route('lookups.districts', ['region_id' => $r1->id]));
        $resp->assertOk()->assertJsonCount(1)->assertJsonFragment(['name_uz' => 'A1']);
    }
}