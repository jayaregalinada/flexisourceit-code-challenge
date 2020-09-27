<?php

namespace Services\Customer;

use App\Entities\AbstractCustomer;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Services\Customer\Contracts\ImporterContract;

class Importer implements ImporterContract
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $dispatcher;

    /**
     * @var \Services\Customer\Manager
     */
    protected $manager;

    public function __construct(
        Manager $manager,
        EntityManagerInterface $entityManager,
        Dispatcher $dispatcher = null
    ) {
        $this->manager = $manager;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public function getDispatcher() : ?Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @param \Illuminate\Contracts\Events\Dispatcher|null $dispatcher
     *
     * @return Importer
     */
    public function setDispatcher(Dispatcher $dispatcher) : Importer
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * @param \Services\Customer\Contracts\ToImportContract|string $contract
     * @param array                                                $options
     */
    public function import($contract, array $options = []) : void
    {
        $results = $this->manager->results($options);
        $results->each(function ($result, $index) use ($contract) {
            $this->entityManager->persist(
                $this->createOrUpdate($this->createImport($contract), $result)
            );
            $this->dispatchPersist($result, $index);
        });

        $this->entityManager->flush();
    }

    /**
     * @param \Services\Customer\Contracts\ToImportContract $contract
     * @param array|mixed                                   $result
     *
     * @return \App\Entities\AbstractCustomer
     */
    protected function createOrUpdate($contract, $result) : AbstractCustomer
    {
        $importClass = $contract->import($result);
        $entity = $this->findEntity($importClass);
        if ($entity->getId() !== null) {
            return $contract->import($result, $entity);
        }

        return $importClass;
    }

    /**
     * @param \App\Entities\AbstractCustomer $importClass
     *
     * @return \App\Entities\AbstractCustomer|object
     */
    protected function findEntity(AbstractCustomer $importClass) : AbstractCustomer
    {
        return $this->entityManager->getRepository(get_class($importClass))
                ->findOneBy(['email' => $importClass->getEmail()]) ?? $importClass;
    }

    /**
     * @param \Services\Customer\Contracts\ToImportContract|string $contract
     *
     * @return \Services\Customer\Contracts\ToImportContract
     */
    protected function createImport($contract)
    {
        return is_string($contract) ? new $contract : $contract;
    }

    protected function dispatchPersist($result, $index) : void
    {
        if ($this->dispatcher !== null) {
            $this->dispatcher->dispatch('customer.import', compact('result', 'index'));
        }
    }
}
