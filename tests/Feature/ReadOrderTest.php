<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ReadOrderTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp() :void
    {
        parent::setUp();
    }


    /** @test */
    public function it_can_get_list_of_orders()
    {
        $order = create(Order::class);
        $this->getOrder()->assertStatus(200)->assertSee($order->country);
    }

    /** @test */
    public function it_can_filter_order_by_country()
    {
        $this->withoutExceptionHandling();
        $canada = create(Order::class,['country' => 'Canada']);
        $china = create(Order::class,['country' => 'China']);
        $this->getOrder(['country' => $canada->country])->assertSee($canada->number)->assertDontSee($china->number);
    }

    /** @test */
    public function it_can_filter_order_by_number()
    {
        $order = create(Order::class,[],2);
        $this->getOrder(['number' => $order[0]->number])->assertSee($order[0]->number)->assertDontSee($order[1]->number);
    }

    /** @test */
    public function it_can_filter_order_by_customer()
    {
        $this->withoutExceptionHandling();
        $cindy = create(Order::class,['customer' => 'cindy']);
        $ronald = create(Order::class,['customer' => 'ronald']);
        $this->getOrder(['customer' => 'cin'])->assertSee($cindy->customer)->assertDontSee($ronald->customer);
    }

    /** @test */
    public function it_can_filter_order_by_greater_than()
    {
        $this->withoutExceptionHandling();
        $order = create(Order::class,['price' => 100]);
        $order1 = create(Order::class,['price' => 10]);
        $order2 = create(Order::class,['price' => 99]);
        $this->getOrder(['price_greater_than' => 99])->assertSee($order->number)->assertDontSee($order2->number);
    }

    /** @test */
    public function it_can_filter_order_by_lesser_or_equal_than()
    {
        $order = create(Order::class,['price' => 100]);
        $order1 = create(Order::class,['price' => 10]);
        $order2 = create(Order::class,['price' => 99]);
        $this->getOrder(['price_lesser_or_equal_than' => 99])->assertSee($order2->number)->assertDontSee($order->number);
    }

    /** @test */
    public function it_can_filter_order_by_lesser_than()
    {
        $order = create(Order::class,['price' => 100]);
        $order1 = create(Order::class,['price' => 10]);
        $order2 = create(Order::class,['price' => 99]);
        $this->getOrder(['price_lesser_than' => 100])->assertSee($order2->number)->assertDontSee($order->number);
    }

    /** @test */
    public function it_can_filter_order_by_latest_timestamp()
    {
        $this->markTestSkipped();
        $old = create(Order::class,['created_at' => now()->subDays(5)]);
        $new = create(Order::class,['created_at' => now()]);

        $response = $this->getOrder()->json();
        $this->assertEquals([$old->id,$new->id], array_column($response['data'],'id'));

        $response = $this->getOrder(['latest' => 1])->json();
        $this->assertEquals([$new->id,$old->id], array_column($response['data'],'id'));
    }

    /** @test */
    public function it_can_order_the_orders_by_an_attribute()
    {
        $this->markTestSkipped();
        $america = create(Order::class,['country' => 'America']);
        $china = create(Order::class,['country' => 'China']);


        $response = $this->getOrder(['order_by' => 'country'])->json();
        $this->assertEquals([$china->country,$america->country], array_column($response['data'],'country'));

        $response = $this->getOrder(['order_by' => 'country','direction' => 'asc'])->json();
        $this->assertEquals([$america->country,$china->country], array_column($response['data'],'country'));
    }

    public function getOrder($queryString = [])
    {
        $query = http_build_query($queryString);
        return $this->get("api/order?$query");
    }

}
