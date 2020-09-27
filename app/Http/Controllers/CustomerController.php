<?php

namespace App\Http\Controllers;

use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CustomerCollectionResource;

class CustomerController extends Controller
{
    public function index(EntityManagerInterface $em) : JsonResource
    {
        $repository = $em->getRepository(Customer::class);

        return new CustomerCollectionResource($repository->findAll());
    }

    public function show(Customer $customer) : JsonResource
    {
        return new CustomerResource($customer);
    }
}
