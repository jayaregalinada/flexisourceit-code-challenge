<?php

namespace App\Http\Resources;

use App\Entities\Customer;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function (Customer $customer) {
                return [
                    'id' => (int) $customer->getId(),
                    'full_name' => $customer->getFullName(),
                    'email' => $customer->getEmail(),
                    'country' => $customer->getCountry(),
                ];
            }),
        ];
    }
}
