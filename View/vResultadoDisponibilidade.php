<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado Disponibilidade</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .azul{
      background-color: #0059b3;
    }

    btn-custom{
      background-color: #026cb6;
    }

    .form-container {
      max-width: 600px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
  </style>
</head>
<body>
  <header class="azul text-center text-white py-4">
      <h1>Disponibilidade</h1>
  </header>
  

  
  <section class="container border rounded shadow p-4 form-container">
  <p class="text-center fw-bold text-{{retorno}}" >{{msg}}</p>
    <div>
      <form action="cResultadoDisponibilidade.php">
        <div class="table-reponsive">
          <table class="table table-striped table-bordered table-hover text-center align-middle">
            <thead class="table-primary">
              <tr>
                <th>Recurso</th>
                {{Colunas}}
              </tr>
            </thead>
            
            <tbody>
              {{Disponibilidades}}
            </tbody>
            {{cate}}
            {{recu}}
            {{periodo}}
          </table>
        </div>
      
      <div class="d-flex justify-content-between mt-4">
        <a href="cFiltroDisponibildade.php?link=1&{{informa}}" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>


        <input class="btn btn-primary" type="submit" name="reserva" value="Reservar">
      </div>
      
      </form>
    </div>    
     
     
      
  </section>



  
</body>
</html>
