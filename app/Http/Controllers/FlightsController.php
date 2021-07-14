<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightsResource;
use App\Services\flights\FlightsServicesInterface;

class FlightsController extends Controller
{
    /**
     * @OA\Info(title="Wallet API", version="0.1")
     */

    /**
     * @OA\Get(
     *    path="/api/flights",
     *    @OA\Response(response="200", description="An example resource"),
     *    @OA\Response(response="422", description="when the service unavailable"),
     *    @OA\Response(response="201", description="The user resource",
     *          @OA\JsonContent(type="object",ref="#/components/schemas/flights_resource")
     *     ),
     * )
     */
    /**
     * Return many groups of flights
     * @return FlightsResource
     */
    public function index(FlightsServicesInterface $flightsServices): FlightsResource
    {
        return new FlightsResource($flightsServices->groupFlights());
    }
}
