<?php

namespace App\Services\flights;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Exceptions\HttpResponseException;


class FlightsServices implements FlightsServicesInterface
{
    /**
     * Get data from api
     * @return array
     */
    public function get(): array
    {
        $response = Http::get(env('API_FLIGHTS'));
        if ($response->failed()) {
            throw new HttpResponseException(new Response("Error Processing Request {$response->status()}", 422), 1);
        }
        return $response->json();
    }
    /**
     * main method group return group of flights
     * @return Collection
     */
    public function groupFlights(): Collection
    {
        return $this->refineGroup($this->groupByFareOutboundPrice($this->get()));
    }
    /**
     * Only group data by fare outbound and price
     * @return Collection
     */
    public function groupByFareOutboundPrice(array $data): Collection
    {
        return (new Collection($data))->groupBy(['fare', 'outbound', 'price']);
    }
    /**
     * Refine group based on this rules:
     * * same price and flight
     * * inbound and outbound
     * * total price off group
     */
    public function refineGroup(Collection $data)
    {
        return $data->map(function ($outbounds) {
            $newGroup = [];

            foreach ($outbounds as $key => $outbound) {
                foreach ($outbound as $price) {
                    if ($key) {
                        $newGroup['outbound'][] = $price;
                        continue;
                    }
                    $newGroup['inbound'][] = $price;
                }
            }
            $groups = [];
            foreach ($newGroup['inbound'] as $inbound) {
                foreach ($newGroup['outbound'] as $outbound) {
                    $groups[] = [
                        'inbound' => $inbound,
                        'outbound' => $outbound,
                        'total' => Arr::first($inbound)['price'] + Arr::first($outbound)['price']
                    ];
                }
            }
            return $groups;
        });
    }
}
