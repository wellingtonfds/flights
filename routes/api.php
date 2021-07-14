<?php

use App\Http\Controllers\FlightsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('flights', [FlightsController::class, 'index'])->name('flights.index');

Route::get('/test', function (Request $request) {
    $response = Http::get('http://prova.123milhas.net/api/flights');
    $flights = new Collection($response->json());
    $groupFare = $flights->groupBy(['fare', 'outbound', 'price']);
    $groupFlights = $groupFare->map(function ($outbounds) {
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
                    'outbound' => $outbound
                ];
            }
        }
        return $groups;
    });
    dd($groupFlights);



    // $groupFlights->;


});
