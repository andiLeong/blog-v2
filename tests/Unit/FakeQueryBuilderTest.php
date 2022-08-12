<?php

namespace Tests\Unit;

use App\FakeQueryBuilder;
use Illuminate\Database\MySqlConnection;
use Tests\TestCase;

class FakeQueryBuilderTest extends TestCase
{
    private $queryBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE_TEST');
        $pdo = new \PDO("mysql:host=localhost;dbname=$database", $username, $password);
        $mysqlConnection = new MySqlConnection($pdo);
        $this->queryBuilder = new FakeQueryBuilder($mysqlConnection);
    }

    /** @test */
    public function it_can_set_a_list_of_columns()
    {
        $this->queryBuilder->select('id', 'foo');
        $this->queryBuilder->select(['name']);

        $columns = $this->queryBuilder->columns;

        $this->assertEquals($columns, ['id', 'foo', 'name']);

    }

    /** @test */
    public function it_can_set_a_target_table()
    {
        $this->assertNull($this->queryBuilder->from);
        $this->queryBuilder->from('orders');
        $this->assertEquals('orders', $this->queryBuilder->from);
    }

    /** @test */
    public function it_can_set_a_list_of_wheres()
    {

        $this->queryBuilder
            ->where('id', '>=', 5)
            ->where('name', 'an');

        $wheres = [
            [
                'type' => 'Basic',
                'column' => 'id',
                'value' => 5,
                'operator' => '>=',
                'boolean' => 'and',
            ],
            [
                'type' => 'Basic',
                'column' => 'name',
                'value' => 'an',
                'operator' => '=',
                'boolean' => 'and',
            ]
        ];
        $this->assertEquals($wheres,$this->queryBuilder->wheres);
    }

    /** @test */
    public function bindings_are_set_correctly_when_where_are_applied()
    {
        $this->queryBuilder
            ->where('id', '>=', 5)
            ->where('name', 'an');

        $bindings = [5,'an'];
        $this->assertEquals($bindings,$this->queryBuilder->bindings['where']);
    }
}
