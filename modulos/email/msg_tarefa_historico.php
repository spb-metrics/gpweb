<?php
$sql = new BDConsulta; 
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);

$sql->adTabela('msg_tarefa_historico');
$sql->adCampo('data, progresso');
$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
$lista = $sql->Lista();
$sql->limpar();

echo '<table rules="ALL" border="1" align="center" cellspacing=0 cellpadding=0 width=200>'; 
echo '<tr><th>Data</th><th>Percentagem</th></tr>';
foreach($lista as $linha) echo '<tr><td>'.retorna_data($linha['data']).'</td><td align="center">'.(int)$linha['progresso'].'</td></tr>';
if (!count($lista)) echo '<tr><td colspan=2>Nenhuma alteração na percentagem foi realizada ainda.</td></tr>';
echo '</table>';
echo sombra_baixo('','200');

?>