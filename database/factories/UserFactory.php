<?php

namespace Database\Factories;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'document_type_id' => fn () => DocumentType::factory()->create(),
            'document_number' => $this->faker->unique()->numerify('##############'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indica que o usuário será criado com um CPF.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCpf()
    {
        return $this->state(function () {
            return [
                'document_type_id' => DocumentType::CPF_ID,
                'document_number' => onlyNumbers(
                    $this->faker->unique()->cpf()
                ),
            ];
        });
    }

    /**
     * Indica que o usuário será criado com um CNPJ.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCnpj()
    {
        return $this->state(function () {
            return [
                'document_type_id' => DocumentType::CNPJ_ID,
                'document_number' => onlyNumbers(
                    $this->faker->unique()->cnpj()
                ),
            ];
        });
    }
}
