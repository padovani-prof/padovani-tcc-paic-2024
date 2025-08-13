<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .azul{
        background-color: #0059b3;
      }
  
      .form-container {
      max-width: 900px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
    </style>
</head>
<body class="bg-light">
    {{cabecario}}
    
    

    <section class="container mt-4 border rounded shadow p-4 form-container">
    <p class="text-center fw-bold text-{{resp}}">{{msg}}</p> 
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center align-middle">
              <thead class="table-primary">
                <tr>
                  <th width="30%">Nome</th>
                  <th width="30%">E-mail:</th>
                  <th>Operações</th>
                </tr>
              </thead>  
              <tbody>
                <tr>
                  {{usuarios}}
                </tr>
              </tbody>          
             
            </table>

          </div>
          
          <div class="d-flex justify-content-between">
            <a href="cMenu.php" class="btn btn-secondary">Voltar</a>        
            <a href="cFormularioUsuario.php" class="btn btn-primary">Novo Usuário</a>    
          </div>
         
    </section>
</body>
</html>
