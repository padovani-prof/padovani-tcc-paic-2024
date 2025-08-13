<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Ensalamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .azul{
        background-color: #0059b3;
      }

      .cor{
        color: #D81159;
      }

    label{
      color: #1a76d8;
      font-weight: bolder;
    }

    .form-container {
      max-width: 800px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    }
    
    </style>

</head>
<body class="bg-light container-fluid">
  <header class="azul text-center text-white py-4">
      <h1>Novo Ensalamento</h1>
  </header>

  <section class="container mt-4 border rounded shadow p-4 form-container">

    <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p> 
    <form action="cFormularioEnsalamento.php">
      
      <div class="d-flex gap-3 w-100">
          <div class="flex-grow-1">
            <label for="txtdisciplina">Disciplina: </label>
            <select class="form-select" name="disciplina" id="txtdisciplina">
            {{Disciplina}}
          </select>
          </div>
          
          <div class="flex-grow-1">
            <label for="txtsala">Sala: </label>
            <select class="form-select" name="sala" id="txtsala">
            {{Sala}}
            </select>
          </div>
      </div>
        

        <label>Dias da Semana:</label>
        <div class="container mt-2 p-3 border rounded mb-3"> <!--Inicio seção Semana-->

            <div class="d-flex gap-3 flex-wrap">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="1" id="domingo">
                    <label class="form-check-label cor" for="domingo">Domingo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="2" id="segunda">
                    <label class="form-check-label cor" for="segunda">Segunda</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="3" id="terca">
                    <label class="form-check-label cor" for="terca">Terça</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="4" id="quarta">
                    <label class="form-check-label cor" for="quarta">Quarta</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="5" id="quinta">
                    <label class="form-check-label cor" for="quinta">Quinta</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="6" id="sexta">
                    <label class="form-check-label cor" for="sexta">Sexta</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="DiaSemana[]" value="7" id="sabado">
                    <label class="form-check-label cor" for="sabado">Sábado</label>
                </div>
            </div>
      </div> <!--Fim seção Semana-->

        <div class="container">
            <label for="">Horário: </label>
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 w-50">
              <input class="form-control" type="time" name="h_inicio" id="hora_inicio"> -
            <input class="form-control" type="time" name="h_fim" id="hora_fim">             
            </div>
            
        </div>
         
        <div class="d-flex justify-content-between mt-4">
          <a href="cEnsalamento.php"><input class="btn btn-secondary" type="button" value="Voltar"></a>
          <input class="btn btn-primary" type="submit" name="salvar" value="Salvar">
          
        </div>
        
    </form>

  </section>
    <script src="script.js"></script>
</body>
</html>
