<?php

namespace Customer;

use TestCase;
use App\Entities\Customer;
use Illuminate\Http\Response;
use Doctrine\ORM\Tools\ToolsException;

class CustomerTest extends TestCase
{
    public function testCustomersList()
    {
        $this->get('customers');
        $this->assertResponseOk();
    }

    public function testCustomersListNextPage()
    {
        $this->get('customers/?page=2');
        $this->assertResponseOk();
    }

    public function testCustomersListWithValidOrderQuery()
    {
        $this->get('customers/?order=ASC');
        $this->assertResponseOk();
        $this->get('customers/?order=DESC');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'country',
                ],
            ],
        ]);
    }

    public function testCustomersListWithInvalidOrderQuery()
    {
        $this->get('customers/?order=latest');
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->get('customers/?order=asc');
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->get('customers/?order=desc');
        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCustomerView()
    {
        $this->get('customers/1');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => ['full_name', 'email', 'username', 'gender', 'country', 'city', 'phone'],
        ]);
    }

    public function testCustomerNotFound()
    {
        $this->get('customers/9999999');
        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    protected function setUp() : void
    {
        parent::setUp();
        try {
            $this->artisan('doctrine:schema:create');
            entity(Customer::class, 30)->create();
        } catch (ToolsException $e) {

        }
        $this->beforeApplicationDestroyed(function () {
            $this->artisan('doctrine:schema:drop');
        });
    }

    protected function tearDown() : void
    {
        $this->artisan('doctrine:schema:drop', [
            '--force' => true,
        ]);
    }
}
