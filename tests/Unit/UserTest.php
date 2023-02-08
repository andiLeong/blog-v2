<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_can_determine_user_is_admin()
    {
        Config::set('app.admin', explode(',', 'foo@gmail.com,anotherAdmin@gmail.com'));
        $user = new User(['email' => 'foo@gmail.com']);
        $user2 = new User(['email' => 'bar@gmail.com']);
        $user3 = new User(['email' => 'anotherAdmin@gmail.com']);
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user3->isAdmin());
        $this->assertFalse($user2->isAdmin());
    }
}
