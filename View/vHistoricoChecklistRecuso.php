<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     {{cabecario}}
    <h1>Historico Checklist</h1>

    <p>Data: {{data}}</p>
    <p>Usuário: {{usuario}}</p>
    <p>Horario da Retirada: {{hora_retirada}}</p>
    <p>Horario da Devolução: {{hora_devolucao}}</p>


    <table>
              <thead >
                <tr>
                  <th>Checklist</th>
                  <th>Situação</th>
                </tr> 
              </thead>
              <tbody>
                {{historico_checklist}}
              </tbody>
              
          </table>

          <a href="cHistoricoRetiradaRecurso.php?codigo_recurso={{cod}}">voltar</a>
    
</body>
</html>