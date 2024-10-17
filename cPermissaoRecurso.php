<?php 

include_once 'Model/mPermissao.php';


if( isset($_GET['codi_recurso']) and isset($_GET['perfio_usuario']) and isset($_GET['hora_ini']) and isset($_GET['hora_fim']) and isset($_GET['data_ini']))
{
    // salva no banco 
    $codigo = $_GET['codi_recurso'];
    $perfi = $_GET['perfio_usuario'];
    $h_ini = $_GET['hora_ini'];
    $h_fim = $_GET['hora_fim'];
    $dia_semana = [$_GET['domi'], $_GET['segu'],$_GET['terc'],$_GET['quar'],$_GET['quin'],$_GET['sext'],$_GET['saba']] ;
    $d_ini = $_GET['data_ini'];
    $d_fim =  $_GET['data_fim'];

    $d = 's';
    if ($d_fim === '') 
    {
        $d_fim = NULL;
        $d = 'd';
    }
    
    
    cadastra_acesso_recurso($codigo, $perfi, $h_ini, $h_fim, $dia_semana, $d_ini, $d_fim, $d );  
    
}
else
{
    $codigo = $_GET ['codigo'];

    if (isset($_GET['apagar']))
    {
        $codigo = $_GET['codigo_recurso'];
        $acesso_recurso_apaga = $_GET['codigo_acesso_ao_recurso'];
        
        apagar_acesso_ao_recurso($acesso_recurso_apaga);
    }
    
}




$recurso = carrega_recurso($codigo);
$perfil_usu =  carrega_perfil_usuario();
$acessos = carrega_acesso_recurso($codigo);


$usua = '';
$perfil_nomes = [];

foreach ($perfil_usu as $linha)
{
    $usua = $usua. "<option value='".$linha['codigo']."' >".$linha['nome']."</opton>";
    $perfil_nomes[] = $linha['nome'];
}




$informa = '<tbody>';
foreach($acessos as $linha)
{
    $informa = $informa . '<tr>';
    $informa = $informa . '<td> '. $perfil_nomes[$linha['codigo_perfil'] - 1] .'</td>'; // coluna nome

    $informa = $informa . '<td> '.$linha['hr_inicial'] . ' - '. $linha['hr_final'].'</td>'; // coluna horarios

    $informa = $informa . '<td> <form action="cPermissaoRecurso.php">   
                                    <input type="hidden" name="codigo_recurso" value="' .$linha['codigo_recurso'].  '"> 
                                    <input type="hidden" name="codigo_acesso_ao_recurso" value="' .$linha['codigo'].  '"> 
                                    <input type="submit" name="apagar" value="Apagar">
                                    </form> 
                            </td>'; // coluna de ação para apagar
    $informa = $informa . '<tr/>';
    
}
$informa = $informa.'<tbody>';


$html = file_get_contents('View/vPermissao.php');

$html = str_replace('{{nomerecurso}}', $recurso['nome'], $html);
$html = str_replace('{{perfis}}', $usua, $html);

$html = str_replace('{{permissoes}}', $informa, $html);

$html = str_replace('{{codigo_recurso_atual}}', $codigo , $html);

echo $html;







?>


