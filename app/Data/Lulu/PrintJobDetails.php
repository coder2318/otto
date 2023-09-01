<?php

namespace App\Data\Lulu;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PrintJobDetails extends Data
{
    public string $contact_email;

    public array $costs;

    public string $date_created;

    public string $date_modified;

    public ?array $estimated_shipping_dates;

    public ?string $external_id;

    public int $id;

    #[DataCollectionOf(LineItem::class)]
    public DataCollection $line_items;

    public int $production_delay;

    public ?string $production_due_time;

    public ShippingAddress $shipping_address;

    public ShippingOption $shipping_level;

    public string $shipping_option_level;

    public array $status;
}
