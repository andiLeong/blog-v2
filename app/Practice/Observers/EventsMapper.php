<?php

namespace App\Practice\Observers;


use App\Practice\Observers\Observer\NotifyAllSubscribers;
use App\Practice\Observers\Observer\PublishedToTwitter;
use App\Practice\Observers\Observer\SendEmailToAdmin;
use App\Practice\Observers\Subject\PostWasCreated;

class EventsMapper
{
    public static $mapper = [
        PostWasCreated::class => [
            NotifyAllSubscribers::class,
            PublishedToTwitter::class
        ]
    ];

    public static function registered(string $event): bool
    {
        return isset(self::$mapper[$event]);
    }

    public function event($name)
    {
        if (self::registered($name)) {
            return $name;
        }

        throw new \Exception('event is not registered ' . $name);
    }

    public function listeners($event)
    {
        if (self::registered($event)) {
            return self::$mapper[$event];
        }

        throw new \Exception('event is not registered ' . $event);
    }
}
