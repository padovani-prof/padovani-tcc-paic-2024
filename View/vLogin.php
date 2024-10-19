<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Portaria</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <header>
        <div>
            <h1>Universidade do Estado do Amazonas</h1>
        </div>

    </header>

    <section>
        <div>
            <h1>Bem Vindo</h1>
            <h2>Login</h2>
        </div>
        <p>{{mensagem}}</p>
        <form action="cLogin.php">
            E-mail:
            <input type="email" name="txtemail" id="txtemail"> <br> <br>
            Senha:
            <input type="password" name="txtsenha" id="txtsenha" >
            
        

        <div id="esqueceu_senha" >
            <p>
                <input type="button" value="esqueceu sua senha?" onclick="recuperar_senha()">
            </p>
        </div>
        
        <p>
            <input id="btconectar" type="submit" value="Conectar" onclick="conectar()">
        </p>
        </form>

    </section>

    <footer>
        <p>&copy;Todos os Direitos Reservados</p>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>