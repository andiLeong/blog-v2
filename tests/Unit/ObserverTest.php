<?php

use App\Practice\Observers\Bar;
use App\Practice\Observers\Foo;
use App\Practice\Observers\Observer;
use PHPUnit\Framework\TestCase;

class ObserverTest extends TestCase
{

    /** @test */
    public function it_add_lists_subjects_as_array_to_observer_and_execute()
    {
        $observer = new Observer();
        $observer->observe([$foo = new Foo(),$bar = new Bar()]);
        $observer->fire();
        $this->assertEquals(get_class($foo),$foo->thing);
        $this->assertEquals(get_class($bar),$bar->thing);
    }

    /** @test */
    public function it_add_lists_subjects_as_multiple_arg_to_observer_and_execute()
    {
        $observer = new Observer();
        $observer->observe($foo = new Foo(),$bar = new Bar());
        $observer->fire();
        $this->assertEquals(get_class($foo),$foo->thing);
        $this->assertEquals(get_class($bar),$bar->thing);
    }

}
