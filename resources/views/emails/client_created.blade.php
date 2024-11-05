<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo orçamento gerado</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #000000;
        }

        .container{
            width: 75%;
            margin: 40px auto;
        }

        .header{
            margin: 40px 0;
        }

        .header h1{
            font-size: 23px;
            font-weight: 400;
            line-height: 29px;
            margin-top: 16px;
        }

        .line-orange{
            width: 60px;
            border: 4px solid #FF8A12;
            margin: 14px 0;
        }

        span{
            font-weight: 700;
        }

        .info{
            font-weight: 400;
            font-size: 12px;
            line-height: 15px;
        }

        p{
            width: 40%;
        }

        .little-letters{
            font-weight: 400;
            font-size: 8px;
            line-height: 10px;
            margin-top: 14px;
        }

        .body-email h4{
            font-size: 14px;
            line-height: 18px;
            font-weight: 400;
        }

        .body-email p{
            font-size: 23px;
            font-weight: 700;
            line-height: 29px;
            margin-bottom: 16px;
        }

        .footer{
            background-color: #0074A8;
            width: 100vw;
            height: 140px;
        }

        .footer img{
            margin-top: 40px;
            margin-left: 40px;
        }

        @media (max-width: 768px) {
            p{
                width: 95%;
            }
        }
    </style>

</head>

<body>
    <div class="container">
        <header class="header">
            <div class="img-header">
                <img src="{{ asset('img/logo-horiz-solarman-1.png') }}" alt="Logo Solarman">
            </div>
            <h1> Tem <span>cliente novo</span> chegando aí!</h1>
            <hr class="line-orange">
            <p class="info">
                Um cliente acabou de preencher o formulário na <span>Área do cliente</span>
                lá do site da Cooperativa. Corre aqui ver os dados desse cliente.
            </p>
            <p class="little-letters">Todos os dados foram enviados conforme consentimento do cliente.</p>
        </header>
        <div class="body-email">
            <h4>Nome do cliente</h4>
            <p>{{ $client->name }}</p>
            <h4>Celular do cliente</h4>
            <p>{{ $client->phone }}</p>
            <h4>Valor da fatura</h4>
            <p>R${{ $clientEstimate->fatura_copel }}</p>
            <h4>Valor final informado</h4>
            <p>R${{ $clientEstimate->final_value_discount }}</p>
        </div>
    </div>

    <footer class="footer">
        <img src="{{ asset('img/logo-horiz-solarman-branca-1.png') }}" alt="Logo footer">
    </footer>

</body>

</html>