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
          <th>Operações</th>
        </tr>
        <tbody>
            {{usuarios}}
        </tbody>  
      </table>
        <p id="{{resp}}">{{msg}}</p>

      <a href="cMenu.php"><button>Voltar</button></a>
      
      <div id="bt-nov-perfil">
          <a href="cFormularioUsuario.php"><button>Novo Usuário</button></a>
      </div>
        
    </section>
</body>
</html>
