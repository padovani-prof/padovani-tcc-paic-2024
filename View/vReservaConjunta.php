<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserva Conjunta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
      .azul{
        background-color: #0059b3;
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
    </style>
</head>
<body class="bg-light">
   {{cabecario}}

  <section class="container mt-4 border rounded shadow p-4 form-container">
  <p class="text-center fw-bold text-{{retorno}}" >{{msg}}</p>

    <form action="cReservaConjunta.php">  
      <div class="table-responsive"  style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered table-hover text-center align-middle">  
          <thead class="table-primary">
            <tr>
              <th width="50%">Reservas</th>
              <th>Data e Horário</th>
            </tr>
          </thead>
          <tbody>
    
          </tbody>
          
          {{reservas}}
        
        </table>
      </div>

      <div>
        <label for="">Agendado por: </label>
        <select class="form-select" name="agendador" id="">
          {{agendador}}
        </select>
      </div>
        
        <div>
          <label for="">Justificativa: </label>
          <input class="form-control" type="text" name="justfc" id="" value="{{just}}">
        </div>

        {{usuario}}
         
        {{dados}}
        
        <div class="d-flex justify-content-between ">
          <a href="cFiltroDisponibildade.php?{{link}}"><input class="btn btn-secondary" type="button" value="Voltar"></a> 

          <input class="btn btn-primary" type="submit" value="Reservar" name="reservar">
        </div>
           
  
    </form> 

  </section>


</body>
</html>


