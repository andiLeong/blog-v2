<?php

use App\Models\Post;
use App\Models\User;
use App\Practice\Observers\Observer\NotifyAllSubscribers;
use App\Practice\Observers\Observer\RecordUserLoginDetails;
use App\Practice\Observers\Observer\SendEmailToAdmin;
use App\Practice\Observers\Subject\PostWasCreated;
use App\Practice\Observers\Subject\UserHadLogIn;
use PHPUnit\Framework\TestCase;

class ObserverTest extends TestCase
{
    private $userHadLogIn;
    private $user;

    protected function setUp() :void
    {
        parent::setUp();
        $this->user = new User();
        $this->userHadLogIn = new UserHadLogIn($this->user);
    }


    /** @test */
    public function it_add_lists_subjects_as_array_to_observer_and_execute()
    {
        $this->userHadLogIn
            ->add([$foo = new RecordUserLoginDetails(),$bar = new SendEmailToAdmin()])
            ->fire();

        $this->assertEquals(get_class($this->user),$foo->user);
        $this->assertEquals(get_class($this->user),$bar->user);

        $post = new Post(['body' => 'hi']);
        $postWasPublished = new PostWasCreated($post);
        $postWasPublished->add($notify = new NotifyAllSubscribers())->fire();
        $this->assertEquals(get_class($post),$notify->post);
    }

    /** @test */
    public function it_add_lists_subjects_as_multiple_arg_to_observer_and_execute()
    {
        $this->userHadLogIn
            ->add($foo = new RecordUserLoginDetails(),$bar = new SendEmailToAdmin())
            ->fire();

        $this->assertEquals(get_class($this->user),$foo->user);
        $this->assertEquals(get_class($this->user),$bar->user);
    }

    /** @test */
    public function it_throws_exception_if_the_value_try_to_pass_to_observer_is_not_subject()
    {
        $this->expectException(Exception::class);
        $this->userHadLogIn->add(['foo']);
    }
}
