<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Pedido;
use App\Services\NotificationService;
use Carbon\Carbon;

class PedidoController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth:api');
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pedido::with('solicitante');

        // Se não for admin, mostra apenas pedidos do usuário
        if (!$user->isAdmin()) {
            $query->where('solicitante_id', $user->id);
        }

        // Filtros
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        if ($request->has('destino') && $request->destino) {
            $query->byDestino($request->destino);
        }

        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $dataInicio = Carbon::parse($request->data_inicio);
            $dataFim = Carbon::parse($request->data_fim);
            $query->byDateRange($dataInicio, $dataFim);
        }

        if ($request->has('criacao_inicio') && $request->has('criacao_fim')) {
            $criacaoInicio = Carbon::parse($request->criacao_inicio);
            $criacaoFim = Carbon::parse($request->criacao_fim);
            $query->byCreationDateRange($criacaoInicio, $criacaoFim);
        }

        $pedidos = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $pedidos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destino' => 'required|string|max:255',
            'data_ida' => 'required|date|after:today',
            'data_volta' => 'required|date|after:data_ida',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $pedido = Pedido::create([
            'solicitante_id' => $user->id,
            'nome_solicitante' => $user->name,
            'destino' => $request->destino,
            'data_ida' => $request->data_ida,
            'data_volta' => $request->data_volta,
            'status' => Pedido::STATUS_SOLICITADO,
        ]);

        $pedido->load('solicitante');

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado com sucesso',
            'data' => $pedido,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();
        $pedido = Pedido::with('solicitante')->find($id);

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado',
            ], 404);
        }

        // Verificar se o usuário pode ver este pedido
        if (!$user->isAdmin() && $pedido->solicitante_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pedido,
        ]);
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Apenas administradores podem atualizar status.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aprovado,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $pedido = Pedido::with('solicitante')->find($id);

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado',
            ], 404);
        }

        $novoStatus = $request->status;

        // Verificar regra de negócio: só pode cancelar se não estiver aprovado
        if ($novoStatus === Pedido::STATUS_CANCELADO && $pedido->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível cancelar um pedido já aprovado',
            ], 422);
        }

        $statusAnterior = $pedido->status;
        $pedido->update(['status' => $novoStatus]);

        // Enviar notificação
        $this->notificationService->sendStatusUpdateNotification($pedido, $statusAnterior, $novoStatus);

        return response()->json([
            'success' => true,
            'message' => 'Status do pedido atualizado com sucesso',
            'data' => $pedido,
        ]);
    }

    /**
     * Get status options for pedidos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusOptions()
    {
        return response()->json([
            'success' => true,
            'data' => Pedido::getStatusOptions(),
        ]);
    }
}
