<?php

namespace Tests;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use JMac\Testing\Traits\AdditionalAssertions;
use Laravel\Cashier\Subscription;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions, CreatesApplication;

    protected function createUser(bool $configured = false, bool $admin = false, bool $subscribed = false): User|Authenticatable
    {
        $user = \App\Models\User::factory()->makeOne();

        if ($configured) {
            $user->details = [
                'first_name' => $fn = fake()->firstName(),
                'last_name' => $ln = fake()->lastName(),
                'name' => $fn.' '.$ln,
                'birth_date' => Carbon::createFromFormat('Y-m-d', fake()->date()),
                'phone' => fake()->phoneNumber(),
                'language' => fake()->languageCode(),
                'country' => fake()->countryCode(),
                'bio' => fake()->text(),
                'quiz' => [],
            ];
        }

        $user->save();

        if ($admin) {
            $user->assignRole('super-admin');
        }

        if ($subscribed) {
            Subscription::factory()->create([
                'user_id' => $user->id,
                'stripe_id' => Str::random(12),
            ]);
        }

        /** @var Authenticatable $user */
        return $user;
    }

    public function createGuestUser(bool $configured = false): Guest|Authenticatable
    {
        $user = \App\Models\Guest::factory()->makeOne();

        if ($configured) {
            $user->details = [
                'relation' => fake()->name(),
            ];
        }

        $user->save();

        /** @var Authenticatable $user */
        return $user;
    }
}
