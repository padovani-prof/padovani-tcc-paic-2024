<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Portaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .azul{
          background-color: #0059b3;
        }

      .form-container {
      max-width: 600px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
      </style>

</head>
<body class="bg-light bg-primary text-white vh-100 d-flex flex-column">

    <header class="azul bg-gradient text-white py-4 text-center">
            <h1>Sistema de Gestão de Recursos Pedagógicos</h1>
    </header>

    <main class="flex-grow-1 d-flex justify-content-center align-items-center"">
        <section class="container bg-white rounded text-dark shadow-lg p-4" style="max-width: 500px; ">
            <div class="text-center mt-4">
                <h2 class="fw-bold text-primary">Bem Vindo</h2>
                <p class="text-muted">Faça Login para continuar</p>
            </div>

            <p class="text-center fw-bold text-{{resp}}">{{mensagem}}</p> 

            <form class="d-flex flex-column" action="cLogin.php" method="post">

                <div class="mb-3 w-100">
                    <label class=" form-label fw-bold text-primary " for="">E-mail:</label>
                    <input class="form-control" type="email" name="txtemail" id="txtemail" placeholder="Digite seu E-mail" required>
                </div>
                
                <div class="mb-4">
                    <label class="fw-bold text-primary form-label" for="">Senha:</label>
                    <input class="form-control" type="password" name="txtsenha" id="txtsenha" placeholder="Digite sua senha" required>
                </div>
            
            <div class="d-grid gap-2 mb-3">
                <input class="btn btn-primary btn-lg" id="btconectar" type="submit" value="Conectar" onclick="conectar()">
            </div>
                
            <div class="text-end " id="esqueceu_senha" >
                <a href="cEsquecelSenha.php" class="text-primary text-decoration-none fw-semibold">Esqueceu sua senha?</a>
            </div>
            </form>
        </section>
    </main>
        
        <footer class="azul bg-gradient text-center py-3">
            <p class="m-0">&copy; Todos os Direitos Reservados</p>
        </footer>
    
    <script src="script.js"></script>
</body>
</html>
