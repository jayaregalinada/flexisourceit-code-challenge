<?php

namespace Customer;

use Mockery;
use TestCase;
use Faker\Factory;
use Faker\Generator;
use Cassandra\Custom;
use App\Entities\Customer;
use Services\Customer\Manager;
use Services\Customer\Importer;
use Illuminate\Support\Collection;
use App\Entities\AbstractCustomer;
use Doctrine\ORM\Tools\ToolsException;
use Services\Customer\Enums\GenderEnum;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Services\Customer\Contracts\ToImportContract;
use Services\Customer\Models\RandomUser\RandomUserModel;

class ImporterTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        try {
            $this->artisan('doctrine:schema:create');
        } catch (ToolsException $e) {

        }
        $this->beforeApplicationDestroyed(function () {
            $this->artisan('doctrine:schema:drop');
        });
    }

    protected function tearDown() : void
    {
        $this->artisan('doctrine:schema:drop', [
            '--force' => true
        ]);
    }

    public function testCreateCustomersFromEntity()
    {
        $entities = entity(Customer::class, 10)->make();
        $this->assertCount(10, $entities);
    }

    public function testImportedCustomerWithTheSameEmail()
    {
        entity(Customer::class, 10)->create();
        entity(Customer::class)->create([
            'email' => 'email@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $manager = Mockery::mock(Manager::class);
        $manager->shouldReceive('results')->andReturn(new Collection([
            new RandomUserModel([
                'email' => 'email@example.com',
                'name' => [
                    'first' => 'John',
                    'last' => 'McClane'
                ],
                'location' => [
                    'country' => 'Country',
                    'city' => 'City',
                ],
                'login' => [
                    'username' => 'username',
                    'md5' => md5('password')
                ],
                'phone' => '(02) 222-2222',
            ])
        ]));
        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')->andReturnNull();

        $importer = new Importer(
            $manager,
            $this->app->make(EntityManagerInterface::class),
            $dispatcher,
        );
        $importerClass = new class implements ToImportContract {
            public function import($row, AbstractCustomer $customer = null) : AbstractCustomer
            {
                $customer =  ($customer ?? new Customer())
                    ->setFirstName($row->getFirstName())
                    ->setLastName($row->getLastName())
                    ->setUsername($row->getUserName())
                    ->setGender($row->isFemale() ? GenderEnum::FEMALE() : GenderEnum::MALE())
                    ->setCountry($row->getCountry())
                    ->setCity($row->getCity())
                    ->setPhone($row->getAttribute('phone'))
                    ->setPassword($row->getPassword(RandomUserModel::PASSWORD_TYPE_MD5));
                if ($customer !== null) {
                    $customer->setEmail($row->getAttribute('email'));
                }

                return $customer;
            }
        };

        $importer->import($importerClass);

        $this->seeInDatabase('customers', [
            'email' => 'email@example.com',
            'first_name' => 'John',
            'last_name' => 'McClane',
        ]);
    }
}
