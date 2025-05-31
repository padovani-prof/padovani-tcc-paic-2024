<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Historico</h1>



    <div class="table-reponsive" style="max-height: 400px; overflow-y: auto;">
            <table>
              <thead >
                <tr>
                  <th>Data</th>
                  <th>Usúario Ret.</th>
                  <th>Horario da Ret.</th>
                  <th>Horario da Dev.</th>
                  <th>Historico Retirada</th>
                </tr> 
              </thead>
              <tbody>
                {{historico_recurso}}
              </tbody>
              
          </table>


          <a href="cRecursos.php"> voltar</a>
          </div>  
</body>
</html>

