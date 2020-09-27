<?php

namespace Services\Customer;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Services\Customer\Commands\ImportCommand;
use Services\Customer\Contracts\ImporterContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use const DIRECTORY_SEPARATOR;

class CustomerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    private const LANGUAGE_NAMESPACE = 'customer';

    public function register() : void
    {
        $this->app->configure('customer');
        $this->app->singleton('customer.manager', function ($app) {
            return new Manager($app, $app->make('config'), $app->make(Factory::class));
        });
        $this->app->singleton('customer.drivers', function ($app) {
            return $app->make('customer.manager')->drivers();
        });
        $this->app->bind(ImporterContract::class, function ($app) {
            return new Importer(
                $app->make('customer.manager'),
                $app->make(EntityManagerInterface::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'Languages', self::LANGUAGE_NAMESPACE);
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'Languages' => resource_path('lang/vendor/' . self::LANGUAGE_NAMESPACE)
        ]);
        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportCommand::class,
            ]);
        }
    }

    public function provides() : array
    {
        return [
            'customer.manager',
            'customer.drivers',
        ];
    }
}
