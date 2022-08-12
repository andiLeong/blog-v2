<?php

namespace Tests\Feature;

use App\FakeQueryBuilder;
use App\Models\Order;
use Illuminate\Database\MySqlConnection;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RunFakeQueryBuilderTest extends TestCase
{

    private $queryBuilder;
    private $orders;

    protected function setUp(): void
    {
        parent::setUp();


        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE_TEST');
        $pdo = new \PDO("mysql:host=localhost;dbname=$database", $username, $password);
        $mysqlConnection = new MySqlConnection($pdo);
        $this->queryBuilder = new FakeQueryBuilder($mysqlConnection);
//        $this->orders = create(Order::class,[],10);
        Order::truncate();
    }

    /** @test */
    public function it_can_get_a_list_of_results_from_builder()
    {
        $orders = create(Order::class,['country' => 'foo'],2);
        $res = $this->queryBuilder
            ->from('orders')
            ->where('country','foo')
            ->get();

        $this->assertInstanceOf(Collection::class,$res);
        $this->assertEquals(2,$res->count());
        $this->assertEquals($res[0]->country,$orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_select_from_builder()
    {
        $orders = create(Order::class,['country' => 'foo'],2);
        $res = $this->queryBuilder
            ->from('orders')
            ->select('id','country')
            ->where('country','foo')
            ->get();

        $this->assertFalse(property_exists($res[0],'number'));
        $this->assertEquals($res[0]->country,$orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_get_columns_from_builder()
    {
        $orders = create(Order::class,['country' => 'foo'],2);
        $res = $this->queryBuilder
            ->from('orders')
            ->where('country','foo')
            ->get(['id','country']);

        $this->assertFalse(property_exists($res[0],'number'));
        $this->assertEquals($res[0]->country,$orders[0]->country);
    }

    /** @test */
    public function it_can_get_a_list_of_results_with_get_null_from_builder()
    {
        create(Order::class,['updated_at' => null,'country' => 'foo'],2);
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
}
