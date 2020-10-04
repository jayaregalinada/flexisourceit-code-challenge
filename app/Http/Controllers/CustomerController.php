<?php

namespace App\Http\Controllers;

use App\Entities\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Doctrine\ORM\EntityManagerInterface;
use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepository;
use Doctrine\Common\Collections\Criteria;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CustomerCollectionResource;

class CustomerController extends Controller
{
    /**
     * @param \Illuminate\Http\Request             $request
     * @param \Doctrine\ORM\EntityManagerInterface $em
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request, EntityManagerInterface $em) : JsonResource
    {
        $this->validate($request, [
            'order' => [
                Rule::in([
                    Criteria::DESC,
                    Criteria::ASC,
                ]),
            ],
            'limit' => [
                'integer',
            ],
            'page' => [
                'integer',
            ],
        ]);
        /** @var \App\Repositories\CustomerRepository $repository */
        $repository = $em->getRepository(Customer::class);

        return CustomerCollectionResource::collection(
            $repository->all(
            $order = $request->get('order', Criteria::DESC),
            $limit = (int) $request->get('limit', CustomerRepository::LIMIT),
            (int) $request->get('page', 1)
            )
            ->withPath(route('customer.index'))
            ->appends(compact('limit', 'order'))
        );
    }

    public function show(Customer $customer) : JsonResource
    {
        return new CustomerResource($customer);
    }
}
