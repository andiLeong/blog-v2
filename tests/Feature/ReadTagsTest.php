<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ReadTagsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_get_all_the_tags()
    {
        $tags = create(Tag::class, [], 3);
        $response = $this->get('/api/tags')->assertStatus(200)->json();

        $this->assertCount($tags->count(), $response);
        $this->assertEquals($tags[0]->name, $response[0]['name']);
    }
}
