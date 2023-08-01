<?php

namespace App\Data\User;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
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
    #[Date, Sometimes]
    public ?Carbon $birth_date;

    /**
     * Quiz answers
     */
    #[ArrayType, Sometimes]
    public array $quiz;
}
