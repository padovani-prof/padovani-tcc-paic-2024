<?php

function percorrer_dados_tabela($dados, $recurso){

    $tabela = '';
    foreach ($dados as $linha){        
        $todos_dados = [];
        $data = $linha['data_retira'];
        $usuario = $linha['nome'];
        $cod_usuario = $linha['cod_usuario'];
        $hora_retira = $linha['hora_retira'];
        $codigo_retirada = $linha['codigo'];
        $reseva = $linha['codigo_reserva'];
        $codigo_devolucao = null;
        $hora_devolu = $linha['hora_final'];
        $dados_devolvido = tem_devolucao($recurso, $data, $cod_usuario, $hora_retira, $reseva);   
        if (count($dados_devolvido) > 0){
            $hora_devolu = $dados_devolvido[0]['hora_devolu'];
            $codigo_devolucao = $dados_devolvido[0]['codigo'];
            
        }
        $todos_dados[] = $data;
        $todos_dados[] = $usuario;
        $todos_dados[] = $hora_retira;
        $todos_dados[] = $hora_devolu;
        $todos_dados[] = $codigo_retirada;
        $todos_dados[] = $codigo_devolucao;
        $todos_dados[] = $recurso;



        $linck = '<form action="cHistoricoRetiradaRecurso.php">
                    <input type="hidden" name="dados" value="'.urlencode(json_encode($todos_dados)).'">
                    <input type="submit" name="historico_checlist" value="Ver checklist">
                </form>';

        $tabela.="<tr>";
        $tabela.= "<td>  $data  </td>";
        $tabela.= "<td> $usuario  </td>";
        $tabela.= "<td> $hora_retira  </td>";
        $tabela.= "<td> $hora_devolu  </td>";
        $tabela.= "<td> $linck  </td>";
        $tabela.='</tr>';



    }


    return $tabela;
}


function foi_devolvido($item, $acessorio_devolvidos){

    $resposta = false;
    $t = count($acessorio_devolvidos);


    
    for ($i=0; $i < $t; $i++) {
        if($item == $acessorio_devolvidos[$i]['codigo_checklist']){
            $resposta = true;
            unset($acessorio_devolvidos[$i]);
            $acessorio_devolvidos = array_values($acessorio_devolvidos);


            break;

        }

    }
    return [$resposta, $acessorio_devolvidos];





}


function dados_tabela($acessorio_retiradaos, $acessorio_devolvidos){

    $tabel = '';

    foreach($acessorio_retiradaos as $retirado){

        $item = $retirado['item'];
        $cod_item = $retirado['codigo_checklist'];
        $resposta = foi_devolvido($cod_item, $acessorio_devolvidos);
        $acessorio_devolvidos = $resposta[1];
        $retira_devo = ($resposta[0])?'Devolvido':'Não Devolvido';

        $tabel.='<tr>';
        $tabel.="<td>$item</td>";
        $tabel.="<td>$retira_devo</td>";

        $tabel.='</tr>';

    }
    return $tabel;



}







include 'Model/mHistoricoRetiradaRecuso.php';

if (isset($_GET['codigo_recurso'])){
    $html = file_get_contents('View/vHistoricoRetiradaRecurso.php');
    $recurso = $_GET['codigo_recurso'];
    $dados = dados_retirada($recurso);
    $dados_tabela = percorrer_dados_tabela($dados, $recurso);
    $html = str_replace('{{historico_recurso}}', $dados_tabela, $html);
    echo $html;

}elseif (isset($_GET['dados'])) {
    $dados = json_decode(urldecode($_GET['dados']));
    $cod_retirada = $dados[4];
    $cod_devolucao = $dados[5];
    $recuso = $dados[6];
    $acessorio_retiradaos = carregar_checlist_retirado($cod_retirada);
    $acessorio_devolvidos = carregar_checlist_devolvido($cod_devolucao);
    $tabela = dados_tabela($acessorio_retiradaos, $acessorio_devolvidos);
    $html = file_get_contents('View/vHistoricoChecklistRecuso.php');
    $html = str_replace('{{data}}',$dados[0] ,$html);
    $html = str_replace('{{usuario}}',$dados[1] ,$html);
    $html = str_replace('{{hora_retirada}}',$dados[2] ,$html);
    $html = str_replace('{{hora_devolucao}}',$dados[3] ,$html);
    $html = str_replace('{{cod}}',$recuso,$html);
    $html = str_replace('{{historico_checklist}}',$tabela ,$html);
    echo $html;
    
}else{
    header('Location: cRecursos.php');
    exit();
}

?>