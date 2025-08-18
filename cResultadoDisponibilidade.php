<?php 

// =========================
// FUNÇÕES AUXILIARES
// =========================


function chaves($lista){
    return array_map(fn($item) => explode(',', $item)[0], $lista);
}

function ta_livre($codigo, $data, $h_ini, $h_fim, $disponives){
    foreach($disponives as $livre){
        if($livre['codigo_recurso'] === $codigo 
           && trim($livre["data_alvo"]) === trim($data) 
           && trim($livre['hora_inicial_alvo']) === trim($h_ini) 
           && trim($livre['hora_final_alvo']) === trim($h_fim)){
            return true;
        }
    }
    return false;
}

function mandar_hindem($lista, $name, $mas=false, $dado=''){
    $inpu = "<input type='hidden' name='$name' value='". urlencode(json_encode($lista))."'>";
    if($mas){
        $inpu .= '<input type="hidden" name="utilizador" value="'.$dado.'">';
    }
    return $inpu;
}

// =========================
// INCLUSÃO DE MODELOS E VERIFICAÇÃO DE ACESSO
// =========================
include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);

include_once 'Model/mDisponibilidade.php';

// =========================
// CARREGAMENTO DE VARIÁVEIS
// =========================
$html = file_get_contents('View/vResultadoDisponibilidade.php');
$msg = '';
$id_msg = 'danger';

$utilizador = $_GET['utilizador'] ?? '';
$categorias = isset($_GET['categorias']) ? json_decode(urldecode($_GET['categorias'])) : [];
$recursos = isset($_GET['recursos']) ? json_decode(urldecode($_GET['recursos'])) : [];
$periodos = isset($_GET['periodos']) ? json_decode(urldecode($_GET['periodos'])) : [];

// Para manter os dados no link de retorno
$todos_os_dados = "utilizador=$utilizador&periodo=" . urlencode(json_encode($periodos))
    . ((count($recursos) > 0) ? "&cate_recu=" . urlencode(json_encode($recursos)) : '')
    . ((count($categorias) > 0) ? "&cate_recu=" . urlencode(json_encode($categorias)) : '');

// =========================
// REDIRECIONAMENTO PARA RESERVA
// =========================
if(isset($_GET['reserva']) && isset($_GET['marcas'])){
    $dados = $_GET['marcas'];
    header("Location: cReservaConjunta.php?marcas=".urlencode(json_encode($dados))."&utilizador=$utilizador");
    exit();
}elseif(isset($_GET['reserva'])){
    $msg = 'Por favor, selecione algum recurso disponível antes de prosseguir.';
}

// =========================
// HIDDEN INPUTS
// =========================
$hid_recu = mandar_hindem($recursos, 'recursos');
$hid_cate = mandar_hindem($periodos, 'periodos');
$hid_peri = mandar_hindem($categorias, 'categorias', true, $utilizador);

// =========================
// PROCESSAMENTO DE DISPONIBILIDADE
// =========================
$chaves_cate = chaves($categorias);
$chaves_recursos = chaves($recursos);
$disponives = Disponibilidade($periodos, $chaves_cate, $chaves_recursos);
$recurso_catego = adicionados($chaves_recursos, $chaves_cate);

// Cria as colunas da tabela
$coluna = '';
$periodos_count = count($periodos) / 3; // cada período tem 3 valores: data, hora_ini, hora_fim
for($i = 0; $i < count($periodos); $i += 3){
    $data = explode('-', $periodos[$i]);
    $coluna .= '<th>'.$data[2].'/'.$data[1].'/'.$data[0].' das '.$periodos[$i + 1].' às '.$periodos[$i + 2].'</th>';
}

// Cria as linhas da tabela
$recurs_dados = '';
$total_celulas = count($recurso_catego) * $periodos_count;
$qdt_ind = 0;

foreach($recurso_catego as $recurso){
    $recurs_dados .= '<tr><td>'.$recurso['nome_recurso'].'</td>';
    for($d = 0; $d < count($periodos); $d += 3){
        $livre = ta_livre($recurso['codigo_recurso'], $periodos[$d], $periodos[$d+1].':00', $periodos[$d+2].':00', $disponives);
        $permitido = verificar_permicao_recurso($periodos[$d], $periodos[$d+1], $periodos[$d+2], $recurso['codigo_recurso'], $utilizador, data_em_dia_semana($periodos[$d]));
        if($livre && $permitido){
            $recurs_dados .= '<td><label><input type="checkbox" name="marcas[]" value="'.$recurso['codigo_recurso'].','.$recurso['nome_recurso'].','.$periodos[$d].','.$periodos[$d+1].','.$periodos[$d+2].'"></label></td>';
        }else{
            $recurs_dados .= '<td><span title="'.(($livre && !$permitido)?'O usuário não possui permissão para este recurso neste período.':'Recurso já reservado.').'">X</span></td>';
            $qdt_ind++;
        }
    }
    $recurs_dados .= '</tr>';
}

// Mensagem formal se todos indisponíveis
if($total_celulas == $qdt_ind){
    $msg = (count($recurso_catego) > 1 ? 'Todos os recursos estão indisponíveis' : 'O recurso está indisponível') .
           ((count($periodos) > 3) ? ' para estes períodos.' : ' para este período.') .
           ' Por favor, retorne e selecione outros períodos ou recursos.';
}

// =========================
// SUBSTITUIÇÃO NO TEMPLATE
// =========================
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{cate}}', $hid_cate, $html);
$html = str_replace('{{recu}}', $hid_recu, $html);
$html = str_replace('{{periodo}}', $hid_peri, $html);
$html = str_replace('{{Colunas}}', $coluna, $html);
$html = str_replace('{{Disponibilidades}}', $recurs_dados, $html);
$html = str_replace('{{informa}}', $todos_os_dados, $html);

$html = cabecalho($html, 'Disponibilidade');
$html = str_replace('{{retorno}}', $id_msg, $html);

echo $html;

?>
