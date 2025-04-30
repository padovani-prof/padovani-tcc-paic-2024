<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil USuário</title>
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
    
    {{cabecario}}

    <header class="azul text-center text-white py-4">
        <h1>Perfil de Usuário</h1>
    </header>
    
    <section class="container mt-4 form-container">

        <p class="text-center fw-bold text-{{retorno}}">{{mensagem}}</p> 


        <form class="border shadow rounded p-4" action="cFormularioPerfil.php">
            {{tipo_tela}}
            <div class="mb-3">
                <label for="nome">Nome:</label>
                <input class="form-control" type="text" name="nome" id="nome" value="{{campoNome}}"> 
            </div>
            
            <div class="mb-3">
                <label for="descricao">Descrição:</label>
                <input class="form-control" type="text" name="descricao" id="descricao" value="{{campoDescricao}}">
            </div>
            

            <label for="">Funcionalidades: </label>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>

                </thead>
                <tbody>
                    {{funcionalidades}}
                </tbody>
            
            </table>
            

            <div class="d-flex justify-content-between mt-4">
                
                <a href="cPerfilUsuario.php" class="btn btn-secondary">Voltar</a>
            
                <button type="submit"  name="salvar" class="btn btn-primary">Salvar</button>   
            </div>     
     
        </form>
        
        
    </section>
</body>
</html>
