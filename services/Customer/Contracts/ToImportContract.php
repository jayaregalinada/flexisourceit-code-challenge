<?php

namespace Services\Customer\Contracts;

use App\Entities\AbstractCustomer;

interface ToImportContract
{
    /**
     * @param array|mixed                         $row
     *
     * @param \App\Entities\AbstractCustomer|null $customer
     *
     * @return \App\Entities\AbstractCustomer
     */
    public function import($row, AbstractCustomer $customer = null) : AbstractCustomer;
}
