<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    .azul{
      background-color: #0059b3;
    }

    btn-custom{
      background-color: #026cb6;
    }
    .conteudo-completo {
      display: none;
    }

    .ver-mais {
      color: blue;
      cursor: pointer;
      text-decoration: underline;
    }
  </style>

</head>
<body class="bg-light">
   {{cabecario}}

  <section class="container mt-4 border rounded shadow p-4 col-12 col-sm-10 col-md-8 col-lg-6">

  <p class="text-center fw-bold text-{{retorno}}" >{{mensagem}}</p>    
     
  
      <div class="table-responsive">


      <form action="cReservas.php">
        <label for="">Recurso : </label>
      <select class="form-select" name="recurso" id="">
              {{recursos}}
              </select>

      <label for="">Usuário : </label>
      <select class="form-select" name="usuario" id="" >
              {{usuario}}
              </select>
      Data inicial: <input type="date" name="p_ini" value="{{data_ini}}"> Data limite <input type="date" name="p_fim" value="{{data_fim}}" >
      <input type="submit" name="filtra" value="FILTRAR">


      <div class="table-reponsive" style="max-height: 400px; overflow-y: auto;">

      <table class="table table-striped table-bordered table-hover text-center align-middle"> 
          <thead class="table-primary">
            <tr>
              <th>Recurso</th>
              <th>Usuário</th>
              <th>Datas e Horários</th>
              <th>Ações</th>
            </tr>
          </thead> 
          <tbody>
            {{Reservas}}

          </tbody>
        </table>

      </div>
 
      
        
      </div>
        
      <div class="d-flex justify-content-end">
        <a href="cFormularioReserva.php" class="btn btn-primary" type="button" value="Nova Reserva" name="btnNovaReserva">Nova Reserva</a>
      </div>
     
    </form> 

    <a href="cMenu.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a> 
  </section>
  <script>
      function alternarConteudo(elemento) {
        const td = elemento.parentElement;
        const resumo = td.querySelector('.resumo');
        const completo = td.querySelector('.completo');

        const estaExpandido = completo.style.display === 'inline';

        if (estaExpandido) {
          // Ocultar conteúdo completo e mostrar resumo
          completo.style.display = 'none';
          resumo.style.display = 'inline';
          elemento.textContent = 'Todas as datas';
        } else {
          // Mostrar conteúdo completo e esconder resumo
          completo.style.display = 'inline';
          resumo.style.display = 'none';
          elemento.textContent = 'Esconder datas';
        }
      }

  </script>
</body>
</html>


