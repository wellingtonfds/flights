<?php

namespace App\Services\flights;

use Faker\Core\Number;
use Illuminate\Support\Collection;
use JsonSerializable;

interface FlightsServicesInterface
{
    public function get(): array;
    public function groupFlights(): Collection;
    public function groupByFareOutboundPrice(array $data): Collection;
    public function refineGroup(Collection $data);
}
