<?php

namespace Tests\Feature;

use Tests\TestCase;

class ValidatorTest extends TestCase
{

    /** @test */
    public function it_can_check_a_required_field()
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
    public function it_can_check_a_email_field()
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
    public function it_can_check_a_min_field()
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
