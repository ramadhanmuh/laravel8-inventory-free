<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
use Illuminate\Support\Str;

class StockTest extends TestCase
{
    public function test_index()
    {
        $user = User::inRandomOrder()->first();

        $url = 'stocks';

        $response = $this->actingAs($user)
                            ->get($url);

        $response->assertStatus(200);
    }

    public function test_with_filter()
    {
        $user = User::inRandomOrder()->first();

        $keyword = Str::random(5);

        $order_by = 'part_number';

        $order_direction = 'desc';

        $category = Category::inRandomOrder()->first();

        $brand = Brand::inRandomOrder()->first();

        $uom = UnitOfMeasurement::inRandomOrder()->first();

        $start_stock = rand(0, 10);

        $end_stock = rand(11, 9999999999);

        $url = "stocks?order_by=$order_by";

        $url .= "&order_direction=$order_direction";

        $url .= "&category_id=$category->id";

        $url .= "&brand_id=$brand->id";

        $url .= "&uom_id=$uom->id";

        $url .= "&start_stock=$start_stock";

        $url .= "&end_stock=$end_stock";

        $response = $this->actingAs($user)
                            ->get($url);

        $response->assertStatus(200);
    }
}