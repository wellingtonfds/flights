<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class FlightsResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="flights_resource",
     *     description="The default resource for an User",
     *     type="object",
     *     title="Flights",
     *     @OA\Property(property="groups", type="object",collectionFormat="multi", description="ID", example="1",
     *       
     *          @OA\Property(property="“flightGroup", type="object",
     *              @OA\Property(property="uniqueId", type="int64", description="ID", example="1"),
     *              @OA\Property(property="totalPrice", type="float", description="preço total", example="1"),
     *              @OA\Property(property="outbound", type="object", description="grupo"),
     *              @OA\Property(property="inbound", type="object", description="grupo"),
     *          ),
     *     ),
     *     @OA\Property(property="totalGroups", type="int", description="quantidade total de grupos"),
     *     @OA\Property(property="totalFlights", type="int", description="quantidade total de voos únicos"),
     *     @OA\Property(property="cheapestPrice", type="float", description="preço do grupo mais barato"),
     *     @OA\Property(property="cheapestGroup", type="float", description="id único do grupo mais barato"),
     * )
     */
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
            ++$key;
            if ($response['cheapestPrice'] === 0 || $group['total'] < $response['cheapestPrice']) {
                $response['cheapestPrice'] = $group['total'];
                $response['cheapestGroup'] = $key;
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
