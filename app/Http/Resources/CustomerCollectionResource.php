<?php

namespace App\Http\Resources;

use App\Entities\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerCollectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->getId(),
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
            'country' => $this->getCountry(),
        ];
    }
}
