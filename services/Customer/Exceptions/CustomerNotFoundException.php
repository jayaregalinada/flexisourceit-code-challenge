<?php

namespace Services\Customer\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerNotFoundException extends NotFoundHttpException
{
    /**
     * @var int
     */
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        parent::__construct(__('customer::customer.not_found', compact('id')));
    }

    public function getCustomerId() : int
    {
        return $this->id;
    }
}
