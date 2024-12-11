<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'list_periodo');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}

include_once 'Model/mPeriodo.php';

$men = '';
$idmen = '';

if(isset($_GET['apagar']))
{
    $cod_peri = $_GET['codigo_do_periodo'];
    if (apagar_periodo($cod_peri) === true)
    {
        $men = 'Perido apagado com Sucesso!!';
        $idmen = 'sucesso';


    }

}

$periodo = carrega_periodo();

// Substitui os recursos no template HTML
$periodos = '<tbody>';
foreach ($periodo as $nome)
{
    $periodos = $periodos. '<tr>
        <td>'
            . $nome["nome"].
        '</td>
        <td> #altera </td>                                
        <td> 
            <form action="cPeriodo.php">   
                <input type="hidden" name="codigo_do_periodo" value="' .$nome['codigo'].  '"> 
                <input type="submit" name="apagar" value="Apagar">
            </form> 
        </td>

    </tr>';
    
}
$periodos = $periodos. '<tbody/>';

$html = file_get_contents('View/vPeriodo.php');

$html = str_replace('{{mensagem}}', $men, $html); // mensagem de apagado
$html = str_replace('{{retorno}}', $idmen, $html); // id de estilização da mensagem

$html = str_replace('{{Categoria}}', $periodos, $html);
echo $html; // Exibe o HTML atualizado
?>

