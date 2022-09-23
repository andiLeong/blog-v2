<?php

namespace Tests\Feature;

use Tests\TestCase;

class ValidatorTest extends TestCase
{

    /** @test */
    public function it_can_check_a_required_rule()
    {
        $response = $this->get('/validate');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The foo is required', $body['errors']['foo'][0]);
        $this->assertArrayHasKey('foo', $body['errors']);

        $response = $this->get('/validate?foo=baz');
        $this->assertNoValidationError($response, 'foo');
    }

    /** @test */
    public function it_can_check_a_email_rule()
    {
        $response = $this->get('/validate?email=hi');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The email must be a valid email', $body['errors']['email'][0]);
        $this->assertArrayHasKey('email', $body['errors']);

        $response = $this->get('/validate?email=baz@gmail.com');
        $this->assertNoValidationError($response, 'email');
    }

    /** @test */
    public function it_can_check_a_min_rule()
    {
        $field = 'bar';
        $response = $this->get('/validate?bar=1');
        $body = $response->json();

        $this->assertEquals(422, $response->status());
        $this->assertEquals('The bar must at least be 3 long', $body['errors'][$field][0]);
        $this->assertArrayHasKey($field,$body['errors']);

        $response = $this->get('/validate?bar=foo');
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

        $response = $this->get('/validate?name=foo');
        $this->assertNoValidationError($response, $field);
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

    public function assertNoValidationError($response, $key)
    {
        $body = $response->json();
        if (isset($body['errors']) && $response->status() === 422) {
            $this->assertArrayNotHasKey($key, $body['errors']);
        } else {
            $this->assertArrayNotHasKey('errors', $body);
        }
    }
}
