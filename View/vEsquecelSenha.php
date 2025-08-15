<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu Senha</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h1 class="text-center mb-4 text-primary">Esqueceu a Senha</h1>

                       
                        <p id="{{resposta}}" class="text-center text-danger fw-semibold">
                            {{msg}}
                        </p>

                            <form action="cEsquecelSenha.php" method="GET">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" id="email" name="email" value="{{email}}" class="form-control" placeholder="Digite seu e-mail" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" name="mandar" value="Enviar" class="btn btn-primary">
                            </div>
                        </form>

                    
                        <div class="mt-3 text-center">
                            <a href="cLogin.php" class="text-decoration-none">&larr; Voltar para o Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
