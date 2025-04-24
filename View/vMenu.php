<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Responsivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* Estilo do cabeçalho */
        header {
            background-color: #0059b3;
            color: #ffffff;
        }

        /* Estilo do menu lateral */
        #menu-lateral {
            background-color: #1E88E5;
            color: #ffffff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        #menu-lateral h5 {
            color: #BBDEFB;
        }

        #menu-lateral a {
            color: #E3F2FD;
            text-decoration: none;
            display: block;
            padding: 8px 10px;
            border-radius: 5px;
        }

        #menu-lateral a:hover {
            color: #ffffff;
            background-color: #0D47A1;
            padding-left: 12px;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .navbar {
            background-color: #1565C0;
        }

        .navbar .dropdown-menu {
            background-color: #E3F2FD;
        }

        .navbar .dropdown-item {
            color: #0D47A1;
        }

        .navbar .dropdown-item:hover {
            background-color: #90CAF9;
            color: #0D47A1;
        }
    </style>
</head>

<body class="bg-light">
    
    {{cabecario}}

    <!-- Layout com Grid -->
    <div class="container-fluid">
        <div class="row">

            <!-- Botão para abrir o menu lateral no mobile -->
            <button class="btn btn-primary d-md-none mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#menuLateralCollapse">
                ☰ Menu
            </button>

            <!-- Menu Lateral -->
            <div class="collapse d-md-block col-md-3 col-lg-2 p-3" id="menuLateralCollapse">
                <nav id="menu-lateral">
                    <h5 class="mb-4">Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="cPerfilUsuario.php">Perfis de Usuários</a></li>
                        <li><a href="cUsuario.php">Usuários</a></li>
                        <li><a href="cCategoria.php">Categoria</a></li>
                        <li><a href="cRecursos.php">Recursos</a></li>
                        <li><a href="cFormularioRetirada.php">Retiradas</a></li>
                        <li><a href="cFormularioDevolucao.php">Devoluções</a></li>
                        <li><a href="cFiltroDisponibildade.php">Disponibilidades</a></li>
                        <li><a href="cReservas.php">Reservas</a></li>
                        <li><a href="cEnsalamento.php">Ensalamentos</a></li>
                        <li><a href="cPeriodo.php">Períodos</a></li>
                        <li><a href="cDisciplina.php">Disciplinas</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Conteúdo Principal -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-md navbar-dark mb-3">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Área do Usuário</a>
                        <!-- Botão Hamburguer -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAreaUsuario">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <!-- Links -->
                        <div class="collapse navbar-collapse" id="navbarAreaUsuario">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <!-- Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdownUsuario" role="button" data-bs-toggle="dropdown">
                                        Opções do Usuário
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#">Editar Perfil</a></li>
                                        <li><a class="dropdown-item" href="#">Alterar Senha</a></li>
                                        <li><a class="dropdown-item" href="#">Configurações</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="cLogin.php?desconectar=1">Sair</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Conteúdo Dinâmico -->
                <h2>Seja Bem-Vindo</h2>
                <p>Esta é a sua área principal.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
