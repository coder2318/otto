<?php

namespace App\Data\User;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class Details extends Data
{
    /**
     * User first name
     */
    #[StringType, Max(255), Sometimes]
    public ?string $first_name;

    /**
     * User last name
     */
    #[StringType, Max(255), Sometimes]
    public ?string $last_name;

    /**
     * User birth date
     */
    #[WithCast(DateTimeInterfaceCast::class), Date, Sometimes]
    public ?Carbon $birth_date;

    /**
     * User motivation
     */
    #[StringType, Max(255), Sometimes]
    public ?string $motivation;

    /**
     * User writing style
     */
    #[StringType, Max(255), Sometimes]
    public ?string $writing_style;

    /**
     * User goals
     */
    #[StringType, Max(255), Sometimes]
    public ?string $goals;

    /**
     * User timeline
     */
    #[StringType, Max(255), Sometimes]
    public ?string $timeline;

    /**
     * User configured
     */
    public bool $configured = false;
}
