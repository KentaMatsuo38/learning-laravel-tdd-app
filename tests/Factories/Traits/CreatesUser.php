<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\Models\UserProfile;
use Illuminate\Support\Arr;

trait CreatesUser
{

    private function createUser(): User
    {
        $user = User::factory()->create();
        $user->profile()->save(UserProfile::factory()->make());

        return $user;
    }
}
