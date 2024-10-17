<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil USuário</title>
</head>
<body>
    <section>
        <h1>Usuario</h1>
        <form>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome"> <br>

            <label for="email">Email:</label>
            <input type="text" name="email" id="email"> <br>

            <label for="senna">Senha:</label>
            <input type="password" name="senha" id="senha"> <br>

            <label for="senna">Confirmar Senha:</label>
            <input type="password" name="conf_senha" id="conf_senha"> <br>

            <h3>Perfis:</h3>
            {{perfis}}
            <input type="submit" value="Salvar">
        </form>
        
                <a href=""></a>

        <div>
            <a href="cUsuario.php"><input type="button" value="Voltar"></a>
        </div>
    </section>
</body>
</html>