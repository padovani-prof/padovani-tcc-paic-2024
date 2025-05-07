<?php 


function Tabela_chacklist($dados){
    $aux = "<tr>"; 
    $cont = 1;
    foreach ($dados as $linha) {
        $aux .= '
        <td ><input type="checkbox" name="dados[]" value="'. $linha['codigo'] .'"/>'.$linha['item'].($cont%3==0?'<br>&nbsp;</td></tr>':'<br>&nbsp;</td>');
        $cont ++;
    }
    return $aux;
}




include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);


include 'Model/mFormulariorRetirada.php';
$html = file_get_contents('View/vFormularioDevolucao.php');
$recursos_emprestados = carrega_recursos_emprestados();

$msg = (isset($_GET['msg']))?$_GET['msg']:((count($recursos_emprestados)==0)?'Nomento não temos nem um recurso emprestado.':'');
$msg_resp = (isset($_GET['msg']))?'success':'danger';

$recurso = '';
$devolvente = '';

$checklistRecurso = '';
if(isset($_GET['btnConfirmar']))
{
    $recurso = $_GET['recurso'];
    $devolvente = $_GET['devolvente'];
    if($_GET['recurso']!='NULL' and  $_GET['devolvente'] !='NULL'){
        date_default_timezone_set('America/Manaus'); 
        $data_atual = new DateTime();
        $data_hora = $data_atual->format('Y/m/d H:i:s');
        $hora = $data_atual->format('H:i');
        $verificacao = verificar_usuario_devolucao($recurso, $devolvente);
        if($verificacao == true)
        {
           // devolvido

           include 'Model/mChecklist.php';

           
            if(isset($_GET['dados']) or  count($recursos_emprestados)==0 ){
                $dados = (isset($_GET['dados']))? $_GET['dados']:[];
                $id_devolucao = insere_reserva_devolucao($devolvente, $recurso, $data_hora, $hora, "D");
                // verificar se todos os checklist foram devolvidos
                Devolucao_checklist($dados, $recurso, $id_devolucao);  // não esta inserindo os dados
                $msg = 'Recurso devolvido com Sucesso!';
                header("Location: cFormularioDevolucao.php?msg=$msg");
                exit();
            }else {
                
                $checklistRecurso = carrega_dados($recurso);
                $msg = (count($checklistRecurso)>0)?'Selecione os checklist de recurso que foram devolvidos':'';
                $checklistRecurso = Tabela_chacklist($checklistRecurso);
                // Mostra checklist do recurso para selecionar
                


            }   
        }else
        {
            $msg = 'Devolução inválida, pois o retirante não é a mesma pessoa que está devolvendo.';   
        }
    }
    else {
        $msg = 'Por favor adicione todas as informações.';
    }
}


$devolventes = listar_usuarios();
$recursos_emprestados = mandar_options($recursos_emprestados, $recurso);

$devolventes = mandar_options($devolventes, $devolvente);
$html = str_replace('{{devolvente}}', $devolventes, $html);
$html = str_replace('{{recursos}}', $recursos_emprestados, $html);
$html = str_replace('{{retorno}}', $msg_resp, $html);
$html = str_replace('{{mensagem}}', $msg, $html);
$html = str_replace('{{chechlistRecurso}}', $checklistRecurso, $html); 

echo $html;

?>
