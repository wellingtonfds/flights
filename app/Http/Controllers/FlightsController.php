<?php

namespace App\Http\Controllers;

use App\Services\flights\FlightsServicesInterface;

class FlightsController extends Controller
{
    public function index(FlightsServicesInterface $flightsServices)
    {
        return $flightsServices->groupFlights();
    }
}
