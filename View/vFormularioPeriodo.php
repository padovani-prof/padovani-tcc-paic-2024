
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario Período</title>
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
      <h1>{{tela}} Período</h1>
  </header>

  <section class="container mt-4 border rounded shadow p-4 form-container">
  <p class="text-center fw-bold text-{{msg}}" >{{mensagem}}</p>
      <form action="cFormularioPeriodo.php" >
        {{tipo_tela}}
          <label for="">Nome: </label>
          <input class="form-control" type="text" name="txtnome" id="txtnome" value="{{nomePeriodo}}">

          <div class="container">
            <label for="data_ini">Data Inicial:</label>
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                <input class="form-control flex-grow-1" type="date" name="data_ini" id="data_ini" value="{{dataIni}}">
                <span class="text-center">a</span>
                <input class="form-control flex-grow-1" type="date" name="data_fim" id="data_fim" value="{{dataFim}}">
                
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
          <a href="cPeriodo.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
          <input class="btn btn-primary" type="submit" name="salvar" value="Salvar">
        </div>
      
        </form>

     
      
  </section>



  
</body>
</html>
