<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'solicitante_id',
        'nome_solicitante',
        'destino',
        'data_ida',
        'data_volta',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_ida' => 'date',
        'data_volta' => 'date',
    ];

    /**
     * Status constants
     */
    const STATUS_SOLICITADO = 'solicitado';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_CANCELADO = 'cancelado';

    /**
     * Get the solicitante that owns the pedido.
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by destination
     */
    public function scopeByDestino($query, $destino)
    {
        return $query->where('destino', 'like', "%{$destino}%");
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_ida', [$dataInicio, $dataFim])
                    ->orWhereBetween('data_volta', [$dataInicio, $dataFim]);
    }

    /**
     * Scope to filter by creation date range
     */
    public function scopeByCreationDateRange($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('created_at', [$dataInicio, $dataFim]);
    }

    /**
     * Check if pedido can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status !== self::STATUS_APROVADO;
    }

    /**
     * Check if pedido is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APROVADO;
    }

    /**
     * Check if pedido is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELADO;
    }

    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_SOLICITADO => 'Solicitado',
            self::STATUS_APROVADO => 'Aprovado',
            self::STATUS_CANCELADO => 'Cancelado',
        ];
    }
}
