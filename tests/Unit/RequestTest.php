<?php

namespace Tests\Unit;

use App\Practice\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        parent::setUp();
        Request::$test = true;
        $this->request = new Request($this->query(), $this->payload());
    }

    /** @test */
    public function it_check_if_key_is_exists()
    {
        $this->assertTrue($this->request->has('foo', 'age'));
        $this->assertTrue($this->request->has(['foo', 'age']));
        $this->assertFalse($this->request->has('hi', 'age'));
    }

    /** @test */
    public function it_get_value_from_a_key()
    {
        $this->assertEquals($this->payload()['age'],$this->request->get('age'));
        $this->assertEquals('default',$this->request->get('not_found','default'));
    }

    /** @test */
    public function it_get_a_key_from_the_data_as_a_property()
    {
        $this->assertEquals('candy',$this->request->name);
    }

    /** @test */
    public function it_dynamically_set_a_request_object_property()
    {
        $this->assertNull($this->request->status);
        $this->request->status = true;
        $this->assertTrue($this->request->status);
    }

    /** @test */
    public function it_get_certain_keys_from_the_data()
    {
        $this->assertEquals($this->payload(),$this->request->only('name','age','sex','hi'));
    }

    /** @test */
    public function it_exclude_certain_keys_from_the_data()
    {
        $this->assertEquals([
            'baz' => 'chu',
            'name' => 'candy',
        ],$this->request->except('foo','age','sex','hi'));
    }

    /** @test */
    public function it_can_merge_additional_data_to_the_request_data_set()
    {
        $this->assertEmpty($this->request->addOn());
        $this->request->merge(['additional' => true,'age' => '25']);

        $this->assertArrayHasKey('additional',$this->request->all());
        $this->assertTrue($this->request->all()['additional']);
        $this->assertEquals('25',$this->request->all()['age']);
        $this->assertEquals([
            'age' => '25',
            'additional' => true
        ],$this->request->addOn());
        $this->assertNotEmpty($this->request->addOn());
    }

    /** @test */
    public function it_can_merge_additional_data_to_the_request_data_set_only_if_those_keys_not_found()
    {
        $this->assertEmpty($this->request->addOn());
        $this->request->mergeIfNotExist(['additional' => true,'age' => '25']);

        $this->assertEquals([
            'additional' => true
        ],$this->request->addOn());
        $this->assertArrayHasKey('additional',$this->request->all());
        $this->assertNotEquals('25',$this->request->all()['age']);
    }

    /** @test */
    public function it_can_get_a_original_request_data_without_the_add_on()
    {
        $this->request->mergeIfNotExist(['additional' => true,'age' => '25']);
        $this->assertEquals(array_merge($this->query(),$this->payload()),$this->request->original());
    }

    /** @test */
    public function it_can_get_a_query_of_the_request()
    {
        $this->assertEquals($this->query(),$this->request->query());
    }

    /** @test */
    public function it_can_get_a_payload_of_the_request()
    {
        $this->assertEquals($this->payload(),$this->request->payload());
    }

    public function query()
    {
        return [
            'foo' => 'bar',
            'baz' => 'chu',
        ];
    }

    public function payload()
    {
        return [
            'name' => 'candy',
            'age' => 16,
            'sex' => 'f',
        ];
    }


}
