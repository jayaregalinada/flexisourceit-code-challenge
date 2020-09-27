<?php

namespace App\Http\Resources;

use Services\Customer\Enums\GenderEnum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Entities\Customer
 */
class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'full_name' => $this->getFullName(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'gender' => $this->getGender() === GenderEnum::FEMALE() ? 'female' : 'male',
            'country' => $this->getCountry(),
            'city' => $this->getCity(),
            'phone' => $this->getPhone(),
        ];
    }
}
