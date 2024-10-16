<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ServiceDataBulkCreating extends Data
{
    public function __construct(
        public int $towerId,
        public array $area,
        public Carbon $startDate,
        public Carbon $endDate,
        public float $amount,
        public int $createdBy,
        public ?string $note = null,
    )
    {}
}
