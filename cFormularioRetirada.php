<?php

function Tabela_chacklist($dados, $marcado){
    $aux = "<tr>"; 
    $cont = 1;
    foreach ($dados as $linha) {
        $mar =  in_array( $linha['codigo'], $marcado)?' checked ':'';
        $aux .= '
        <td><input '.$mar.' type="checkbox" name="dados[]" value="'. $linha['codigo'] .'">'.$linha['item'].($cont%3==0?'<br>&nbsp;</td></tr>':'<br>&nbsp;</td>');
        $cont ++;
    }
    $aux.='<input type="hidden" name="nada_marcado" value="nada">';
    return $aux;

    
} 





function optios($dados){
    $opt = '<option value="NULL">...</option>';
    foreach($dados as $dado)
    {
        $opt .= '<option title="'.'" value="'. $dado['codigo'].'">'.mb_strtoupper($dado['nome'] ).'</option>';
    }
    return $opt;

}


function data_em_dia_semana($data){
    $dias = [
        'Sunday' => 1,
        'Monday' => 2,
        'Tuesday' => 3,
        'Wednesday' => 4,
        'Thursday' => 5,
        'Friday' => 6,
        'Saturday' => 7
    ];

    $diaSemana = $dias[date('l', strtotime($data))];
    return $diaSemana;
    


}

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();


include 'Model/mFormulariorRetirada.php';
include 'Model/mDisponibilidade.php';
include 'Model/mChecklist.php';



if (isset($_GET['cancela'])){

    verificação_acesso($_SESSION['codigo_usuario'], 'cancela_retirada', 2);
    $checklist = '';
    $recursos_emprestados = carrega_recursos_emprestados();
    $usuarios = listar_usuarios();
    $tela = 'Cancelar Retirada';
    $id_msg = 'danger'; 
    $msg = (count($recursos_emprestados)>0)?'Selecione o recurso e o usuario que emprestou para cancelar a retirda':'Nomento não temos nem um recurso Retirado.';
    $recurso = '';
    $devolvente = '';

    if(isset($_GET['btnConfirmar'])){
        $recurso = $_GET['recurso'];
        $devolvente = $_GET['devolvente'];
        if($_GET['recurso']!='NULL' and  $_GET['devolvente'] !='NULL'){
            $resposta = cancelaRetirada($recurso, $devolvente);
            if ($resposta){
                $msg = 'Retirada Cancelada com Sucesso!';
                $id_msg = 'success';
                $recursos_emprestados = carrega_recursos_emprestados();
                $recurso = '';
                $devolvente = '';
            }else{
                $msg = 'Retirada não Encontrada. Selecione as Informações da Retirada Corretamente.';
            }

        }else{
            $msg = 'Selecione Todas as Informações.';
        }

    }

    

    

    $recursos_emprestados =  mandar_options($recursos_emprestados, $recurso);
    $usuarios = mandar_options($usuarios, $devolvente);


    $html = file_get_contents('View/vFormularioDevolucao.php');

    $html = str_replace( 'Devolvente: ', 'Retirante: ', $html);

    $html = str_replace( '{{tela}}', $tela, $html);
    $html = str_replace( '{{mensagem}}', $msg, $html);
    $html = str_replace( '{{retorno}}', $id_msg, $html);
    $html = str_replace( '{{chechlistRecurso}}', $checklist, $html);
    $html = str_replace( '{{texto}}', 'Retirada', $html);
    $html = str_replace( '{{link}}', 'cFormularioRetirada.php', $html);
    $html = str_replace( '{{recursos}}', $recursos_emprestados, $html);
    $html = str_replace( '{{devolvente}}', $usuarios, $html);
    $html = str_replace( '{{mandar}}', 'cFormularioRetirada.php', $html);
    $html = str_replace( '{{cancelaRetirada}}', '<input type="hidden" name="cancela" value="true">', $html);
    $html = cabecalho($html, 'Cancelar Retirada');
    echo $html;

}else{
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);
    $html = file_get_contents('View/vFormulariorRetirada.php');
    $senha_usuario = '';
    $msg = '';
    $id_msg = 'danger'; 
    $marca_recu = '';
    $marca_reti = '';
    $mar_hora = '';
    $checklistRecurso = '';
    if(isset($_POST['btnConfirmar']))
    {
        $recurso = $_POST['recurso'];
        $retirante = $_POST['retirante'];
        $hora_fim = $_POST['hora_final'];
        $marca_recu = $recurso;
        $marca_reti = $retirante;
        $mar_hora = $hora_fim;

        date_default_timezone_set('America/Manaus'); 
        $data_atual = new DateTime();

        $data = new DateTime();
        $data = $data->format('Y/m/d');
        
        $data_devolução = new DateTime("$data $hora_fim");

        // dados validos

        if(mb_strlen($hora_fim)==5  and $data_atual < $data_devolução and $recurso != 'NULL' and $retirante != 'NULL')
        {
            $hora_ini =  $data_atual->format('H:i:s');
            $hora_fim = $hora_fim.':00';
            $tem_permição =  verificar_permicao_recurso($data, $hora_ini, $hora_fim, $recurso, $retirante, data_em_dia_semana($data));
            // verificar permição
            if ($tem_permição){
                    // verificar se o recurso não está reservado
                $disponives = Disponibilidade([$data, $hora_ini, $hora_fim], [], [$recurso]);
                $disponives = count($disponives)> 0;
                $sua_reserva = verificar_reserva_do_retirante([$data, $hora_ini, $hora_fim], $retirante, $recurso);
                if ($disponives or $sua_reserva)
                {
                    $data_hora = $data.' '.$hora_ini;
                    $checklistRecurso = carrega_dados($recurso);
                    $checklistMarcado = isset($_POST['dados'])?$_POST['dados']:[]; // dados selecionados

                    
                    $msg = (count($checklistRecurso)>0)?'Selecione os checklist de recurso apenas que vai ser retirado e ':'';
                    $msg .= 'Solicite ao Retirante que insira sua senha';
                    
                    $focar_senha = '';
                    $confimar_senha = false;
                    if (isset($_POST['senha_usuario'])){
                        $focar_senha = ' autofocus ';
                        $senha_usuario = hash('sha256', $_POST['senha_usuario']);
                        $confimar_senha = Confirmar_usuario_retirada($retirante, $senha_usuario); // local onde veririfara sem tem essa login e  senha
                        $msg = $confimar_senha?'':'Senha inválida. Digite novamente.';
                        
                    }
                    if((isset($_POST['nada_marcado']) or count($checklistRecurso)==0) and $confimar_senha){
                        $senha_usuario = '';
                        $id_reseva = ($disponives)? criar_reserva_retirada($retirante, $recurso, $data, $hora_ini, $hora_fim): null;
                        $checklistRecurso = $checklistRecurso;
                        //fazer a reserva de checlist
                        $id_retirada = insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim,'R', $id_reseva);
                        retirada_checklist($checklistRecurso,  $checklistMarcado, $id_retirada);
                        $msg = 'Recurso retirado com Sucesso!';
                        $id_msg = 'success'; 
                        $marca_recu = '';
                        $marca_reti = '';
                        $mar_hora = '';
                        $checklistRecurso = '';
                        

                    }
                    else
                    {

                        // deixar marcado os checklistes caso eles tenha errado a senha

                    
                    $checklistRecurso = Tabela_chacklist($checklistRecurso, $checklistMarcado);   
                    $senha_usuario = '<label for="">Senha do Retirante</label>
                                    <input type="password" name="senha_usuario"'.$focar_senha.'>';

                    }  
                }
                else{
                    $msg = 'Esse recurso está reservado para outro úsuario.';

                }

            }else{
                $msg = 'O Retirante não possui permição para retirar esse recurso.';
            }

            
            
            
        }else{
            
            $msg = (mb_strlen($hora_fim)!=5  or $recurso == 'NULL' or $retirante == 'NULL')?'Por favor preenxa todos os dados. ':' Horário de devolução ínvalido.'; 
            
        }
    
        
    }




    $recursos_reserva = carrega_retirada_disponivel();
    $retirantes = listar_usuarios();

    $opicoes_recurso = mandar_options($recursos_reserva, $marca_recu);
    $opicoes_retirantes = mandar_options($retirantes, $marca_reti);


    $html = str_replace('{{chechlistRecurso}}', $checklistRecurso, $html); 
    $html = cabecalho($html, 'Retirada');
    $html = str_replace('{{retirante}}', $opicoes_retirantes, $html);
    $html = str_replace('{{recursos}}',$opicoes_recurso , $html);
    $html = str_replace('{{mensagem}}',$msg, $html);
    $html = str_replace('{{retorno}}', $id_msg, $html);
    $html = str_replace('{{hora_fim}}',$mar_hora, $html);
    $html = str_replace('{{senha_usuario}}',$senha_usuario, $html);



    echo $html;

}

?>



