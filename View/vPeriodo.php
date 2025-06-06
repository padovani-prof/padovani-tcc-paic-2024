<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario-Período</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .azul{
      background-color: #0059b3;
    }

    .apagar{
        border: 1px solid red;
        color: red;
        background-color: white;
        transition: background-color 0.3s ease, color 0.3s ease;
        cursor: pointer;
      }

      .apagar:hover {
         background-color: red;
        color: white;
      }

  label{
    color: #1a76d8;
    font-weight: bolder;
  }
  </style>
  
</head>
<body class="bg-light">
  <header class="azul text-center text-white py-4">
      <h1>Período</h1>
  </header>

  <section class="container w-75 mt-4 border rounded shadow p-4 ">
  <p class="text-center fw-bold text-{{retorno}}" >{{mensagem}}</p>
    <div class="table-reponsive">

    
        <table class="table table-bordered table-striped table-hover text-center align-middle">
          <thead  class="table-primary">
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

           

  <div class="d-flex justify-content-between">
    <a href="cMenu.php"><input class="btn btn-secondary" type="button" value="Voltar"></a>
    <a href="cFormularioPeriodo.php"><input class="btn btn-primary" type="button" value="Novo"></a>
  </div>
  
      

  </section>



  
</body>
</html>
