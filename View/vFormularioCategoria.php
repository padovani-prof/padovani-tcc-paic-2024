<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario-Categoria</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
      .azul{
        background-color: #0059b3;
      }
  
    #lbl-ambiente{
      color: black;
      font-weight: normal;
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
   {{cabecario}}

  <section class="container mt-4 border rounded shadow p-4 form-container">
    <p class="text-center fw-bold text-{{resposta}}">{{mensagem}}</p>  <!--Erro = danger, sucesso = success-->
      <form action="cFormularioCategoria.php">
        {{tipo_tela}}
        <input type="hidden" value="{{campoCodigo}}" />
        <div class="mb-3">
          <label for="">Nome: </label>
          <input class="form-control" type="text" name="nome" id="txtnome" value="{{Camponome}}">
        </div>
        
        <div class="mb-3">
          <label for="">Descrição: </label>
          <input class="form-control" type="text" name="descricao" id="txtdescricao" value="{{Campodescricao}}">
        </div>
        
        <div class="form-check mb-3">
          <label id="lbl-ambiente" for="cambiente">Ambiente Físico</label> 
          <input class="form-check-input" type="checkbox" name="ambiente_fisico" id="cambiente" {{Campoambiente}}>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
          <a href=" cCategoria.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
          <input class="btn btn-primary" type="submit" name="salvar" value="Salvar">
        </div>
      </form>
      
      
  </section>

 
  
  
</body>
</html>
