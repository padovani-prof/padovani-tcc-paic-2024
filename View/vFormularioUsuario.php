
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .azul{
          background-color: #0059b3;
        }
    
        .btn-custom{
          background-color: #026cb6;
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
        <h1>Usuário</h1>
    </header>
    <section class="container border rounded shadow mt-4 p-4 form-container">
        <p class="text-center fw-bold text-{{retorno}}" >{{mensagem}}</p>

        <form action="cFormularioUsuario.php" method="post">
            {{tipo}}
            <div class="row mb-2"> <!--Inicio nome e email-->
                <div class="form-group col-md-6 mb-3">
                    <label for="nome">Nome:</label>
                    <input class="form-control" type="text" name="nome" id="nome" value="{{campoNome}}" placeholder="Digite seu nome" required> 
                </div>
                
                <div class="form-group col-md-6 mb-3">
                    <label for="email">Email:</label>
                    <input class="form-control" type="email" name="email" id="email" value="{{campoEmail}}">
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label for="senha">Senha:</label>
                    <input class="form-control" type="password" name="senha" id="senha" value="{{campoSenha}}">
                <!-- será que tem como ter a opção de mostrar senha? -->
                </div>
                
                <div class="form-group col-md-6 mb-3">
                    <label  for="senha">Confirmar Senha:</label>
                    <input class="form-control" type="password" name="conf_senha" id="conf_senha" value="{{campoConfirma}}"> 
                <!--aqui verificar se a senha foi preenchida corretamente-->
                </div>
            </div> <!--Fim nome e email-->
                      
            <label class="mb-3" for="">{{titulo}}</label>

                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 40%;"></th>
                            <th style="width: 45%;"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{perfis}}
                          
                    </tbody>

                </table>
                
            

            

            <div class="d-flex justify-content-between w-100">
                <div class="text-start">
                    <a href="cUsuario.php" class="btn btn-secondary" type="button" value="Voltar">Voltar</a>
                </div>
                
                <div>
                    <input class="btn btn-primary" type="submit" name="salvar" value="Salvar">
                </div>
            </div>
        </form>
    </section>

</body>
</html>
