<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nova Reserva</title>
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
  <header class="azul text-center text-white py-4">
      <h1>Nova Reserva</h1>
  </header>
  <section class="container mt-4 border rounded shadow p-4 form-container" >
  <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p> 
    
    <form action="cFormularioReserva.php"> 
      <div>
        <label for="">Recurso: </label>
        <select class="form-select" name="recurso" id="recurso">
          {{Recursos}}
        </select>
      </div> 
        
      <div>
        <label for="">Agendado por: </label>
        <select class="form-select" name="usuario_agendador" id="usuario_agendador">
          {{UsuariosAgendador}}
        </select>
      </div>
        
        <div>
          <label for="">Justificativa: </label>
          <input class="form-control" type="text" name="justificativa" id="justificativa" value="{{Campojustificativa}}">
        </div>
        
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover text-center align-middle">
            <thead class="table-primary text-center">
              <tr>
                {{verifica}}
                <th>Data</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody>
              {{Datas Reservas}}    
            </tbody>
            
            
        </table>

        </div>
        
        Nova Data: 
        <div class="row">
          <div class="col-12 col-md-4 mb-3">
            <label for="data">Data: </label>
            <input class="form-control" type="date" name="data" id="data" value="{{data}}">
          </div>
          <div class="col-6 col-md-4 mb-3">
            <label for="hora_inicial">Hora Inicial: </label>
            <input class="form-control" type="time" name="hora_inicial" id="hora_inicial" value="{{hora_inicial}}">
          </div>
          <div class="col-6 col-md-4 mb-3">
            <label for="hora_final">Hora Final: </label>
            <input class="form-control" type="time" name="hora_final" id="hora_final" value="{{hora_final}}">
          </div>
        </div>

          {{Lista Data}}
          <div class="d-flex justify-content-end pe-2">
            <input class="btn btn-primary" type="submit" value="Adicionar" name="btnAdicionar"> 
          </div>
          
        <div>
          <label for="">Agendado para: </label>
          <select class="form-select" name="usuario_utilizador" id="usuario_utilizador">
            {{Usuarios}}
          </select>
        </div>
           
        

        <div class="d-flex justify-content-between">
            <a href="cReservas.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
            <input class="btn btn-primary" type="submit" value="Salvar" name="btnSalvar"> 
        </div>

    </form> 
 
  </section>
</body>
</html>
