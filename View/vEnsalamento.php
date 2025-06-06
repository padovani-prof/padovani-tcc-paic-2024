<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ensalamento</title>

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

      .form-container {
      max-width: 800px; /*  limite para telas grandes */
      width: 100%; /* não extrapole em telas menores */
      margin: auto; /* Vai centralizar o formulário*/
    } 
    </style>

</head>
<body>
  <header class="azul text-white text-center py-4">
      <h1>Dias da Semana</h1>
  </header>

    <section class="container mt-4 border rounded shadow p-4 w-100 w-md-75 w-lg-50 form-container">
      <form action="cEnsalamento.php">
        
        <div class="d-flex align-itens-center mb-3">
            <label class="form-label me-3" for="">Período: </label>
            <select class="form-select " name="periodo"   id="txtperiodo">
            {{periodo}}
            </select>
        </div>

        <div class="d-flex align-itens-center mb-3">
          <label class="form-label me-2" for="">Disciplina: </label>
          <select class="form-select " name="disciplina" id="txtdisciplina">
          {{disciplina}}
          </select>
        </div>
          
        <div class="d-flex align-itens-center mb-4">
          <label class="form-label me-5" for="">Sala: </label>
          <select class="form-select" name="sala" id="txtsala">
          {{sala}}
          </select>
        </div>
          
        <div>
          <input class="btn btn-success" type="submit" name="filtrar" value="Filtrar">
        </div> 

    </form>

      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
          <table class="table table-striped table-bordered table-hover text-center align-middle">
            <thead>
              <tr class="table-primary">
                <th>Sala</th>
                <th>Período</th>
                <th>Disciplina</th>
                <th>Dia da Semana</th>
                <th>Horário</th>
                <th>Operação</th>
              </tr>
            </thead>
            <tbody>
              {{Categoria}}
                
            </tbody>
            
                
        </table>
      </div>
      
    <div class="d-flex justify-content-between mt-4">
      <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
      <a href="cFormularioEnsalamento.php"><input class="btn btn-primary" type="button" value="Novo"></a>
    </div>
    
  </section>

    

   
    <script src="script.js"></script>
</body>
</html>
