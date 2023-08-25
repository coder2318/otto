<?php

namespace App\Data\Lulu;

use Spatie\LaravelData\Data;

class LineItem extends Data
{
    public int $page_count;
    public string $pod_package_id;
    public int $quantity;
}
