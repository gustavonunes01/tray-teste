<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Vendas Diárias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        .sales-list {
            margin-top: 20px;
        }
        .sale-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Relatório de Vendas Diárias</h1>
            <p>Olá {{ $seller->name }},</p>
            <p>Aqui está seu relatório de vendas do dia {{ $date }}.</p>
        </div>

        <div class="summary">
            <div class="summary-item">
                <strong>Total de Vendas:</strong> {{ $totalSales }}
            </div>
            <div class="summary-item">
                <strong>Valor Total:</strong> R$ {{ number_format($totalValue, 2, ',', '.') }}
            </div>
            <div class="summary-item">
                <strong>Comissão Total:</strong> R$ {{ number_format($totalCommission, 2, ',', '.') }}
            </div>
        </div>

        @if(count($sales) > 0)
            <div class="sales-list">
                <h2>Detalhes das Vendas</h2>
                @foreach($sales as $sale)
                    <div class="sale-item">
                        <p><strong>Pedido:</strong> {{ $sale->name }}</p>
                        <p><strong>Valor:</strong> R$ {{ number_format($sale->price, 2, ',', '.') }}</p>
                        <p><strong>Comissão:</strong> R$ {{ number_format($sale->commission_value, 2, ',', '.') }}</p>
                        <p><strong>Data:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>Nenhuma venda realizada hoje.</p>
        @endif

        <div class="total">
            <p>Continue com o excelente trabalho!</p>
        </div>
    </div>
</body>
</html> 