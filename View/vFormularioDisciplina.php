<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>vFormularioDisciplina</title>
  
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
    <h1>{{tela}}  Disciplina </h1>
  </header>

  <section  class="container mt-4 border rounded shadow p-4 form-container">
    <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p>

      <form  action="cFormularioDisciplina.php">
        {{tipo_tela}}
        <div class="mb-3">
          <label for="">Nome: </label>
          <input class="form-control" type="text" name="nome" id="txtnome" value="{{Camponome}}">
        </div>
        
        <div class="mb-3">
          <label for="">Curso: </label>
          <input class="form-control" type="text" name="curso" id="txtcurso" value="{{Campocurso}}">
        </div>
        
        <div class="mb-3">
          <label for="">Período: </label>
          <select class="form-select" name="periodo" id="txtperiodo">
            
              {{Periodo}}
          
          </select>
        </div>
        
        <div class="d-flex justify-content-between">
          <a href="cDisciplina.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>

          <input class="btn btn-primary" type="submit" name="salvar" value="Salvar">
          </div>
      </form>  
  </section>
        
</body>
</html>
