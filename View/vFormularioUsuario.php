<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USuário</title>
</head>
<body>
    <section>
        <h1>Usuario</h1>
        
        <form action="cFormularioUsuario.php">
            {{tipo}}
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="{{campoNome}}"> <br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{campoEmail}}"> <br>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" value="{{campoSenha}}"> <br>
            <!-- será que tem como ter a opção de mostrar senha? -->

            <label for="senna">Confirmar Senha:</label>
            <input type="password" name="conf_senha" id="conf_senha" value="{{campoConfirma}}"> <br> 
            <!--aqui verificar se a senha foi preenchida corretamente-->
            
            <h3>Perfis:</h3>
            {{perfis}}
            <input type="submit" name="salvar" value="Salvar">
        </form>

        <p id="mensagem-{{retorno}}" >{{mensagem}}</p>

        <div>
            <a href="cUsuario.php"><input type="button" value="Voltar"></a>
        </div>
    </section>
</body>
</html>
