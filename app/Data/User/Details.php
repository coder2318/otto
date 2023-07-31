<?php

namespace App\Data\User;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

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
     * Quiz answers
     */
    #[DataCollectionOf(StringType::class)]
    public DataCollection $quiz;
}
