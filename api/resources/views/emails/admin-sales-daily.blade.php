<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Vendas Diárias - Administrativo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
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
        .sellers-list {
            margin-top: 20px;
        }
        .seller-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .seller-header {
            background-color: #e9ecef;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-radius: 5px 5px 0 0;
        }
        .sales-list {
            margin-top: 10px;
            padding-left: 20px;
        }
        .sale-item {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Relatório de Vendas Diárias - Administrativo</h1>
            <p>Data: {{ $date }}</p>
        </div>

        <div class="summary">
            <div class="summary-item">
                <strong>Total de Vendedores Ativos:</strong> {{ $totalSellers }}
            </div>
            <div class="summary-item">
                <strong>Total de Vendas:</strong> {{ $totalSales }}
            </div>
            <div class="summary-item">
                <strong>Valor Total de Vendas:</strong> R$ {{ number_format($totalValue, 2, ',', '.') }}
            </div>
            <div class="summary-item">
                <strong>Total de Comissões:</strong> R$ {{ number_format($totalCommission, 2, ',', '.') }}
            </div>
        </div>

        @if(count($sellersData) > 0)
            <div class="sellers-list">
                <h2>Desempenho por Vendedor</h2>
                @foreach($sellersData as $sellerData)
                    <div class="seller-item">
                        <div class="seller-header">
                            <h3>{{ $sellerData['seller']->name }}</h3>
                        </div>
                        <div class="summary-item">
                            <strong>Total de Vendas:</strong> {{ $sellerData['totalSales'] }}
                        </div>
                        <div class="summary-item">
                            <strong>Valor Total:</strong> R$ {{ number_format($sellerData['totalValue'], 2, ',', '.') }}
                        </div>
                        <div class="summary-item">
                            <strong>Comissão Total:</strong> R$ {{ number_format($sellerData['totalCommission'], 2, ',', '.') }}
                        </div>

                        @if(count($sellerData['sales']) > 0)
                            <div class="sales-list">
                                <h4>Detalhes das Vendas</h4>
                                @foreach($sellerData['sales'] as $sale)
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
                    </div>
                @endforeach
            </div>
        @else
            <p>Nenhuma venda realizada hoje.</p>
        @endif

        <div class="total">
            <p>Relatório gerado automaticamente pelo sistema.</p>
        </div>
    </div>
</body>
</html> 