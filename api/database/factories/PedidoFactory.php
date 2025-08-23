<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataIda = Carbon::now()->addDays(rand(30, 180));
        $dataVolta = $dataIda->copy()->addDays(rand(1, 14));
        
        $destinos = [
            'São Paulo, SP',
            'Rio de Janeiro, RJ',
            'Belo Horizonte, MG',
            'Brasília, DF',
            'Salvador, BA',
            'Recife, PE',
            'Fortaleza, CE',
            'Curitiba, PR',
            'Porto Alegre, RS',
            'Manaus, AM',
        ];

        return [
            'solicitante_id' => User::factory(),
            'nome_solicitante' => fake()->name(),
            'destino' => fake()->randomElement($destinos),
            'data_ida' => $dataIda,
            'data_volta' => $dataVolta,
            'status' => fake()->randomElement(['solicitado', 'aprovado', 'cancelado']),
        ];
    }

    /**
     * Indicate that the pedido is solicitado.
     */
    public function solicitado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Pedido::STATUS_SOLICITADO,
        ]);
    }

    /**
     * Indicate that the pedido is aprovado.
     */
    public function aprovado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Pedido::STATUS_APROVADO,
        ]);
    }

    /**
     * Indicate that the pedido is cancelado.
     */
    public function cancelado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Pedido::STATUS_CANCELADO,
        ]);
    }
}
