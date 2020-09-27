<?php

namespace Services\Customer\Models\Import;

use App\Entities\Customer;
use App\Entities\AbstractCustomer;
use Services\Customer\Enums\GenderEnum;
use Services\Customer\Contracts\ToImportContract;
use Services\Customer\Models\RandomUser\RandomUserModel;

class CustomerImport implements ToImportContract
{
    /**
     * @param \Services\Customer\Models\RandomUser\RandomUserModel $row
     * @param \App\Entities\AbstractCustomer|null                  $customer
     *
     * @return Customer|AbstractCustomer
     */
    public function import($row, AbstractCustomer $customer = null) : AbstractCustomer
    {
        $customer = ($customer ?? new Customer())
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
}
