<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Atualização do Status do Pedido de Viagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-cancelled {
            color: #dc3545;
            font-weight: bold;
        }
        .details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Sistema de Gestão de Pedidos de Viagem</h2>
    </div>

    <div class="content">
        <h3>Olá, {{ $nome }}!</h3>
        
        <p>O status do seu pedido de viagem foi atualizado.</p>

        <div class="details">
            <p><strong>Pedido ID:</strong> #{{ $pedido_id }}</p>
            <p><strong>Destino:</strong> {{ $destino }}</p>
            <p><strong>Data de Ida:</strong> {{ $data_ida }}</p>
            <p><strong>Data de Volta:</strong> {{ $data_volta }}</p>
            <p><strong>Status Anterior:</strong> {{ $status_anterior }}</p>
            <p><strong>Novo Status:</strong> 
                <span class="{{ $novo_status === 'Aprovado' ? 'status-approved' : ($novo_status === 'Cancelado' ? 'status-cancelled' : '') }}">
                    {{ $novo_status }}
                </span>
            </p>
        </div>

        @if($novo_status === 'Aprovado')
            <p>🎉 <strong>Parabéns!</strong> Seu pedido de viagem foi aprovado. Você pode começar a planejar sua viagem!</p>
        @elseif($novo_status === 'Cancelado')
            <p>❌ Seu pedido de viagem foi cancelado. Entre em contato com o administrador para mais informações.</p>
        @endif

        <p>Para mais detalhes, acesse o sistema de gestão de pedidos.</p>
    </div>

    <div class="footer">
        <p>Esta é uma notificação automática do Sistema de Gestão de Pedidos de Viagem.</p>
        <p>Por favor, não responda a este email.</p>
    </div>
</body>
</html>
