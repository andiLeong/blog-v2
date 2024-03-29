<?php

namespace Tests\Feature;

use App\FakeQueryBuilder;
use App\Models\Order;
use Illuminate\Support\Collection;
use Tests\DbConnection;
use Tests\TestCase;

class RunFakeQueryBuilderTest extends TestCase
{
    use DbConnection;

    private $queryBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildConnection();
        $this->queryBuilder = new FakeQueryBuilder(self::$connection);
        Order::truncate();
    }

    /** @test */
    public function it_can_get_a_list_of_results_from_builder()
    {
        $orders = create(Order::class, ['country' => 'foo'], 2);
        $res = $this->queryBuilder
            ->from('orders')
            ->where('country', 'foo')
            ->get();

        $this->assertInstanceOf(Collection::class, $res);
        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_select_from_builder()
    {
        $orders = create(Order::class, ['country' => 'foo'], 2);
        $res = $this->queryBuilder
            ->from('orders')
            ->select('id', 'country')
            ->where('country', 'foo')
            ->get();

        $this->assertFalse(property_exists($res[0], 'number'));
        $this->assertEquals($res[0]->country, $orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_get_columns_from_builder()
    {
        $orders = create(Order::class, ['country' => 'foo'], 2);
        $res = $this->queryBuilder
            ->from('orders')
            ->where('country', 'foo')
            ->get(['id', 'country']);

        $this->assertFalse(property_exists($res[0], 'number'));
        $this->assertEquals($res[0]->country, $orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_get_null_from_builder()
    {
        create(Order::class, ['updated_at' => null, 'country' => 'foo'], 2);
        $order = create(Order::class);
        $res = $this->queryBuilder
            ->from('orders')
            ->whereNull('updated_at')
            ->get();

        $doesntContain = $res->pluck('country')->doesntContain($order->country);
        $this->assertTrue($doesntContain);
        $this->assertNull($res[0]->updated_at);


        $res2 = $this->queryBuilder
            ->from('orders')
            ->where('updated_at')
            ->get();

        $doesntContain2 = $res2
            ->pluck('country')
            ->doesntContain($order->country);

        $this->assertTrue($doesntContain2);
        $this->assertNull($res2[0]->updated_at);

    }

    /** @test */
    public function it_can_get_a_list_of_results_when_pass_array_to_where_builder()
    {
        $orders = create(Order::class, ['number' => 99, 'country' => 'foo'], 2);
        create(Order::class);
        $res = $this->queryBuilder
            ->from('orders')
            ->where([
                'number' => '99',
                'country' => 'foo',
            ])
            ->get();

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $orders[0]->country);

    }

    /** @test */
    public function it_can_get_a_list_of_results_when_pass_closure_to_where_builder()
    {
        $orders = create(Order::class, ['number' => 99, 'country' => 'foo'], 2);
        create(Order::class);
        $res = $this->queryBuilder
            ->from('orders')
            ->where(function ($query) {
                return $query->where('country', 'foo')
                    ->where('number', 99);
            })
            ->get();

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $orders[0]->country);

    }

    /** @test */
    public function it_can_get_a_list_of_results_with_dynamic_where()
    {
        $orders = create(Order::class, ['number' => 99, 'country' => 'foo'], 2);
        create(Order::class);
        $res = $this->queryBuilder
            ->from('orders')
            ->whereNumber(99)
            ->get();

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $orders[0]->country);

        $res2 = $this->queryBuilder
            ->from('orders')
            ->whereCountry('like', '%fo%')
            ->get();

        $this->assertEquals(2, $res2->count());
        $this->assertEquals($res2[0]->country, $orders[0]->country);

    }

    /** @test */
    public function it_can_get_a_list_of_results_with_where_in()
    {
        $order1 = create(Order::class, ['country' => 'foo']);
        $order2 = create(Order::class, ['country' => 'bar']);
        create(Order::class);
        $res = $this->queryBuilder
            ->from('orders')
            ->whereIn('country', ['foo', 'bar'])
            ->get();

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $order1->country);
        $this->assertEquals($res[1]->country, $order2->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_where_between()
    {
        $order1 = create(Order::class, ['price' => 99]);
        $order2 = create(Order::class, ['price' => 10]);
        $order3 = create(Order::class, ['price' => 9]);
        $res = $this->queryBuilder
            ->from('orders')
            ->whereIn('price', [10, 99])
            ->get();

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $order1->country);
        $this->assertEquals($res[1]->country, $order2->country);
    }

    /** @test */
    public function it_can_get_the_first_record_by_using_first()
    {
        $order1 = create(Order::class, ['country' => 'foo']);
        $order2 = create(Order::class, ['country' => 'bar']);
        $res = $this->queryBuilder
            ->from('orders')
            ->first();

        $this->assertEquals($res->country, $order1->country);
        $this->assertNotEquals($res->country, $order2->country);
    }

    /** @test */
    public function it_can_get_the_find_records()
    {
        $order1 = create(Order::class, ['country' => 'foo']);
        $order2 = create(Order::class, ['country' => 'bar']);
        $res = $this->queryBuilder
            ->from('orders')
            ->find([1, 2]);

        $this->assertEquals(2, $res->count());
        $this->assertEquals($res[0]->country, $order1->country);
        $this->assertEquals($res[1]->country, $order2->country);

        $res2 = $this->queryBuilder
            ->from('orders')
            ->find(2);

        $this->assertEquals($res2->country, $order2->country);
    }

    /** @test */
    public function it_can_insert_a_record()
    {
        $this->assertDatabaseCount('orders', 0);
        $data = [
            'country' => 'foo',
            'price' => 99,
            'customer' => 'foo',
            'status' => 'foo',
            'number' => 'foo',
            'paid' => 1
        ];
        $id = $this->queryBuilder
            ->from('orders')
            ->insert($data);

        $this->assertEquals(1, $id);
        $this->assertDatabaseHas('orders', $data);
        $this->assertDatabaseCount('orders', 1);
    }

    /** @test */
    public function it_can_update_a_record()
    {
        $order1 = create(Order::class, ['country' => 'foo']);

        $this->assertEquals('foo', $order1->fresh()->country);
        $res = $this->queryBuilder
            ->from('orders')
            ->update(['country' => 'bar']);

        $this->assertTrue((bool)$res);
        $this->assertEquals('bar', $order1->fresh()->country);
    }

    /** @test */
    public function it_can_delete_a_record()
    {
        $order1 = create(Order::class, ['country' => 'foo']);
        $this->assertDatabaseCount('orders', 1);
        $res = $this->queryBuilder
            ->from('orders')
            ->delete(1);

        $this->assertTrue((bool)$res);
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function it_can_delete_multiple_records()
    {
        $order1 = create(Order::class, ['country' => 'foo']);
        $order2 = create(Order::class, ['country' => 'bar']);
        $order3 = create(Order::class, ['country' => 'bar']);

        $this->assertDatabaseCount('orders', 3);
        $res = $this->queryBuilder
            ->from('orders')
            ->where(['country' => 'bar'])
            ->delete();

        $this->assertTrue((bool)$res);
        $this->assertDatabaseCount('orders', 1);
    }
}
