<?php

namespace App\Data\User;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
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
     * User full name
     */
    #[StringType, Max(255), Sometimes]
    public ?string $name;

    /**
     * User birth date
     */
    #[Date, Sometimes]
    public ?Carbon $birth_date;

    /**
     * User phone
     */
    #[StringType, Max(255), Sometimes]
    public ?string $phone;

    /**
     * User language
     */
    #[StringType, Max(255), Sometimes]
    public ?string $language;

    /**
     * User country
     */
    #[StringType, Max(255), Sometimes]
    public ?string $country;

    /**
     * User bio
     */
    #[StringType, Sometimes]
    public ?string $bio;

    /**
     * Quiz answers
     */
    #[Sometimes]
    public ?array $quiz;

    /**
     * Social URLs
     */
    #[Sometimes]
    public ?DetailsSocial $social;
}
