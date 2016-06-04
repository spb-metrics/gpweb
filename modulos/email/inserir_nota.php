<?php
$sql = new BDConsulta;

$Aplic->carregarCKEditorJS();

$vetor_cor=array('FFFAFA', 'F8F8FF', 'F5F5F5', 'DCDCDC', 'FFFAF0', 'FDF5E6', 'FAF0E6', 'FAEBD7', 'FFEFD5', 'FFEBCD', 'FFE4C4', 'FFDAB9', 'FFDEAD', 'FFE4B5', 'FFF8DC', 'FFFFF0', 'FFFACD', 'FFF5EE', 'F0FFF0', 'F5FFFA', 'F0FFFF', 'F0F8FF', 'E6E6FA', 'FFF0F5', 'FFE4E1', 'FFFFFF', '000000', '2F4F4F', '696969', '708090', '778899', 'BEBEBE', 'D3D3D3', '191970', '000080', '6495ED', '483D8B', '6A5ACD', '7B68EE', '8470FF', '0000CD', '4169E1', '0000FF', '1E90FF', '00BFFF', '87CEEB', '87CEFA', '4682B4', 'B0C4DE', 'ADD8E6', 'B0E0E6', 'AFEEEE', '00CED1', '48D1CC', '40E0D0', '00FFFF', 'E0FFFF', '5F9EA0', '66CDAA', '7FFFD4', '006400', '556B2F', '8FBC8F', '2E8B57', '3CB371', '20B2AA', '98FB98', '00FF7F', '7CFC00', '00FF00', '7FFF00', '00FA9A', 'ADFF2F', '32CD32', '9ACD32', '228B22', '6B8E23', 'BDB76B', 'EEE8AA', 'FAFAD2', 'FFFFE0', 'FFFF00', 'FFD700', 'EEDD82', 'DAA520', 'B8860B', 'BC8F8F', 'CD5C5C', '8B4513', 'A0522D', 'CD853F', 'DEB887', 'F5F5DC', 'F5DEB3', 'F4A460', 'D2B48C', 'D2691E', 'B22222', 'A52A2A', 'E9967A', 'FA8072', 'FFA07A', 'FFA500', 'FF8C00', 'FF7F50', 'F08080', 'FF6347', 'FF4500', 'FF0000', 'FF69B4', 'FF1493', 'FFC0CB', 'FFB6C1', 'DB7093', 'B03060', 'C71585', 'D02090', 'FF00FF', 'EE82EE', 'DDA0DD', 'DA70D6', 'BA55D3', '9932CC', '9400D3', '8A2BE2', 'A020F0', '9370DB', 'D8BFD8', 'FFFAFA', 'EEE9E9', 'CDC9C9', '8B8989', 'FFF5EE', 'EEE5DE', 'CDC5BF', '8B8682', '8B8682', 'EEDFCC', 'CDC0B0', '8B8378', 'FFE4C4', 'EED5B7', 'CDB79E', '8B7D6B', 'FFDAB9', 'EECBAD', 'CDAF95', '8B7765', 'FFDEAD', 'EECFA1', 'CDB38B', '8B795E', 'FFFACD', 'EEE9BF', 'CDC9A5', '8B8970', 'FFF8DC', 'EEE8CD', 'CDC8B1', '8B8878', 'FFFFF0', 'EEEEE0', 'CDCDC1', '8B8B83', 'F0FFF0', 'E0EEE0', 'C1CDC1', '838B83', 'FFF0F5', 'EEE0E5', 'CDC1C5', '8B8386', 'FFE4E1', 'EED5D2', 'CDB7B5', '8B7D7B', 'F0FFFF', 'E0EEEE', 'C1CDCD', '838B8B', '836FFF', '7A67EE', '6959CD', '473C8B', '4876FF', '436EEE', '3A5FCD', '27408B', '0000FF', '0000EE', '0000CD', '00008B', '1E90FF', '1C86EE', '1874CD', '104E8B', '63B8FF', '5CACEE', '4F94CD', '36648B', '00BFFF', '00B2EE', '009ACD', '00688B', '87CEFF', '7EC0EE', '6CA6CD', '4A708B', 'B0E2FF', 'A4D3EE', '8DB6CD', '607B8B', 'C6E2FF', 'B9D3EE', '9FB6CD', '6C7B8B', 'CAE1FF', 'BCD2EE', 'A2B5CD', '6E7B8B', 'E6E6E6', 'B2DFEE', '9AC0CD', '68838B', 'E0FFFF', 'D1EEEE', 'B4CDCD', '7A8B8B', 'BBFFFF', 'AEEEEE', '96CDCD', '668B8B', '98F5FF', '8EE5EE', '7AC5CD', '53868B', '00F5FF', '00E5EE', '00C5CD', '00868B', '00FFFF', '00EEEE', '00CDCD', '008B8B', '97FFFF', '8DEEEE', '79CDCD', '528B8B', '7FFFD4', '76EEC6', '66CDAA', '458B74', 'C1FFC1', 'B4EEB4', '9BCD9B', '698B69', '54FF9F', '4EEE94', '43CD80', '2E8B57', '9AFF9A', '90EE90', '7CCD7C', '548B54', '00FF7F', '00EE76', '00CD66', '008B45', '00FF00', '00EE00', '00CD00', '008B00', '7FFF00', '76EE00', '66CD00', '458B00', 'C0FF3E', 'B3EE3A', '9ACD32', '698B22', 'CAFF70', 'BCEE68', 'A2CD5A', '6E8B3D', 'FFF68F', 'EEE685', 'CDC673', '8B864E', 'FFEC8B', 'EEDC82', 'CDBE70', '8B814C', 'FFFFE0', 'EEEED1', 'CDCDB4', '8B8B7A', 'FFFF00', 'EEEE00', 'CDCD00', '8B8B00', 'FFD700', 'EEC900', 'CDAD00', '8B7500', 'FFC125', 'EEB422', 'CD9B1D', '8B6914', 'FFB90F', 'EEAD0E', 'CD950C', 'FFC1C1', 'EEB4B4', 'CD9B9B', '8B6969', 'FF6A6A', 'EE6363', 'CD5555', '8B3A3A', 'FF8247', 'EE7942', 'CD6839', '8B4726', 'FFD39B', 'EEC591', 'CDAA7D', '8B7355', 'FFE7BA', 'EED8AE', 'CDBA96', '8B7E66', 'FFA54F', 'EE9A49', 'CD853F', '8B5A2B', 'FF7F24', 'EE7621', 'CD661D', '8B4513', 'FF3030', 'EE2C2C', 'CD2626', '8B1A1A', 'FF4040', 'EE3B3B', 'CD3333', '8B2323', 'FF8C69', 'EE8262', 'CD7054', '8B4C39', 'FFA07A', 'EE9572', 'CD8162', '8B5742', 'FFA500', 'EE9A00', 'CD8500', '8B5A00', 'FF7F00', 'EE7600', 'CD6600', '8B4500', 'FF7256', 'EE6A50', 'CD5B45', '8B3E2F', 'FF6347', 'EE5C42', 'CD4F39', '8B3626', 'FF4500', 'EE4000', 'CD3700', '8B2500', 'FF0000', 'EE0000', 'CD0000', '8B0000', 'FF1493', 'EE1289', 'CD1076', '8B0A50', 'FF6EB4', 'EE6AA7', 'CD6090', '8B3A62', 'FFB5C5', 'EEA9B8', 'CD919E', '8B636C', 'FFAEB9', 'EEA2AD', 'CD8C95', '8B5F65', 'FF82AB', 'EE799F', 'CD6889', '8B475D', 'FF34B3', 'EE30A7', 'CD2990', '8B1C62', 'FF3E96', 'EE3A8C', 'CD3278', '8B2252', 'FF00FF', 'EE00EE', 'CD00CD', '8B008B', 'FF83FA', 'EE7AE9', 'CD69C9', '8B4789', 'FFBBFF', 'EEAEEE', 'CD96CD', '8B668B', 'E066FF', 'D15FEE', 'B452CD', '7A378B', 'BF3EFF', 'B23AEE', '9A32CD', '68228B', '9B30FF', '912CEE', '7D26CD', '551A8B', 'AB82FF', '9F79EE', '8968CD', '5D478B', 'FFE1FF', 'EED2EE', 'CDB5CD', '8B7B8B', '1C1C1C', '363636', '4F4F4F', '696969', '828282', '9C9C9C', 'B5B5B5', 'CFCFCF', 'E8E8E8', 'A9A9A9', '00008B', '008B8B', '8B008B', '8B0000', '90EE90');

$pasta=getParam($_REQUEST, 'pasta', null);
$ordem=getParam($_REQUEST, 'ordem', 0);
$pesquisa=getParam($_REQUEST, 'pesquisa', '');
$status=getParam($_REQUEST, 'status', 1);
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', null);
$cot_fim=getParam($_REQUEST, 'cot_fim', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$cor=getParam($_REQUEST, 'cor', 0);
$texto=getParam($_REQUEST, 'texto', '');
$status=getParam($_REQUEST, 'status', 0);

if ($alterar){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('nota', $texto);
	$sql->adAtualizar('cor', $cor);
	$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela msg_usuario!'.$bd->stderr(true));
	$sql->limpar();
	}

if ($excluir){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('nota', '');
	$sql->adAtualizar('cor', '');
	$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela msg_usuario!'.$bd->stderr(true));
	$sql->limpar();
	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="inserir_nota">';
echo '<input type=hidden name="m" id="m" value="email">';
echo '<input type=hidden name="cot_fim" id="cot_fim" value="'.$cot_fim.'">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden name="ordem" id="ordem" value="'.$ordem.'">';
echo '<input type=hidden name="pesquisa" id="pesquisa" value="'.$pesquisa.'">';
echo '<input type=hidden name="status" id="status" value="'.$status.'">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden name="cor" id="cor" value="'.$cor.'">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="excluir" id="excluir" value="">';

if ($excluir || $alterar) echo '<script>env.a.value="lista_msg"; env.submit();</script>';

$sql->adTabela('msg_usuario');
$sql->adCampo('cor, nota, msg_id');
$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
$linha = $sql->Linha();
$sql->limpar();





echo estiloTopoCaixa(770);
echo '<table width="770" class="std" align="center" border=0 cellspacing=0 cellpadding=0 >';
echo '<tr><td align="center"><h1>Nota n'.$config['genero_mensagem'].' '.$config['mensagem'].' Nº '.$linha['msg_id'].'<h1></td></tr>';
echo '<tr><td bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:767px;">'.$linha['nota'].'</textarea></td></tr>';
echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Cor','Escolha a cor de fundo desta nota.<br><br>Na tela principal de exibição da lista de '.$config['mensagens'].' é possível ordenar '.$config['genero_mensagem'].'s mesm'.$config['genero_mensagem'].'s pelas cores').'<b>Cor: </b>'.dicaF();
	echo '<select id="cor" name="cor" style="background-color: #FFFFFF; width:60pt; font-size:8pt; font-family: verdana, arial, helvetica, sans-serif; padding-left: 5px; padding-right: 5px;" class=text size=1 onchange="env.cor.bgcolor=\'FF00FF\'">';
	foreach($vetor_cor as $chave => $valor)	echo '<option style="background-color: #'.$valor.($valor=='000000'  || $valor=='1C1C1C'? '; color:#FFFFFF' : '').';" value="'.$valor.'" '.($valor==$linha['cor'] ? 'selectED' : '').' >'.$valor.'</option>';
	echo '</select>';
echo '</td></tr>';


echo '<tr><td align="center"><table><tr>';
echo '<td>'.dica("Cancelar","Clique neste botão para cancelar esta operação.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'lista_msg\'; env.submit();"><span><b>cancelar</b></span></a>'.dicaF().'</td>';
if (strlen($linha['nota'])) echo '<td>'.dica('Alterar','Clique neste botão para alterar uma nota relativa a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a class="botao" href="javascript:void(0);" onclick="inserir_alterar();"><span><b>alterar</b></span></a>'.dicaF().'</td>';
else echo '<td>'.dica('Inserir','Clique neste botão para inserir uma nota relativa a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a class="botao" href="javascript:void(0);" onclick="inserir_alterar();"><span><b>inserir</b></span></a>'.dicaF().'</td>';
if (strlen($linha['nota'])) echo '<td>'.dica("Excluir","Clique neste botão para excluir esta anotação.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.submit();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
echo '</tr></table>';

echo '</table></form>';
echo estiloFundoCaixa(770);

?>

<script LANGUAGE="javascript">
function tem_conteudo(){
	var editorcontent = CKEDITOR.instances['texto'].getData().replace(/<[^>]*>/gi, '');
  return (editorcontent.length > 0);
	}
function inserir_alterar(){
	if (tem_conteudo()){
		env.alterar.value=1;
		env.submit();
		}
	else alert ("Digite um texto para este modelo!");
	}
</script>
