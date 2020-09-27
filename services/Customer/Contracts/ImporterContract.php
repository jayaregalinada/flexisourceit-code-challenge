<?php

namespace Services\Customer\Contracts;

interface ImporterContract
{
    /**
     * @param \Services\Customer\Contracts\ToImportContract|string $contract
     * @param array                                                $options
     */
    public function import($contract, array $options = []) : void;
}
