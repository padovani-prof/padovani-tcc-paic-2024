<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .azul{
      background-color: #0059b3;
    }

    btn-custom{
      background-color: #026cb6;
    }
  </style>

</head>
<body class="bg-light">
  <header class="azul text-center text-white py-4">
      <h1>Reservas</h1>
  </header>

  <section class="container mt-4 border rounded shadow p-4 col-12 col-sm-10 col-md-8 col-lg-6">

  <p class="text-center fw-bold text-{{retorno}}" >{{mensagem}}</p>    
    <form action="cReservas.php">  
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-center align-middle"> 
          <thead class="table-primary">
            <tr>
              <th>Recurso</th>
              <th>Usuário</th>
              <th>Datas e Horários</th>
              <th>Ações</th>
            </tr>
          </thead> 
          <tbody>
            {{Reservas}}

          </tbody>
        </table>
      </div>
        
      <div class="d-flex justify-content-end">
        <a href="cFormularioReserva.php" class="btn btn-primary" type="button" value="Nova Reserva" name="btnNovaReserva">Nova Reserva</a>
      </div>
     
    </form> 

    <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a> 
  </section>
</body>
</html>
