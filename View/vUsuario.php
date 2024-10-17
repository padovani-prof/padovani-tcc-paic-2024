<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário</title>
</head>
<body>
    <h1>Usuário</h1>
    <section>
      <table border='1'>
        <tr>
          <th>Nome</th>
          <th>E-mail:</th>
          <th>Alterar</th>
          <th>Apagar</th>
        </tr>
        {{usuarios}}
      </table>

      <div>
          <a href="Menu_Login/Viel/vMenu.php"><button>Voltar</button></a>
      </div>
      <div id="bt-nov-perfil">
          <a href="cFormularioUsuario.php"><button>Novo Usuário</button></a>
      </div>
        
    </section>
</body>
</html>