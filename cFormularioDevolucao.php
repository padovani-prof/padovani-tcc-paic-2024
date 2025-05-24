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



include 'Model/mFormulariorRetirada.php';

$recurso = '';
$devolvente = '';
$devolventes = listar_usuarios();
if (isset($_GET['cancela'])){
    verificação_acesso($_SESSION['codigo_usuario'], 'cancela_devolucao', 2);
    $html = file_get_contents('View/vFormularioDevolucao.php');
    $msg = '';
    $msg_resp = 'danger';

    $recusos_devolvidos = carrega_recurssos_devolvidos();
    $msg = (count($recusos_devolvidos) > 0)?'': 'No momento não possuimos nem um recurso que foi devolvido.';

    if(isset($_GET['btnConfirmar']))
    {
        $recurso = $_GET['recurso'];
        $devolvente = $_GET['devolvente'];
        if($_GET['recurso']!='NULL' and  $_GET['devolvente'] !='NULL'){

            $resposta = verificar_devolucao_usuario($recurso, $devolvente);
            if ($resposta) {
                $msg = 'Devolução Cancelada com Sucesso!!';
                $recurso = '';
                $devolvente = '';
                $msg_resp = 'success';
                $recusos_devolvidos = carrega_recurssos_devolvidos();


            }else{
                $msg = 'Devolução não Encontar. Selecione as informações corretamente.';
            }


        }


    }





    // carrega os dados que foram devolvidos
    
    $recusos_devolvidos = mandar_options($recusos_devolvidos, $recurso);


    $devolventes = mandar_options($devolventes, $devolvente);
    $html = str_replace('{{devolvente}}', $devolventes, $html);


    $html = str_replace( '{{texto}}', 'Devolução', $html);
    $html = str_replace( '{{recursos}}', $recusos_devolvidos, $html);
    $html = str_replace( '{{link}}', 'cFormularioDevolucao.php?', $html);
    $html = str_replace( '{{tela}}', 'Cancelar Devolução', $html);
    $html = str_replace( '{{cancelaRetirada}}', '<input type="hidden" name="cancela" value="true">', $html);
    $recurso = '';
    $devolvente = '';
    $checklistRecurso = '';
    $html = str_replace( '{{mandar}}', 'cFormularioDevolucao.php', $html);
    $html = str_replace( '{{cancelaRetirada}}', '', $html);
    $html = str_replace('{{chechlistRecurso}}', '', $html);
    $html = str_replace('{{retorno}}', $msg_resp, $html);
    $html = str_replace('{{mensagem}}', $msg, $html);
    echo $html;

}else{

    verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);
    $recursos_emprestados = carrega_recursos_emprestados();
    
    $html = file_get_contents('View/vFormularioDevolucao.php');
    $msg = (isset($_GET['msg']))?$_GET['msg']:((count($recursos_emprestados)==0)?'Nomento não temos nem um recurso emprestado.':'');
    $msg_resp = (isset($_GET['msg']))?'success':'danger';

    
    $checklistRecurso = '';
    $html = str_replace( '{{mandar}}', 'cFormularioDevolucao.php', $html);
    $html = str_replace( '{{cancelaRetirada}}', '', $html);

    $html = str_replace( '{{texto}}', 'Cancelar Devolução', $html);
    $html = str_replace( '{{link}}', 'cFormularioDevolucao.php?cancela=true', $html);
    $html = str_replace( '{{tela}}', 'Devolução', $html);
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
            if($verificacao[0] == true)
            {

            include 'Model/mChecklist.php';

            
                if(isset($_GET['dados']) or  count($recursos_emprestados)==0 ){
                    $dados = (isset($_GET['dados']))? $_GET['dados']:[];
                    $id_reserva = $verificacao[1];
                    $id_devolucao = insere_reserva_devolucao($devolvente, $recurso, $data_hora, $hora, "D", $id_reserva);
                    // verificar se todos os checklist foram devolvidos
                    Devolucao_checklist($dados, $id_devolucao); 
                    $msg = 'Recurso devolvido com Sucesso!';
                    header("Location: cFormularioDevolucao.php?msg=$msg");
                    exit();
                }else {
                    
                    $checklistRecurso = carregar_devolução_checklist($recurso, $devolvente);
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


    
    $recursos_emprestados = mandar_options($recursos_emprestados, $recurso);

    $devolventes = mandar_options($devolventes, $devolvente);
    $html = str_replace('{{devolvente}}', $devolventes, $html);
    $html = str_replace('{{recursos}}', $recursos_emprestados, $html);
    $html = str_replace('{{retorno}}', $msg_resp, $html);
    $html = str_replace('{{mensagem}}', $msg, $html);
    $html = str_replace('{{chechlistRecurso}}', $checklistRecurso, $html); 

    echo $html;


}



?>

