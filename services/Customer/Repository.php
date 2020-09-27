<?php

namespace Services\Customer;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Events\Dispatcher;
use Services\Customer\Contracts\ClientContract;

class Repository
{
    /**
     * @var \Services\Customer\Contracts\ClientContract
     */
    protected $client;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $dispatcher;

    public function __construct(ClientContract $client, Dispatcher $dispatcher = null)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    public function setEventDispatcher(Dispatcher $dispatcher) : void
    {
        $this->dispatcher = $dispatcher;
    }

    public function getEventDispatcher() : ?Dispatcher
    {
        return $this->dispatcher;
    }

    public function results(array $options = []) : Collection
    {
        return $this->client->results($options);
    }

    public function getClient() : ClientContract
    {
        return $this->client;
    }
}
