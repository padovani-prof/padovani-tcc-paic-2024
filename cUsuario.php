<?php
    include_once 'Model/mUsuario.php';

    $usuario = listar_usuarios();

    // Verifica se $perfil é um array antes de usar o foreach
    if (is_array($usuario)) {
        
        $usuarios = '<tbody>';

     // Gera as linhas da tabela para cada perfil
        foreach ($usuario as $linha) {
            $usuarios .= '<tr>
                <td>'.$linha["nome"].'</td>
                <td>'.$linha["email"].'</td>
                <td><a href="#">alterar</a></td>
                <td><a href="#">apagar</a></td>
            </tr>';
        }

        $usuarios .= '</tbody>';
    } else {
        // Caso não haja perfis, exibe uma mensagem apropriada
        $usuarios = '<tbody><tr><td colspan="4">Nenhum perfil encontrado.</td></tr></tbody>';
    }

    // Carrega o HTML da view
    $html = file_get_contents('View/vUsuario.php');

    // Substitui {{perfis}} pelo conteúdo da variável $perfis
    if ($html !== false) {
        $html = str_replace('{{usuarios}}', $usuarios, $html);
        echo $html;
    } else {
        echo "Erro ao carregar o HTML da página.";
    }
?>
