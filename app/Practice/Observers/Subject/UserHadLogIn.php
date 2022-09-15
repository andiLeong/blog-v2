<?php

namespace App\Practice\Observers\Subject;


use App\Models\User;

class UserHadLogIn implements Subject
{
    use Subjectable;

    public function __construct(public User $user)
    {
        //
    }
}
