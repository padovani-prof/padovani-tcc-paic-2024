<?php 

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mVerificacao_acesso.php';
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'adm_perm_recurso');
if ($verificar== false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}




include_once 'Model/mPermissao.php';
$html = file_get_contents('View/vPermissao.php');

$perfi = '';
$mensAnom = '';


if (isset($_GET['salvar']))
{
    $dia_semana = [$_GET['domi'], $_GET['segu'],$_GET['terc'],$_GET['quar'],$_GET['quin'],$_GET['sext'],$_GET['saba']] ;

   
    $dia_semana =  dias_da_semana($dia_semana);


    $codigo =  $_GET['codi_recurso'];
    $perfi = $_GET['perfio_usuario'];
    $h_ini = $_GET['hora_ini'];
    $h_fim = $_GET['hora_fim'];
    $d_ini = $_GET['data_ini'];
    $d_fim =  $_GET['data_fim'];


    if((isset($_GET['codi_recurso'])) and isset($_GET['perfio_usuario']) and (!$_GET['hora_ini'] == '') and (!$_GET['hora_fim']== '') and (!$_GET['data_ini']== '' and (!$dia_semana == '')))
    {
        // salva no banco 
        $d = 's';
        if ($d_fim === '') 
        {
            $d_fim = NULL;
            $d = 'd';
        }
        
        
        $mensAnom = 'Permissão de recurso salvo com Sucesso!!'; // salvo co Sucesso
        cadastra_acesso_recurso($codigo, $perfi, $h_ini, $h_fim,$dia_semana, $d_ini, $d_fim, $d );
        $perfi = '';  
        
    }
    else
    {
        // Deixou algun campo vazio
    
        $semana = str_split($dia_semana);

        $cont = 1;
        foreach ($semana as $dia) 
        {
            if($dia=='S')
            {
                $html = str_replace("{{marca$cont}}", ' checked', $html);
            }
            $cont ++;
           
        }

        $mensAnom = 'Voçê não preecheu algun dos campos necessarios';

        $html = str_replace('{{horaInicial}}', $h_ini, $html);
        $html = str_replace('{{horaFinal}}}', $h_fim, $html);
        $html = str_replace('{{dataIni}}}', $d_ini, $html);
        $html = str_replace('{{dataFinal}}}', $d_fim, $html);

        
        

        
    }
}

else
{
    //Açessou  Permissão de Recurso pela primeira / vai apagar

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
    


    $usua = $usua. '<option value="' .$linha['codigo'].'"' . ($linha['codigo'] ==  $perfi? ' selected' : '') . '> '.$linha['nome'].'</option>';
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



$html = str_replace('{{mensagemAnomalia}}', $mensAnom, $html);

$html = str_replace('{{nomerecurso}}', $recurso['nome'], $html);
$html = str_replace('{{perfis}}', $usua, $html);

$html = str_replace('{{permissoes}}', $informa, $html);

$html = str_replace('{{codigo_recurso_atual}}', $codigo , $html);

echo $html;







?>


