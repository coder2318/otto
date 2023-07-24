<?php

namespace App\Data\User;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class Details extends Data
{
    public string $first_name;

    public string $last_name;

    #[WithCast(DateTimeInterfaceCast::class)]
    public Carbon $birth_date;

    public string $motivation;

    public string $writing_style;

    public string $goals;

    public string $timeline;
}
