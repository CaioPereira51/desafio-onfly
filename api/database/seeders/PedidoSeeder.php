<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

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

        $statuses = ['solicitado', 'aprovado', 'cancelado'];

        foreach ($users as $user) {
            // Create 3-5 pedidos for each user
            $numPedidos = rand(3, 5);
            
            for ($i = 0; $i < $numPedidos; $i++) {
                $dataIda = Carbon::now()->addDays(rand(30, 180));
                $dataVolta = $dataIda->copy()->addDays(rand(1, 14));
                
                Pedido::create([
                    'solicitante_id' => $user->id,
                    'nome_solicitante' => $user->name,
                    'destino' => $destinos[array_rand($destinos)],
                    'data_ida' => $dataIda,
                    'data_volta' => $dataVolta,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}
