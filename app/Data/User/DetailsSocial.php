<?php

namespace App\Data\User;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class DetailsSocial extends Data
{
    #[StringType, Max(255), Nullable, Sometimes]
    public ?string $facebook;

    #[StringType, Max(255), Nullable, Sometimes]
    public ?string $telegram;

    #[StringType, Max(255), Nullable, Sometimes]
    public ?string $instagram;

    #[StringType, Max(255), Nullable, Sometimes]
    public ?string $linkedin;
}
