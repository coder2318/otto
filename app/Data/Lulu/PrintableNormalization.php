<?php

namespace App\Data\Lulu;

use Spatie\LaravelData\Data;

class PrintableNormalization extends Data
{
    public array $cover;

    public array $interior;

    public string $pod_package_id;
}
