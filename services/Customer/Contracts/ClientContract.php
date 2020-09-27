<?php

namespace Services\Customer\Contracts;

use Illuminate\Support\Collection;

interface ClientContract
{
    public function results(array $options = []) : Collection;
}
