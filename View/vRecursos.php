<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .azul{
        background-color: #0059b3;
      }
  
      btn-custom{
        background-color: #026cb6;
      }

      .form-container {
      max-width: 800px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }

    </style>

</head>
<body class="bg-light">
     <header class="azul text-center text-white py-4">
      <h1>Recursos</h1>
     </header>

     
     <section class="container border rounded shadow my-4 p-4 form-container"> <!--Inicio Section-->
     <p class="text-center fw-bold text-{{repos}}">{{msg}}</p> 
          <div class="table-reponsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-hover text-center align-middle">
              <thead class="table-primary">
                <tr>
                  <th width="20%">Nome</th>
                  <th>Operações</th>
                </tr> 
              </thead>
              <tbody>
                {{recursos}}
              </tbody>
              
              
          </table>

          </div>     
        
          <div class="d-flex justify-content-between">
                <a href="cMenu.php" class="btn btn-secondary">Voltar</a>             
                <a href="cFormularioRecurso.php" class="btn btn-primary">Novo Recurso</a>
          </div>
      </section>  
</body>
</html>
