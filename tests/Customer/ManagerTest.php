<?php

use Services\Customer\Manager;
use Illuminate\Http\Client\Factory;

class ManagerTest extends TestCase
{
    protected function tearDown() : void
    {
        Mockery::close();
    }

    public function testCustomDriver()
    {
        $manager = new Manager(
            $this->app,
            $this->app['config']->set('customer.importer_drivers.' . __CLASS__, [
                'driver' => __CLASS__
            ]),
            $this->app[Factory::class]->fake()
        );
        $manager->extend(__CLASS__, function () {
            return $this;
        });
        $this->assertEquals($manager, $manager->driver(__CLASS__));
    }

    public function testGetDrivers()
    {
        $manager = new Manager(
            $this->app,
            $this->app['config']->set('customer.importer_drivers.' . __CLASS__, [
                'driver' => __CLASS__
            ]),
            $this->app[Factory::class]->fake()
        );
        $manager->extend(__CLASS__, function () {
            return $this;
        });
        $manager->driver(__CLASS__);
        $this->assertArrayHasKey(__CLASS__, $manager->drivers());
    }

    public function testSetCustomDriverAsDefault()
    {
        $manager = new Manager(
            $this->app,
            $this->app['config']->set('customer.importer_drivers.' . __CLASS__, [
                'driver' => __CLASS__
            ]),
            $this->app[Factory::class]->fake()
        );
        $manager->extend(__CLASS__, function () {
            return $this;
        });
        $manager->setDefaultDriver(__CLASS__);
        $manager->driver(__CLASS__);
        $this->assertSame(__CLASS__, $manager->getDefaultDriver());
    }
}
