<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightsResource;
use App\Services\flights\FlightsServicesInterface;

class FlightsController extends Controller
{
    /**
     * Return many groups of flights
     * @return FlightsResource
     */
    public function index(FlightsServicesInterface $flightsServices): FlightsResource
    {
        return new FlightsResource($flightsServices->groupFlights());
    }
}
