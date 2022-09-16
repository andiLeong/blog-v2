<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Practice\Observers\Dispatcher;
use App\Practice\Observers\Subject\PostWasCreated;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{

    /** @test */
    public function it_can_dispatch_event_as_string_and_get_the_results()
    {
        $dispatcher = new Dispatcher();
        $results = $dispatcher->dispatch(
            PostWasCreated::class,
            new Post(['body' => 'f']),
            'stub'
        );

        $this->assertEquals([true,true],$results);
    }

    /** @test */
    public function it_can_dispatch_event_as_object_and_get_the_results()
    {
        $dispatcher = new Dispatcher();
        $event = new PostWasCreated(new Post(['body' => 'f']),'stub');
        $results = $dispatcher->dispatch($event);

        $this->assertEquals([true,true],$results);
    }
}
