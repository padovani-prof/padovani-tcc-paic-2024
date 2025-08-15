<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retirada </title>
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
    .link-cancelar {
    display: inline-block;
    color: #ff4d4d;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .link-cancelar:hover {
    color: #fff;
    background-color: #ff4d4d;
    padding: 5px 10px;
    border-radius: 5px;
  }
    </style>
</head>
<body class="bg-light">
   {{cabecario}}

  <section class="container mt-4 border rounded shadow p-4 mb-5 form-container">
        <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p> 
    
        <form action="cFormularioRetirada.php" method="post">  
            <p>
              <label for="">Recurso: </label>
              <select class="form-select" name="recurso" id="">
              {{recursos}}
              
              </select>
            </p>
            
            <label for="">Retirante: </label>
            <select class="form-select mb-3" name="retirante" id="">
              {{retirante}}

            </select>

              <div class="d-flex align-items-center gap-3 mb-3">
                <label class="m-0" for="">Horário Final:</label>
                <input class="form-control w-auto" type="time" name="hora_final"  id="" value="{{hora_fim}}">
              </div> 
              <table>
                <thead>
                    <tr>
                        <th style="width: 35%;"></th>
                        <th style="width: 40%;"></th>
                        <th></th>
                    </tr>

                </thead>
                <tbody>
                    {{chechlistRecurso}}
                </tbody>
            
            </table>

            <div>
                {{senha_usuario}}
            </div>
            


            <div class="d-flex justify-content-between">
              <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>

              <input class="btn btn-primary" type="submit" value="Confirmar" name="btnConfirmar"> 
            </div>

            
              
        </form> 
  </section>

    <div class="text-center">
      <a href="cFormularioRetirada.php?cancela=true" class="link-cancelar">Cancelar Retirada</a>
    </div>

</body>
</html>


