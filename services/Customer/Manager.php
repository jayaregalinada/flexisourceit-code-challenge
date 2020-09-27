<?php

namespace Services\Customer;

use Closure;
use InvalidArgumentException;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use Services\Customer\Contracts\ClientContract;
use Services\Customer\Clients\RandomUserClient;
use Services\Customer\Repository as CustomerRepository;

/**
 * @mixin \Services\Customer\Repository
 */
class Manager
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * @var array
     */
    protected $customDrivers = [];

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var \Illuminate\Http\Client\Factory
     */
    protected $factory;

    /**
     * Manager constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Container\Container $app
     * @param \Illuminate\Config\Repository|null                                                     $config
     * @param \Illuminate\Http\Client\Factory|null                                                   $factory
     */
    public function __construct($app, Repository $config = null, Factory $factory = null)
    {
        $this->app = $app;
        $this->config = $config ?? $this->app['config'];
        $this->factory = $factory ?? $this->app[Factory::class];
    }

    public function drivers() : array
    {
        return $this->drivers;
    }

    public function setDefaultDriver($name) : void
    {
        $this->config->set("customer.importer_default_driver", $name);
    }

    public function extend($driver, Closure $callback) : Manager
    {
        $this->customDrivers[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->drivers[$name] = $this->get($name);
    }

    public function getDefaultDriver() : string
    {
        return $this->config->get("customer.importer_default_driver");
    }

    protected function get($name)
    {
        return $this->drivers[$name] ?? $this->resolve($name);
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if ($config === null) {
            throw new InvalidArgumentException("Customer Importer client [${name}] is not defined.");
        }

        if (isset($this->customDrivers[$config['driver']])) {
            return $this->callCustomDriver($config);
        }

        $method = 'create' . ucfirst($config['driver']) . 'Driver';

        if (method_exists($this, $method)) {
            return $this->{$method}($config);
        }

        throw new InvalidArgumentException("Driver [${config['driver']}] is not supported.");
    }

    protected function getConfig(string $name)
    {
        return $this->config->get("customer.importer_drivers.${name}");
    }

    protected function callCustomDriver(array $config)
    {
        return $this->customDrivers[$config['driver']]($this->app, $config);
    }

    protected function createDefaultDriver(array $config)
    {
        return $this->repository(new RandomUserClient(
            $this->factory->baseUrl($config['url']),
            $config
        ));
    }

    public function repository(ClientContract $client)
    {
        return tap(new CustomerRepository($client), function (CustomerRepository $repository) {
            $this->setEventDispatcher($repository);
        });
    }

    protected function setEventDispatcher(\Services\Customer\Repository $repository) : void
    {
        if (!$this->app->bound(Dispatcher::class)) {
            return;
        }

        $repository->setEventDispatcher($this->app->make(Dispatcher::class));
    }
}
