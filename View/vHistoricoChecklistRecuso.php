<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico Checklist</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

       <style>
      .azul{
        background-color: #0059b3;
      }
  
      btn-custom{
        background-color: #026cb6;
      }

      .form-container {
      max-width: 800px; 
      width: 100%; 
      margin: auto; 
    }

    </style>

</head>
<body class="bg-light">

    
        {{cabecario}}
        
        
        

    <section class="container border rounded shadow my-4 p-4 form-container"> 

    
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                
                <div class="mb-4">
                    <p class="mb-1"><strong>Data:</strong> {{data}}</p>
                    <p class="mb-1"><strong>Usuário:</strong> {{usuario}}</p>
                    <p class="mb-1"><strong>Horário da Retirada:</strong> {{hora_retirada}}</p>
                    <p class="mb-1"><strong>Horário da Devolução:</strong> {{hora_devolucao}}</p>
                </div>

            
                <div class="table-reponsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-hover text-center align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Checklist</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{historico_checklist}}
                        </tbody>
                    </table>
                </div>

           
                <div>
                    <a href="cHistoricoRetiradaRecurso.php?codigo_recurso={{cod}}" 
                       class="btn btn-secondary">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
