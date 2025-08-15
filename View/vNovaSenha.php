<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha</title>
    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .azul{
          background-color: #0059b3;
        }
    
        .btn-custom{
          background-color: #026cb6;
        }

        label{
         color: #1a76d8;
         font-weight: bolder;
    }
    .form-container {
      max-width: 600px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
    input[type="password"],
    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }

input[type="password"]:focus,
input[type="text"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}
    </style>
</head>
<body class="bg-light">

    <!-- Cabeçalho -->
    
    {{cabecario}}


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        

                        <!-- Mensagem -->
                        <p  class="text-center fw-semibold text-{{resp}}">
                            {{msg}}
                        </p>

                        <!-- Formulário -->
                        <form action="cNovaSenha.php" method="post">
                            <div class="mb-3">
                                {{dados}}
                            </div>

                            <div class="d-grid">
                                <input type="submit" name="mandar" value="Confirmar" class="btn btn-success">
                            </div>
                        </form>

                        <!-- Link de voltar -->
                        <div class="mt-3 text-center">
                            <a href="cMenu.php" class="text-decoration-none">&larr; Voltar para o Menu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
