<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pedido()
    {
        $user = User::factory()->create();
        
        $pedido = Pedido::create([
            'solicitante_id' => $user->id,
            'nome_solicitante' => $user->name,
            'destino' => 'São Paulo, SP',
            'data_ida' => '2024-06-01',
            'data_volta' => '2024-06-05',
            'status' => Pedido::STATUS_SOLICITADO,
        ]);

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'destino' => 'São Paulo, SP',
            'status' => 'solicitado',
        ]);
    }

    public function test_pedido_belongs_to_solicitante()
    {
        $user = User::factory()->create();
        $pedido = Pedido::factory()->create(['solicitante_id' => $user->id]);

        $this->assertInstanceOf(User::class, $pedido->solicitante);
        $this->assertEquals($user->id, $pedido->solicitante->id);
    }

    public function test_can_filter_by_status()
    {
        $user = User::factory()->create();
        
        Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_SOLICITADO,
        ]);
        
        Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_APROVADO,
        ]);

        $solicitados = Pedido::byStatus(Pedido::STATUS_SOLICITADO)->get();
        $aprovados = Pedido::byStatus(Pedido::STATUS_APROVADO)->get();

        $this->assertEquals(1, $solicitados->count());
        $this->assertEquals(1, $aprovados->count());
    }

    public function test_can_filter_by_destino()
    {
        $user = User::factory()->create();
        
        Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'destino' => 'São Paulo, SP',
        ]);
        
        Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'destino' => 'Rio de Janeiro, RJ',
        ]);

        $saoPaulo = Pedido::byDestino('São Paulo')->get();
        $rioJaneiro = Pedido::byDestino('Rio de Janeiro')->get();

        $this->assertEquals(1, $saoPaulo->count());
        $this->assertEquals(1, $rioJaneiro->count());
    }

    public function test_can_be_cancelled_when_not_approved()
    {
        $user = User::factory()->create();
        $pedido = Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_SOLICITADO,
        ]);

        $this->assertTrue($pedido->canBeCancelled());
    }

    public function test_cannot_be_cancelled_when_approved()
    {
        $user = User::factory()->create();
        $pedido = Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_APROVADO,
        ]);

        $this->assertFalse($pedido->canBeCancelled());
    }

    public function test_status_checks()
    {
        $user = User::factory()->create();
        
        $solicitado = Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_SOLICITADO,
        ]);
        
        $aprovado = Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_APROVADO,
        ]);
        
        $cancelado = Pedido::factory()->create([
            'solicitante_id' => $user->id,
            'status' => Pedido::STATUS_CANCELADO,
        ]);

        $this->assertFalse($solicitado->isApproved());
        $this->assertTrue($aprovado->isApproved());
        $this->assertFalse($cancelado->isApproved());

        $this->assertFalse($solicitado->isCancelled());
        $this->assertFalse($aprovado->isCancelled());
        $this->assertTrue($cancelado->isCancelled());
    }

    public function test_get_status_options()
    {
        $options = Pedido::getStatusOptions();

        $this->assertArrayHasKey('solicitado', $options);
        $this->assertArrayHasKey('aprovado', $options);
        $this->assertArrayHasKey('cancelado', $options);
        
        $this->assertEquals('Solicitado', $options['solicitado']);
        $this->assertEquals('Aprovado', $options['aprovado']);
        $this->assertEquals('Cancelado', $options['cancelado']);
    }
}
