<?php

namespace App\Practice\Observers;


use App\Practice\Observers\Subject\Subject;

class Dispatcher
{

    public function dispatch(...$args)
    {
        $event = $args[0];

        if (is_object($event) && $event instanceof Subject) {
            $eventInstance = $event;
        }

        if (is_string($event)) {

            $event = $this->mapper()->event($event);
            unset($args[0]);
            $payload = $args;
            $eventInstance = new $event(...$payload);
        }

        return $this->callListeners($eventInstance);
    }

    public function buildlistener($listener,$event)
    {
        $instance = new $listener($event);
        if(!method_exists($instance,'handle') && !method_exists($instance,'__invoke')){
            throw new \Exception('handle or __invoke Method not exists');
        }

        $caller = [
            $instance,
            'handle'
        ];

        return $caller($event);
    }

    public function callListeners($event)
    {
        return array_map(
            fn($listener) => $this->buildlistener($listener,$event),
            $this->listeners($event)
        );
    }

    public function listeners($event): array
    {
        return $this->mapper()->listeners(
            is_object($event) ? get_class($event) : $event
        );
    }

    protected function mapper()
    {
        return new EventsMapper();
    }

}
