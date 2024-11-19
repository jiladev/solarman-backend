<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estudo de Economia</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            text-align: right;
        }

        .header {
            text-align: right;
        }

        .header img {
            width: 150px;
        }

        .header h1 {
            font-size: 24px;
            color: #000;
            font-weight: 400;
        }

        .line-orange {
            padding: 3px;
            background-color: #ff6600;
            width: 54px;
            margin-left: auto;
            border: none;
        }

        .highlight {
            color: #000000;
            font-weight: 700;
        }

        .content {
            margin: 24px 0;
        }

        .content p {
            font-size: 14px;
            line-height: 15px;
        }

        .solar-values {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .sol-1 {
            position: relative;
            top: -70px;
            left: -50px;
        }

        .price-box {
            display: flex;
            width: 40%;
            flex-direction: column;
        }

        .price-box div {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0;
        }

        .price-box div p {
            width: 50%;
            margin-right: 24px;
        }

        .price-box h3 {
            padding: 4px 19px;
            border-radius: 24px;
            background-color: orange;
            color: #fff;
        }

        .tripe-box {
            display: flex;
            width: 50%;
        }

        .tripe-box p {
            font-size: 12px;
            width: 150px;
            height: 70px;
            margin-left: auto;
        }

        .tripe-1 {
            position: relative;
            margin-left: auto;
            margin-bottom: auto;
            left: -35px;
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
            border: none;
            padding: 8px;
            text-align: center;
            font-weight: 700;
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
            justify-content: center;
            align-items: center;
        }

        .ger-compartilhada-img {
            position: relative;
            margin: 50px 0;
            left: -100px;
        }

        .discount-value {
            background-color: #FF8A12;
            color: #fff;
            border-radius: 8px;
            padding: 4px 19px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('img/logo-horiz-solarman-1.png') }}" alt="Solarman Logo">
            <h1>Estudo de <span class="highlight">Economia</span></h1>
            <hr class="line-orange">
        </div>
        <div class="content">
            <p>Prezado(a), <span class="highlight">{{$clientName}}</span></p>
            <p>
                É com grande satisfação que apresentamos a nossa proposta para o fornecimento <br>de energia solar
                através de
                fontes solares, visando não apenas reduzir os seus <br>custos com a tarifa da Copel, mas também
                contribuir
                para a sustentabilidade <br>ambiental!
            </p>
        </div>
        <div class="solar-values">
            <div class="price-box">
                <div>
                    <p>Hoje você paga</p>
                    <h3 style="background-color: #0074A8">R${{$currentValue}}</h3>
                </div>
                <div>
                    <p>Com a SolarMan você paga</p>
                    <h3>R${{$valueCoop}}</h3>
                </div>
                <div>
                    <p>Sua economia mensal</p>
                    <h3 style="background-color: #0074A8">R${{$econMensal}}</h3>
                </div>
                <div>
                    <p>Sua economia anual</p>
                    <h3>R${{$econAnual}}</h3>
                </div>
            </div>
            <div class="tripe-box">
                <p>
                    A proposta de valor da <span class="highlight">SolarMan Cooperativa</span> é entregue através do
                    tripé
                    <span class="highlight">Energia Limpa</span>, Consumo Consciente e Interface Moderna.
                </p>
                <img class="tripe-1" src="{{public_path('img/tripe-1.svg')}}" alt="">
            </div>
        </div>
        <img class="sol-1" src="{{public_path('img/sol-1.svg')}}" alt="">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 8px; border: none">Bandeira</th>
                        <th>Economia Mensal</th>
                        <th style="border-top-right-radius: 8px; border: none">% de desconto</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td style="color: #008600;">Verde</td>
                        <td>R${{$verdeDesconto}}</td>
                        <td><span class="discount-value">13,55604396</span></td>
                    </tr>
                    <tr>
                        <td style="color: #FF8A12;">Amarela</td>
                        <td>R${{$amarelaDesconto}}</td>
                        <td><span class="discount-value">14,55604396</span></td>
                    </tr>
                    <tr>
                        <td style="color: #FF0000;">Vermelha P1</td>
                        <td>R${{$vermelhaP1Desconto}}</td>
                        <td><span class="discount-value">15,55604396</span></td>
                    </tr>
                    <tr>
                        <td style="color: #FF0000;">Vermelha P2</td>
                        <td>R${{$vermelhaP2Desconto}}</td>
                        <td><span class="discount-value">15,55604396</span></td>
                    </tr>
                    <tr>
                        <td style="color: #333333;">Es. Hídrica</td>
                        <td>R${{$escassezDesconto}}</td>
                        <td><span class="discount-value">16,55604396</span></td>
                    </tr>
                </tbody>
            </table>
            <p class="tableFooter">Nosso valor não aumenta com a bandeira tarifária.</p>
        </div>
        <div class="icons">
            <img class="ger-compartilhada-img" src="{{public_path('img/modelo-1.svg')}}" alt="">
        </div>
    </div>
</body>

</html>