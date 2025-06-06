<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Recurso</title>
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
      <h1>Recursos</h1>
     </header>
     
     <section class="container border rounded shadow mt-4 p-4 form-container"> <!--Inicio Section-->

      <p class="text-center fw-bold text-{{retorno}}" >{{mensagem}}</p>
       
      <form action="cFormularioRecurso.php">
        {{tipo_tela}}
              <div class="row">
                <label for="">Nome:</label>
                <input class="form-control" type="text" name="nome" value = "{{campoNome}}" id="">
                <label for="">Descrição: </label>
                <input class="form-control" type="text" name="descricao" value="{{campoDescricao}}" id="">
              </div>
                            
              <div class="form-group">
                  <label for="categoria">Categoria</label>
                  <select class="form-select mb-3" name="categoria" id="">
                    
                  {{categoriarecurso}}
                
                  </select>
              </div>

            <div class="d-flex justify-content-between w-100 mt-4">
                <a href="cRecursos.php" class="btn btn-secondary">Voltar</a>  
                <button type="submit"  name="salvar" class="btn btn-primary">Salvar</button>
             
            </div>
            
        </form>   
      </section>  
</body>
</html>
