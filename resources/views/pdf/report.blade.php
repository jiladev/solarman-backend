<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estudo de Economia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 150px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #003366;
        }

        .highlight {
            color: #ff6600;
            font-weight: bold;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
        }

        .price-box {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .price-box div {
            background-color: #e6f7ff;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            width: 30%;
        }

        .price-box div h3 {
            color: #ff6600;
            font-size: 24px;
        }

        .price-box div p {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #003366;
            color: #fff;
        }

        .quote {
            background-color: #e6f7ff;
            border-left: 5px solid #ff6600;
            padding: 10px;
            margin: 20px 0;
            font-size: 16px;
        }

        .tableFooter {
            text-align: left;
            font-size: 9px;
            line-height: 19px;
            font-weight: 700;
        }

        .icons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .icons div {
            width: 45%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('img/logo-horiz-solarman-1.png') }}" alt="Solarman Logo">
            <h1>Estudo de <span class="highlight">Economia</span></h1>
        </div>
        <div class="content">
            <p>Prezado(a), Nome do Cliente</p>
            <p>É com grande satisfação que apresentamos a nossa proposta para o fornecimento de energia solar através de
                fontes solares, visando não apenas reduzir os seus custos com a tarifa da Copel, mas também contribuir
                para a sustentabilidade ambiental!</p>
        </div>
        <div class="price-box">
            <div>
                <p>Hoje você paga</p>
                <h3>R$200,00</h3>
            </div>
            <div>
                <p>Com a SolarMan você paga</p>
                <h3>R$180,00</h3>
            </div>
            <div>
                <p>Sua economia mensal</p>
                <h3>R$20,00</h3>
            </div>
            <div>
                <p>Sua economia anual</p>
                <h3>R$220,00</h3>
            </div>
        </div>
        <div>
            <p>
                A proposta de valor da <span class="highlight">SolarMan Cooperativa</span> é entregue através do tripé
                <span class="highlight">Energia Limpa</span>, Consumo Consciente e Interface Moderna.
            </p>
            <img src="{{public_path('img/tripe-1.png')}}" alt="">
        </div>
        <img src="{{public_path('img/sol-1.png')}}" alt="">
    </div>
    <div class="table-container">
        <table>
            <tr>
                <th>Bandeira</th>
                <th>Economia Mensal</th>
                <th>% de desconto</th>
            </tr>
            <tr>
                <td style="color: green;">Verde</td>
                <td>30,84</td>
                <td>13,55</td>
            </tr>
            <tr>
                <td style="color: orange;">Amarela</td>
                <td>30,84</td>
                <td>13,55</td>
            </tr>
            <tr>
                <td style="color: red;">Vermelha P1</td>
                <td>30,84</td>
                <td>13,55</td>
            </tr>
            <tr>
                <td style="color: darkred;">Vermelha P2</td>
                <td>30,84</td>
                <td>13,55</td>
            </tr>
            <tr>
                <td style="color: blue;">Es. Hídrica</td>
                <td>30,84</td>
                <td>13,55</td>
            </tr>
        </table>
        <p class="tableFooter">Nosso valor não aumenta com a bandeira tarifária.</p>
    </div>
    <div class="icons">
        <img src="{{public_path('img/modelo-1.png')}}" alt="">
    </div>
    </div>
</body>

</html>