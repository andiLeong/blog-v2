<?php

use App\Practice\Observers\Bar;
use App\Practice\Observers\Foo;
use App\Practice\Observers\UserHadLogIn;
use PHPUnit\Framework\TestCase;

class ObserverTest extends TestCase
{
    /** @test */
    public function it_add_lists_subjects_as_array_to_observer_and_execute()
    {
        $userHadLogIn = new UserHadLogIn();
        $userHadLogIn->observe([$foo = new Foo(),$bar = new Bar()]);
        $userHadLogIn->fire();
        $this->assertEquals(get_class($foo),$foo->thing);
        $this->assertEquals(get_class($bar),$bar->thing);
    }

    /** @test */
    public function it_add_lists_subjects_as_multiple_arg_to_observer_and_execute()
    {
        $userHadLogIn = new UserHadLogIn();
        $userHadLogIn->observe([$foo = new Foo(),$bar = new Bar()]);
        $userHadLogIn->fire();
        $this->assertEquals(get_class($foo),$foo->thing);
        $this->assertEquals(get_class($bar),$bar->thing);
    }

}
