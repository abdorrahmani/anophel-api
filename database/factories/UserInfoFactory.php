<?php

namespace Database\Factories;

use App\Models\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserInfoFactory extends Factory
{
    protected $model = UserInfo::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'avatar' => $this->faker->imageUrl(100,100),
            'address' => $this->faker->address(),
            'birthday' => $this->faker->dateTime(),
            'tel' => $this->faker->phoneNumber(),
            'tel_verified' => $this->faker->boolean(),
            'bio' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
