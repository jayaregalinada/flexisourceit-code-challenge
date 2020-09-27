<?php

namespace Services\Customer\Clients;

use Illuminate\Support\Collection;
use Illuminate\Http\Client\PendingRequest;
use Services\Customer\Contracts\ClientContract;
use Services\Customer\Models\RandomUser\RandomUserModel;

class RandomUserClient implements ClientContract
{
    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $request;

    /**
     * @var array
     */
    protected $config;

    public function __construct(PendingRequest $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function results(array $options = []) : Collection
    {
        $request = $this->request->get(
            $this->config['version'],
            $this->processQueries($options)
        );

        return (new Collection($request->json('results')))
            ->mapInto(RandomUserModel::class);
    }

    protected function processQueries(array $options = []) : array
    {
        $defaultOptions = [
            'nationalities' => implode(',', $options['nationalities'] ?? $this->config['nationalities']),
            'inc' => implode(',', $this->config['fields']),
            'results' => (int) ($options['count'] ?? $this->config['count']),
        ];

        return array_merge($defaultOptions, Collection::make($options)->except(array_keys($defaultOptions), 'count')->toArray());
    }
}
