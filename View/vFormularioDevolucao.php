<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devolução</title>
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
    /* ===== CANCELAR (qualquer tipo) ===== */
  .text-center a[href*="cancela"] {
      color: red;
      font-weight: bold;
      text-decoration: none;
  }

  .text-center a[href*="cancela"]:hover {
      color: #fff;
      background-color: #ff4d4d;
      padding: 5px 10px;
      border-radius: 5px;
  }

  /* ===== RETIRADA ou DEVOLUÇÃO ===== */
  .text-center a[href*="FormularioRetirada.php"]:not([href*="cancela"]),
  .text-center a[href*="FormularioDevolucao.php"]:not([href*="cancela"]) {
      color: #0d6efd; /* Azul Bootstrap */
      font-weight: bold;
      text-decoration: none;
  }

  .text-center a[href*="FormularioRetirada.php"]:not([href*="cancela"]):hover,
  .text-center a[href*="FormularioDevolucao.php"]:not([href*="cancela"]):hover {
      color: white;
      background-color: #0d6efd; /* Azul Bootstrap */
      padding: 4px 8px;
      border-radius: 5px;
  }


    </style>
    
  </style>
</head>
<body class="bg-light">
   {{cabecario}}

  <section class="container mt-4 border rounded shadow p-4 mb-5 form-container">

    <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p>  
        <form action="{{mandar}}">  
            <div class="mb-3">
              <label for="">Recursos Retirado: </label>
              <select class="form-select" name="recurso" id="">
              {{recursos}}
              </select>
            </div>
            <div class="mb-3">
              <label for="">Devolvente: </label>
              <select class="form-select" name="devolvente" id="">
              {{devolvente}}
            </select>
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
            

            <div class="d-flex justify-content-between">
              <a href="cMenu.php"><input class="btn btn-secondary" type="button" value="Voltar"></a>
              <input class="btn btn-primary" type="submit" value="Confirmar" name="btnConfirmar"> 
            </div>

            {{cancelaRetirada}}
            
        </form> 
        
        
         
        
        
        

        
      
  </section>

      <div class="text-center">
        <a href="{{link}}">{{texto}}</a>
      </div>



  
</body>
</html>

