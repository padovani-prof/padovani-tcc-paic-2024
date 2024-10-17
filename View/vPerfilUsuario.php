<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil USuário</title>
</head>
<body>
    <h1>Perfil de Usuário</h1>
    <section>
      <table border='1'>
        <tr>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Alterar</th>
          <th>Apagar</th>
        </tr>
        {{perfis}}
      </table>

      <div>
          <a href="Menu_Login/Viel/vMenu.php"><button>Voltar</button></a>
      </div>
      <div id="bt-nov-perfil">
          <a href="cFormularioPerfil.php"><button>Novo Perfil</button></a>
      </div>
        
    </section>
</body>
</html>