<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil USuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .azul{
        background-color: #0059b3;
      }
  
      .btn-custom{
        background-color: #026cb6;
      }
      .form-container {
      max-width: 700px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
    </style>
</head>
<body class="bg-light">
  <header class="azul text-center text-white py-4">
    <h1>Perfil de Usuário</h1>
  </header>
    

  
    <section class="container mt-4 border rounded shadow p-4 form-container">
    <p class="text-center fw-bold text-{{resp}}">{{msg}}</p> 

        <div class="table-reponsive">
            <table class="table table-bordered table-striped table-hover text-center align-middle">
              <thead  class="table-primary">
                <tr>
                  <th width="30%">Nome</th>
                  <th>Descrição</th>
                  <th>Operação</th>
                </tr>
                <tbody>
                  {{perfis}}
                </tbody>
                
              </thead>              
              
            </table>
      </div>

      <div class="d-flex justify-content-between mt-4">
          <a href="cMenu.php" class="btn btn-secondary">Voltar</a>         
          <a href="cFormularioPerfil.php" class="btn btn-primary">Novo Perfil</a>
      </div>
        
    </section>
</body>
</html>
