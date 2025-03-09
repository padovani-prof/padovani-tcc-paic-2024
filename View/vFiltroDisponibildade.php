<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filtro Disponibilidade</title>
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
  <header class="azul text-center text-white py-4 ">
      <h1>Disponibilidade</h1>
  </header>

  <section class="container w-75 p-4 border">
    <form action="cFiltroDisponibildade.php">

    <div class="row">
            <!--Inicio Tabela Categoria-->
            <div class="col-sm-12 col-md-6 border p-3 rounded">  
              <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover text-center align-middle">
                  <thead class="table-primary">
                    <tr>
                      <th>Categoria ou Recurso</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{RecursoCategoria}}
                  </tbody>            
                </table>
              </div>
              

            <div class="d-flex justify-content-between align-items-center ">
                <div>
                  <label for="categoria" class="">Categoria: </label>
                  <select class="form-select w-auto" name="categoria" id="">
                    {{op-categoria}}
                  </select>
                </div>
                    
                <div>
                  {{dados-catego-recu}}
                  <input class="btn btn-primary" type="submit" value="Adicionar" name="btnCategoria">
                </div>
                
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                  <label class="" for="">Recurso:</label>
                  <select class="form-select" name="recurso" id="">
                  {{op-recurso}}
                  </select>
                </div>
                
                <input class="btn btn-primary" type="submit" value="Adicionar" name="btnRecursos">
            </div>
            
            </div>  <!--Fim Tabela Categoria-->
         
          <div class="col-sm-12 col-md-6 border p-3 rounded"> <!--Inicio Tabela Período-->
            <div class="table-reponsive">
                <table class="table table-striped table-bordered table-hover text-center align-middle">
                  <thead class="table-primary">
                    <tr>
                      <th>Período</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{Períodos}}
                  </tbody>
              </table>
            </div>
            
          <label for="">Data</label>
              <input class="form-control" type="date" name="p-data" id="" value="{{data}}">

              <label for="">Horário Local:</label>
                <input class="form-control" type="time" name="p-hora-ini" id="" value="{{hora_ini}}">

              <label>Horário Final: </label>
                <input class="form-control" type="time" name="p-hora-fim"  id="" value="{{hora_fim}}">

                <div class="d-flex mt-3 justify-content-end" > <!--Botões-->
                  <input class="btn btn-primary" type="submit" value="Adicionar" name="btnPeriodos">
                </div>
        </div>
        
  </div>

      {{peridos-salvos}}
 
    <p id="mensagem-{{retorno}}">{{mensagem}}</p>
    
    <div class="d-flex justify-content-between">
      <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
      <input class="btn btn-primary" type="submit" value="Consultar" name="btnConsultar">
    </div>
  </form> 

    
  
      
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>

