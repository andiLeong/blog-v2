<?php

namespace Tests\Feature;

use Tests\TestCase;

class ValidatorTest extends TestCase
{

    /** @test */
    public function it_can_check_a_required_rule()
    {
        $field = 'name';
        $response = $this->get('/validate');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The name is required', $body['errors'][$field][0]);
        $this->assertArrayHasKey('name', $body['errors']);

        $response = $this->get('/validate?name=baz');
        $this->assertNoValidationError($response, $field);
    }

    /** @test */
    public function it_can_check_a_required_if_rule()
    {
        $field = 'age';
        $response = $this->get('/validate?name=foo');
        $body = $response->json();

        $this->assertEquals('age is required if name is provided', $body['errors'][$field][0]);
        $this->assertArrayHasKey('age', $body['errors']);

        $response = $this->get('/validate?name=baz&age=19');
        $this->assertNoValidationError($response, $field);
    }

    /** @test */
    public function it_can_check_a_between_rule()
    {
        $field = 'age';
        $response = $this->get('/validate?age=10');
        $body = $response->json();

        $this->assertInArray('The age must between 18,60',$body['errors'][$field]);
        $this->assertArrayHasKey($field, $body['errors']);
    }

    /** @test */
    public function it_can_check_a_email_rule()
    {
        $response = $this->get('/validate?email=hi');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The email must be a valid email', $body['errors']['email'][0]);
        $this->assertArrayHasKey('email', $body['errors']);

        $response = $this->get('/validate?email=abaz@gmail.com');
        $this->assertNoValidationError($response, 'email');
    }


    /** @test */
    public function it_can_check_a_starts_with_rule()
    {
        $field = 'email';
        $response = $this->get('/validate?email=hi');
        $body = $response->json();

        $this->assertInArray('The email must starts with a',$body['errors'][$field]);
        $this->assertArrayHasKey($field, $body['errors']);
    }

    /** @test */
    public function it_can_check_a_min_rule()
    {
        $field = 'name';
        $response = $this->get('/validate?name=1');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The name must at least be 3 long', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);

        $response = $this->get('/validate?name=foz');
        $this->assertNoValidationError($response, $field);
    }

    /** @test */
    public function it_can_check_a_max_rule()
    {
        $field = 'name';
        $response = $this->get('/validate?name=11111111111111');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The name must not exceed 10 long', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);

        $response = $this->get('/validate?name=foz');
        $this->assertNoValidationError($response, $field);
    }


    /** @test */
    public function it_can_check_a_ends_with_rule()
    {
        $field = 'name';
        $response = $this->get('/validate?name=11111111111111');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertInArray('The name must ends with z',$body['errors'][$field]);
        $this->assertArrayHasKey($field,$body['errors']);
    }

    /** @test */
    public function it_can_check_a_in_rule()
    {
        $field = 'status';
        $response = $this->get('/validate?status=2');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The status is not in 0,1', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);

        $response = $this->get('/validate?status=0');
        $this->assertNoValidationError($response, $field);
    }

    /** @test */
    public function it_can_check_a_custom_object_rule()
    {
        $field = 'custom';
        $response = $this->get('/validate?custom=2');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The value is not answer', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);

        $response = $this->get('/validate?custom=answer');
        $this->assertNoValidationError($response, $field);
    }

    /** @test */
    public function it_support_custom_message()
    {
        $field = 'custom';
        $response = $this->get('/validate');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('you must fill in custom field', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);
    }

    /** @test */
    public function it_support_closure_validation_rule()
    {
        $field = 'closure';
        $response = $this->get('/validate');
        $body = $response->json();

        $this->assertEquals('a custom closure error message', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);
    }

    public function assertNoValidationError($response, $key)
    {
        $body = $response->json();
        if (isset($body['errors']) && $response->status() === 422) {
            $this->assertArrayNotHasKey($key, $body['errors']);
        } else {
            $this->assertArrayNotHasKey('errors', $body);
        }
    }

    private function assertInArray($item,$array)
    {
        $this->assertTrue(in_array($item,$array));
    }
}
