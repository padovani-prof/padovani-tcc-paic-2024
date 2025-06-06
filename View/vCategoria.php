<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario-Categoia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .azul{
      background-color: #0059b3;
    }

  </style>
</head>
<body class="bg-light">
  <header class="azul text-center text-white py-4">
      <h1>Categoria</h1>
  </header>

  
  
  <section class="container mt-4 border rounded shadow p-4 col-12 col-sm-10 col-md-8 col-lg-6">
  <p class="text-center fw-bold text-{{resposta}}">{{msg}}</p> 
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="table-primary">
          <tr>
            <th width="60%">Nome</th>
            <th>Operações</th>
          </tr>
        </thead>

        <tbody>
          
          
          {{Categoria}}
        </tbody>
      </table>        
    </div>
    
    <div class="d-flex justify-content-between mt-4">
      <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>

      <a href="cFormularioCategoria.php" class="btn btn-primary" type="button" value="Nova Categoria">Nova Categoria</a> 
    </div>
  
  </section>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https:js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> 

</body>
</html>
