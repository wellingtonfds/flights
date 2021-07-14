<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class FlightsResource extends JsonResource
{
    public static $wrap = 'flights';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            "groups" => [],
            "totalGroups" => 0,
            "totalFlights" => $this->resource['totalFlights'],
            "cheapestPrice" => 0,
            "cheapestGroup" => 0
        ];
        $finalGroup = [];

        $this->resource['group']->each(function ($group) use (&$finalGroup) {
            $finalGroup = array_merge($finalGroup, $group);
        });
        $finalGroup = (new Collection($finalGroup))->sortBy('total');
        $key = 0;
        $final = $finalGroup->map(function ($group) use (&$response, &$key) {
            $key++;
            if ($response['cheapestPrice'] === 0 || $group['total'] < $response['cheapestPrice']) {
                $response['cheapestPrice'] = $group['total'];
                $group['cheapestGroup'] = $key;
            }
            return [
                'uniqueId' => $key,
                'inbound' => $group['inbound'],
                'outbound' => $group['outbound'],
                'totalPrice' => $group['total']
            ];
        });
        $response['groups'] = $final->all();
        $response['totalGroups'] = count($final);

        return $response;
    }
}
