<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissão</title>
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
    <h1>Permissão de Recurso</h1>
  </header>

  <p id="{{rep}}">{{mensagemAnomalia}}</p> <!-- errro ou sucesso-->
     <section class="container mt-4 border rounded shadow p-4 form-container"> <!--Inicio Section-->
     
          <form action="cPermissaoRecurso.php">
                <input type="hidden" name="codigorecurso" value="{{codigorecurso}}">
                <p>Recurso: {{nomerecurso}}</p>

                <div>
                  <label for="">Perfil com Acesso: </label>
                  <select class="form-select" name="perfio_usuario" id="">
                    {{perfis}}
                  </select>
                </div>

                <div class="container">
                    <label for="#">Horário:</label>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 w-50">
                      <input class="form-control" type="time" name="hora_ini" value="{{horaInicial}}" id="">
                      <span class="">a</span>
                      <input class="form-control" type="time" name="hora_fim" value="{{horaFinal}}}" id="">
                    </div>
                </div>
                
                <label for="">Dia da Semana</label>
                <div class="container mt-2 p-3 border rounded mb-3"> <!--Inicio Dias da Semana-->
                  <div  class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox"  name="dia0" {{0}} id="">Domingo
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia1" {{1}}id="">Segunda
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia2" {{2}} id="">Terça
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia3" {{3}}id="">Quarta
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia4" {{4}} id="">Quinta
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia5" {{5}}id="">Sexta
                  </div>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dia6" {{6}} id="">Sábado
                  </div>
                    
                  </div>
                </div> <!--Fim Dias da Semana-->

              <div>
                <label for="">Data Inicial:</label>
                <input class="form-control" type="date" name="data_ini" value="{{dataIni}}}" id="#">
                <label for="">Data Final:</label>
                <input class="form-control" type="date" name="data_fim" value="{{dataFinal}}}" id="#">
                
                <input type="hidden" name="codi_recurso" value="{{codigo_recurso_atual}}">    

              </div>
                          
      

            <div class="table-reponsive"> 
                <table class="table table-striped table-bordered table-hover text-center align-middle">
                  <thead class="table-primary">
                    <tr>
                      <th>Nome</th>
                      <th>Horário</th>
                      <th>Ação</th>
                  </tr>
                  
                  </thead>
                  <tbody>
                    {{permissoes}}
                  </tbody>
                  
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
              <a href="cRecursos.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
              <input class="btn btn-primary" type="submit" name="salvar" value="Adicionar"> 
            </div>

        </form>     
     </section>  
    
    <script src="script.js"></script>
</body>
</html>
