<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Disciplina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
      .azul{
        background-color: #0059b3;
      }
  
    label{
      color: #1a76d8;
      font-weight: bolder;
    }
    </style>

</head>
<body class="bg-light">
  <header class="azul text-white text-center py-4 ">
      <h1>Disciplina</h1>
  </header>

    <section class="container mt-4 w-50 border rounded shadow p-4">
      <p class="text-center fw-bold text-{{resp}}">{{msg}}</p> 

          <div class="table-responsive"  style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-bordered table-hover text-center align-middle">
              <thead class="table-primary"> 
                <tr>
                  <th class="w-50">Nome</th>
                  <th>Operações</th>
                </tr>
              </thead>

              <tbody>
                {{disciplinas}}
              </tbody>
              
                  
          </table>
            
        </div>
        <div class="d-flex justify-content-between mt-4">
              <a href="cMenu.php"><input class="btn btn-secondary"  type="button" value="Voltar"></a>

              <a href="cFormularioDisciplina.php"><input class="btn btn-primary" type="button" value="Nova Disciplina"></a> 
            </div>

              
    </section>


  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https:js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>  
</body>
</html>
