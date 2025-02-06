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
<body>
  <header class="azul text-center text-white py-4">
      <h1>Categoria</h1>
  </header>
  <section class="container my-4 w-75">
    <div class="table-reponsive">
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

      <p id="id-{{resposta}}">{{msg}}</p>
    
    <!-- colocar a mensagen de apagar --->
        
        
    </div>
    
    <div class="d-flex justify-content-between mt-4">
      <a class="" href="cMenu.php"><input class="btn btn-outline-primary" type="button" value="Voltar"></a>
      <a href="cFormularioCategoria.php"><input class="btn btn-primary" type="button" value="Nova Categoria"></a> 
      
    </div>
  
  </section>
</body>
</html>
