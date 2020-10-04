<?php

namespace Services\Customer\Middleware;

use Closure;
use App\Entities\Customer;
use Illuminate\Support\Arr;
use Doctrine\ORM\EntityManagerInterface;
use Services\Customer\Exceptions\CustomerNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerBindingMiddleware
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Laravel\Lumen\Http\Request|mixed $request
     * @param \Closure                          $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->route('customer') !== null) {
            $customer = $this->findCustomer($request->route('customer'));
            $resolver = $request->getRouteResolver();
            $request->setRouteResolver(function () use ($customer, $resolver) {
                $route = $resolver();
                Arr::set($route[2], 'customer', $customer);

                return $route;
            });
        }

        return $next($request);
    }

    protected function findCustomer(int $id) : ?Customer
    {
        /** @var Customer|null $find */
        if (($find = $this->entityManager->find(Customer::class, $id)) !== null) {
            return $find;
        }

        throw new CustomerNotFoundException($id);
    }
}
