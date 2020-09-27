<?php

namespace Customer;

use Mockery;
use TestCase;
use Illuminate\Events\Dispatcher;
use Services\Customer\Repository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;
use Services\Customer\Contracts\ClientContract;
use Services\Customer\Clients\RandomUserClient;

class RepositoryTest extends TestCase
{
    public function testGetCount()
    {
        $repo = $this->getRepository();
        $repo->getClient()
            ->shouldReceive('results')
            ->once()
            ->andReturn(new Collection());

        $this->assertInstanceOf(Collection::class, $repo->results());
    }

    protected function getRepository()
    {
        $dispatcher = new Dispatcher(Mockery::mock(Container::class));

        return new Repository(Mockery::mock(ClientContract::class), $dispatcher);
    }

    public function testGetClient()
    {
        $repo = $this->getRepository();
        $this->assertInstanceOf(ClientContract::class, $repo->getClient());
    }

    public function testRandomUserClient()
    {
        $repo = new Repository(Mockery::mock(RandomUserClient::class));
        $this->assertInstanceOf(ClientContract::class, $repo->getClient());
    }
}
