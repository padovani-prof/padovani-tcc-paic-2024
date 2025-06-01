<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Esqueceu Senha</h1>

    <p id="{{resposta}}">{{msg}}</p>
    <form action="cEsquecelSenha.php">
        <label for="">E-mail</label>
        <input type="email" name="email" value="{{email}}">
        <input type="submit" name="mandar" value="Enviar">
    </form>
    




    <a href="cLogin.php">Voltar</a>


    
</body>
</html>