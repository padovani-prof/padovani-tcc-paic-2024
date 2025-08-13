<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist</title>
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
     {{cabecario}}

     
     <section class="container border shadow rounded col-12 col-sm-10 col-md-8 col-lg-6 mt-4 p-4"> <!--Inicio Section-->
     <p class="text-center fw-bold text-{{respe}}">{{msg}}</p> 
          <form action="cChecklist.php" class="d-flex flex-column flex-sm-row align-items-sm-end gap-2">
            <div>
                <p>Recurso: {{nomerecurso}}</p>  
                <label for="txtitem">Item:</label>
                <input class="form-control" type="text" name="txtitem" id="txtitem" value="{{nome}}">
                <input type="hidden" name='codigo' value="{{codigo}}">

            </div>

              <div>
                <input class="btn btn-primary" type="submit" name="adicionar" value="Adicionar">
              </div>


          </form>
           
          <div class="table-responsive">
            <table class="table text-center table-hover table-bordered">
              <thead class="table-primary">
                <tr>
                  <th class="w-75">Itens</th>
                  <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                {{itens}}
              </tbody>
              

              
            </table>
          </div>
           
            

            <div>
                <a href="cRecursos.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
            </div>


     </section>  
    
    <script src="script.js"></script>
</body>
</html>
