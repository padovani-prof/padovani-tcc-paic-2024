<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil USuário</title>
</head>
<body>
    <section>
        <h1>Perfil Usuario</h1>
        <form>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="{{campoNome}}"> <br>

            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" value="{{campoDescricao}}"> <br>

            <h3>Funcionalidades:</h3>
            {{funcionalidades}}
            <input type="submit" value="Salvar">
        </form>
        
        <p id="mensagem-{{retorno}}" >{{mensagem}}</p>

        <div>
            <a href=""><input type="button" value="Voltar"></a>
        </div>
    </section>
</body>
</html>