<?php

namespace App\Data\Lulu;

use Spatie\LaravelData\Data;

class LineItem extends Data
{
    public ?string $external_id;

    public ?int $page_count;

    public ?string $pod_package_id;

    public int $quantity = 1;

    public ?PrintableNormalization $printable_normalization;

    public ?string $title;

    public function toArray(): array
    {
        return collect(parent::toArray())->filter()->all();
    }
}
