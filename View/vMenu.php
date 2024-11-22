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
</header>

    <div id="area-principal">
        <div>
            <h2>{{saudacao}}</h2>
        </div>

        <div id="menu-principal">
            <h2>Operações</h2>
            <ul>
                <li><a href="cPerfilUsuario.php">Perfil de Usuário</a></li>
                <li><a href="cUsuario.php">Usuários</a></li>
                <li><a href="cRecursos.php">Recursos</a></li>
                <li><a href="cCategoria.php">Categoria do Recurso</a></li>
                <li><a href="">Registro de retirada / Devolução</a></li>
                <li><a href="">Cancelar Retirada / Devolução</a></li>
                <li><a href="cResultadoDisponibilidade.php">Consultar Disponibilidade</a></li>
                <li><a href="cReservas.php">Reserva Recurso</a></li>
                <li><a href="cEnsalamento.php">Ensalamento</a></li>
                <li><a href="cPeriodo.php">Cadastrar Período</a></li>
                <li><a href="cDisciplina.php">Cadastrar Disciplina</a></li>



            </ul>
        </div>


        
        <div>
            <h2>Área do Usuário</h2>
            <ul>
                <li><a href="#">Editar Perfil</a></li>
                <li><a href="cLogin.php?desconectar=1">Sair</a></li>
            </ul>
        </div>

    </div>

    
    
    <script src="script.js"></script>
</body>
</html>
