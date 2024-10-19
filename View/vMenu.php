<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header>
    Seja Bem Vindo 
</header>

    <div id="cabecalho-menu">
        <div>
            imagem1
        </div>
        <div>
            imagem2
        </div>
        <div>
            Imagem3
        </div>
    </div>
    <div id="area-principal">
        <div id="menu-principal">
            <ul>
                <li><a href="cPerfilUsuario.php">Perfil de Usuário</a></li>
                <li><a href="cUsuario.php">Usuários</a></li>
                <li><a href="cRecursos.php">Recursos</a></li>
                <li><a href="">Categoria do Recurso</a></li>
                <li><a href="">Registro de retirada / Devolução</a></li>
                <li><a href="">Cancelar Retirada / Devolução</a></li>
                <li><a href="">Consultar Disponibilidade</a></li>
                <li><a href="">Reserva Recurso</a></li>
                <li><a href="">Ensalamento</a></li>
                <li><a href="">Cadastrar Período</a></li>
                <li><a href="">Cadastrar Disciplina</a></li>


            </ul>
        </div>


        <div>
            <h2>{{saudacao}}</h2>
        </div>

        <div>
            <ul>
                <li><a href="#">Editar Perfil</a></li>
                <li><a href="cLogin.php?desconectar=1">Sair</a></li>
            </ul>
        </div>

    </div>

    
    
    <script src="script.js"></script>
</body>
</html>