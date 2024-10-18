<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('##########'), // 10 digit unique NIK
            'username' => substr($this->faker->unique()->userName, 0, 5),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Anda bisa menggantinya dengan password sesuai kebutuhan
            'jabatan' => $this->faker->jobTitle,
            'no_hp' => $this->faker->phoneNumber,
            'avatar' => $this->faker->imageUrl(100, 100, 'people', true, 'avatar'), // Gambar avatar acak
            'role' => 'user', // Set role sebagai user untuk data karyawan
            'department_id' => $this->faker->numberBetween(1, 2), // Asumsi Anda memiliki 5 departemen
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
