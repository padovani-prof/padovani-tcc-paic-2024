<?php
    include_once 'Model/mPerfilUsuario.php';

    $perfil = listar_perfis();

    if (isset($_GET['codigo_do_perfil'])) {

        $cod_perfil = $_GET['codigo_do_perfil'];
        apagar_perfil($cod_perfil);
    }

    // Verifica se $perfil é um array antes de usar o foreach
    if (is_array($perfil)) {
        
        $perfis = '<tbody>';

     // Gera as linhas da tabela para cada perfil
        foreach ($perfil as $linha) {
            $perfis .= '<tr>
                <td>'.$linha["nome"].'</td>
                <td>'.$linha["descricao"].'</td>
                <td><a href="#">alterar</a></td>
                <td>
                    <form action="cPerfilUsuario.php">   
                        <input type="hidden" name="codigo_do_perfil" value="'. $linha["codigo"] . '"> 
                        <input type="submit" name="apagar" value="Apagar">
                    </form> 
                </td>
            </tr>';
        }

        $perfis .= '</tbody>';
    } else {
        // Caso não haja perfis, exibe uma mensagem apropriada
        $perfis = '<tbody><tr><td colspan="4">Nenhum perfil encontrado.</td></tr></tbody>';
    }

    // Carrega o HTML da view
    $html = file_get_contents('View/vPerfilUsuario.php');

    // Substitui {{perfis}} pelo conteúdo da variável $perfis
    if ($html !== false) {
        $html = str_replace('{{perfis}}', $perfis, $html);
        echo $html;
    } else {
        echo "Erro ao carregar o HTML da página.";
    }
?>
