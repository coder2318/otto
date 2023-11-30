<?php

namespace App\Data\User;

use Carbon\Carbon;
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
    public ?string $first_name = null;

    /**
     * User last name
     */
    #[StringType, Max(255), Sometimes]
    public ?string $last_name = null;

    /**
     * User pronouns
     */
    #[StringType, Max(255), Sometimes]
    public ?string $pronouns = null;

    /**
     * User full name
     */
    #[StringType, Max(255), Sometimes]
    public ?string $name = null;

    /**
     * User birth date
     */
    #[Date, Sometimes]
    public ?Carbon $birth_date = null;

    /**
     * User phone
     */
    #[StringType, Max(255), Sometimes]
    public ?string $phone = null;

    /**
     * User language
     */
    #[StringType, Max(255), Sometimes]
    public ?string $language = null;

    /**
     * User country
     */
    #[StringType, Max(255), Sometimes]
    public ?string $country = null;

    /**
     * User bio
     */
    #[StringType, Sometimes]
    public ?string $bio = null;

    /**
     * Quiz answers
     */
    #[Sometimes]
    public ?array $quiz = null;

    /**
     * Social URLs
     */
    #[Sometimes]
    public ?DetailsSocial $social = null;
}
