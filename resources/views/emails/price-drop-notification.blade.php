<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }

            .header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 30px;
                text-align: center;
                border-radius: 10px 10px 0 0;
            }

            .content {
                background: #f8f9fa;
                padding: 30px;
                border-radius: 0 0 10px 10px;
            }

            .price-box {
                background: white;
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .old-price {
                text-decoration: line-through;
                color: #999;
                font-size: 20px;
            }
            
            .new-price {
                color: #28a745;
                font-size: 32px;
                font-weight: bold;
            }
            
            .savings {
                background: #28a745;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                display: inline-block;
                margin-top: 10px;
            }

            .button {
                display: inline-block;
                background: #667eea;
                color: white;
                padding: 15px 30px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
                font-weight: bold;
            }

            .vendor {
                color: #764ba2;
                font-weight: bold;
            }

            .component-name {
                font-size: 24px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Bajada de precio en el componente: {{ $component->name }}</h1>
        </div>

        <div class="content">
            <div class="component-name">
                <strong>{{ $component->name }}</strong>
            </div>

            <div class="price-box">
                <p>Tienda: <span class="vendor">{{ ucfirst($vendor) }}</</span></p>

                <div style="margin: 20px 0;">
                    <div class="old-price">{{ number_format($oldPrice, 2) }}€</div>
                    <div class="new-price">{{ number_format($newPrice, 2) }}€</div>
                </div>

                <div class="savings">
                    Ahorras {{ number_format($priceDrop, 2) }}€ ({{ number_format($percentageDrop, 2) }}%)
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ $componentUrl }}" class="button">Ver componente</a>
            </p>

            <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

            <p style="font-size: 12px; color: #999; text-align: center;">
                Este mensaje fue enviado automáticamente. Si desea no recibir más notificaciones, puedes desactivarlas desde tu perfil.
            </p>
        </div>
    </body>
</html>
