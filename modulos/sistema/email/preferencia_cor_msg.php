<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);

$cor_fundo=getParam($_REQUEST, 'cor_fundo', '');
$cor_msg=getParam($_REQUEST, 'cor_msg', '');
$cor_anexo=getParam($_REQUEST, 'cor_anexo', '');
$cor_despacho=getParam($_REQUEST, 'cor_despacho', '');
$cor_resposta=getParam($_REQUEST, 'cor_resposta', '');
$cor_anotacao=getParam($_REQUEST, 'cor_anotacao', '');
$cor_encamihamentos=getParam($_REQUEST, 'cor_encamihamentos', '');

$cor_msg_nao_lida=getParam($_REQUEST, 'cor_msg_nao_lida', '');
$cor_msg_realce=getParam($_REQUEST, 'cor_msg_realce', '');
$cor_referenciado=getParam($_REQUEST, 'cor_referenciado', '');
$cor_referencia=getParam($_REQUEST, 'cor_referencia', '');
$sql = new BDConsulta;

if ($cor_fundo){
	//verificar se já foi inserido na tabela preferencia o usuario
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('count(usuario_id)');
	if($usuario_id){
        $sql->adOnde('usuario_id='.$usuario_id);
        }
    else{
        $sql->adOnde('usuario_id IS NULL or usuario_id = 0');
        }
	$ja_inserido = $sql->Resultado();
	$sql->Limpar();
	if(!empty($ja_inserido)){
		$sql->adTabela('preferencia_cor');
		$sql->adAtualizar('cor_fundo', $cor_fundo);
		$sql->adAtualizar('cor_msg', $cor_msg);
		$sql->adAtualizar('cor_anexo', $cor_anexo);
		$sql->adAtualizar('cor_despacho', $cor_despacho);
		$sql->adAtualizar('cor_resposta', $cor_resposta);
		$sql->adAtualizar('cor_anotacao', $cor_anotacao);
		$sql->adAtualizar('cor_encamihamentos', $cor_encamihamentos);
		$sql->adAtualizar('cor_msg_nao_lida', $cor_msg_nao_lida);
		$sql->adAtualizar('cor_msg_realce', $cor_msg_realce);
		$sql->adAtualizar('cor_referencia', $cor_referencia);
		$sql->adAtualizar('cor_referenciado', $cor_referenciado);
		if($usuario_id) $sql->adOnde('usuario_id='.$usuario_id);
        else $sql->adOnde('usuario_id IS NULL or usuario_id = 0');
		if (!$sql->exec()) die('Não foi possivel alterar os valores das cores da tabela preferencia_cor!'.$bd->stderr(true));
		$sql->limpar();
		}
	else{
		$sql->adTabela('preferencia_cor');
		$sql->adInserir('usuario_id', (int)$usuario_id);
		$sql->adInserir('cor_fundo', $cor_fundo);
		$sql->adInserir('cor_msg', $cor_msg);
		$sql->adInserir('cor_anexo', $cor_anexo);
		$sql->adInserir('cor_despacho', $cor_despacho);
		$sql->adInserir('cor_resposta', $cor_resposta);
		$sql->adInserir('cor_anotacao', $cor_anotacao);
		$sql->adInserir('cor_encamihamentos', $cor_encamihamentos);
		$sql->adInserir('cor_msg_nao_lida', $cor_msg_nao_lida);
		$sql->adInserir('cor_msg_realce', $cor_msg_realce);
		$sql->adInserir('cor_referencia', $cor_referencia);
		$sql->adInserir('cor_referenciado', $cor_referenciado);
		if (!$sql->exec()) die('Não foi possivel alterar os valores das cores da tabela preferencia_cor!'.$bd->stderr(true));
		$sql->limpar();
		}
	echo "<script>alert ('Preferência de cores salvas'); </script>";
	}

$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos, cor_msg_nao_lida, cor_msg_realce, cor_referencia, cor_referenciado');
$sql->adOnde('usuario_id='.(int)$usuario_id);
$cor = $sql->Linha();
$sql->Limpar();

if (!isset($cor['cor_fundo'])) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos, cor_msg_nao_lida, cor_msg_realce, cor_referencia, cor_referenciado');
	$sql->adOnde('usuario_id=0 OR usuario_id IS NULL');
	$cor = $sql->Linha();
	$sql->Limpar();
	}

echo '<form method="POST" id="cor" name="cor">';
echo '<input type=hidden id="m" name="m" value="sistema">';
echo '<input type=hidden id="u" name="u" value="email">';
echo '<input type=hidden id="a" name="a" value="preferencia_cor_msg">';
echo '<input type=hidden id="usuario_id" name="usuario_id" value="'.(int)$usuario_id.'">';
echo estiloTopoCaixa(770);
echo '<table align="center" class="std2" cellpadding=0 cellspacing=0 width="770"><tr><td>';

echo '<table align=center><tr><td align="center">'.dica('Preferências de Cor do '.ucfirst($config['usuario']), 'Preferencias de cor n'.$config['genero_mensagem'].'s '.$config['mensagens'].'.').'<h2><b>Preferências '.dicaF().($usuario_id ? 'de '.nome_usuario($usuario_id) : 'do '.ucfirst($config['usuario']).' Padrão').'</b></h2>'.'</th></tr></table>';

echo '<table><tr><td>'.dica('salvar','Clique neste botão para salvar as seleções de cores que serão apresentadas quando visualizar '.$config['mensagens'].'.').'<a class="botao" href="javascript:void(0);" onclick="javascript:cor.submit();"><span><b>salvar</b></span></a>'.dicaF().'</td><td width="640">&nbsp;</td><td align=right>'.dica("Voltar","Clique neste botão para voltar à tela anterior.").'<a class="botao" href="javascript:void(0);" onclick="javascript:cor.u.value=\'\'; cor.a.value=\'editarpref\'; cor.submit();"><span><b>voltar</b></span></a>'.dicaF().'</td></tr></table>';

echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Fundo d'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']), 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do fundo d'.$config['genero_mensagem'].'s '.$config['mensagens'].':</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_fundo\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_fundo" style="border:solid;border-width:1;background:#'.$cor['cor_fundo'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_fundo" id="cor_fundo" value="'.$cor['cor_fundo'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_fundo\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';

//MENSAGEM
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho d'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']), 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho d'.$config['genero_mensagem'].'s '.$config['mensagens'].':</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_msg\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_msg" style="border:solid;border-width:1;background:#'.$cor['cor_msg'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_msg" id="cor_msg" value="'.$cor['cor_msg'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_msg\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td colspan="3" align="center" style="background-color: #'.$cor['cor_msg'].';"><b>MENSAGEM 023</b></td></tr>';
echo '<tr><td style="font-weight:Bold; text-align:center; background-color: #'.$cor['cor_msg'].'">Precedência</td><td style="font-weight:Bold; text-align:center; background-color: #'.$cor['cor_msg'].'">Class Sigilosa</td><td style="font-weight:Bold; text-align:center; background-color: #'.$cor['cor_msg'].'">Referência / Assunto</td></tr>';
echo '<tr><td style="background-color: #'.$cor['cor_fundo'].';" align="center">Rotina</td><td align="center" style="background-color: #'.$cor['cor_fundo'].';">Sem Class Slg</td><td style="background-color: #'.$cor['cor_fundo'].';" align="center">Obras/Cursos</td></tr>';
echo '<tr><td align="right" style="font-weight:Bold;background-color: #'.$cor['cor_msg'].';">De:</td><td colspan="2" style="background-color: #'.$cor['cor_fundo'].';" >CPL (Sr. Reinert)</td></tr>';
echo '<tr><td align="right" style="font-weight:Bold;background-color: #'.$cor['cor_msg'].';" >Para:</td><td colspan="2" style="background-color: #'.$cor['cor_fundo'].';">auxsecsau3 (Sr. Model)</td></tr>';
echo '<tr><td colspan="3" align="center" style="font-weight:Bold;background-color: #'.$cor['cor_msg'].';" >Texto d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</td></tr>';
echo '<tr><td colspan="3" width="100%"  style="background-color: #'.$cor['cor_fundo'].';">Segue em anexo o material relacionado.</td></tr>';
echo '<tr><td colspan="2" style="background-color: #'.$cor['cor_fundo'].';" height="1"><b>Crpt: </b>Sim<br></td><td style="background-color: #'.$cor['cor_fundo'].';"><b>Data de Envio: </b> 17/02/2009 08:31 </td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';


//referencia
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho das Referências', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho das referencias:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_referencia\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_referencia" style="border:solid;border-width:1;background:#'.$cor['cor_referencia'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_referencia" id="cor_referencia" value="'.$cor['cor_referencia'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_referencia\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td colspan="3" align="center" style="background-color: #'.$cor["cor_referencia"].';"><b>Referencias</b></td></tr>';
echo '<tr><td colspan="3" style="background-color: #'.$cor['cor_fundo'].';">Doc. 3 - Modelo de MDO - Sr. Spalding - Ch 1ª Seção - 25/07/2010</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';

//referenciado
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho das Referências', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho das referenciados:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_referenciado\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_referenciado" style="border:solid;border-width:1;background:#'.$cor['cor_referenciado'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_referenciado" id="cor_referenciado" value="'.$cor['cor_referenciado'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_referenciado\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td colspan="3" align="center" style="background-color: #'.$cor["cor_referenciado"].';"><b>referenciados</b></td></tr>';
echo '<tr><td colspan="3" style="background-color: #'.$cor['cor_fundo'].';">Msg. 15 - teste2 - Sr. Reinert - Ch 3ª Seção - 30/10/2010</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';



//anexos
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho dos Anexos', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho dos anexos:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_anexo\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_anexo" style="border:solid;border-width:1;background:#'.$cor['cor_anexo'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_anexo" id="cor_anexo" value="'.$cor['cor_anexo'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_anexo\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td colspan="3" align="center" style="background-color: #'.$cor["cor_anexo"].';"><b>Documentos Anexados</b></td></tr>';
echo '<tr><td colspan="3" style="background-color: #'.$cor['cor_fundo'].';">Material 2009.xls (ID 15550 - Anexo por auxsecsau3)</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';
//despacho
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho dos Despachos', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho dos despachos:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_despacho\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_despacho" style="border:solid;border-width:1;background:#'.$cor['cor_despacho'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_despacho" id="cor_despacho" value="'.$cor['cor_despacho'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_despacho\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td bgcolor="#e0dede" style="background-color: #'.$cor['cor_despacho'].';">Despacho de <b>CPL (Sr. Reinert)</b> em 12/11/2008 11:25 </td></tr>';
echo '<tr><td style="background-color:'.$cor['cor_fundo'].';">Iremos colocar no outro pregão &nbsp;</td></tr>';
echo '<tr><td style="background-color: #'.$cor['cor_fundo'].';" ><b>Encaminhado</b>: auxcpl1 (Sr. Gilson Nei)</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';

echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho das Respostas', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho das respostas:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_resposta\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_resposta" style="border:solid;border-width:1;background:#'.$cor['cor_resposta'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_resposta" id="cor_resposta" value="'.$cor['cor_resposta'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_resposta\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td style="background-color: #'.$cor['cor_resposta'].';" >Resposta de <b>adjs4 (Sr. Amir)</b> em 13/11/2009 13:41 </td></tr>';
echo '<tr><td style="background-color: #'.$cor['cor_fundo'].';">O Sr. Fantine está ciente.</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';

echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho das Notas', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho das notas:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_anotacao\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_anotacao" style="border:solid;border-width:1;background:#'.$cor['cor_anotacao'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_anotacao" id="cor_anotacao" value="'.$cor['cor_anotacao'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_anotacao\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td style="background-color: #'.$cor['cor_anotacao'].';" >Nota de <b>CPL (Sr. Reinert)</b> em 16/11/2009 13:41</td></tr>';
echo '<tr><td style="background-color: #'.$cor['cor_fundo'].';">Irei verificar pessoalmente.</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';

echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor do Cabeçalho dos Encaminhamentos', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor do cabeçalho dos encaminhamentos:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_encamihamentos\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_encamihamentos" style="border:solid;border-width:1;background:#'.$cor['cor_encamihamentos'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_encamihamentos" id="cor_encamihamentos" value="'.$cor['cor_encamihamentos'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_encamihamentos\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="770">';
echo '<tr><td colspan="5" width="200" style="background-color: #'.$cor["cor_encamihamentos"].'"><b>Encaminhamentos</b></td></tr>';
echo '<tr><td width="200" style="background-color: #'.$cor["cor_fundo"].'">De</td><td width="200" style="background-color: #'.$cor["cor_fundo"].'">Para</td><td width="200" style="background-color: #'.$cor["cor_fundo"].'">Data de Envio</td><td width="200" style="background-color: #'.$cor["cor_fundo"].'">Data de Leitura</td><td width="200" style="background-color: #'.$cor["cor_fundo"].'">Status</td></tr>';
echo '<tr><td style="background-color: #'.$cor["cor_fundo"].'">CPL (Sr. Reinert)</td><td style="background-color: #'.$cor["cor_fundo"].'">auxcpl1 (Sr. Gilson)</td><td style="background-color: #'.$cor["cor_fundo"].'">12/11/2008 11:26</td><td style="background-color: #'.$cor["cor_fundo"].'">12/11/2008 16:04</td><td style="background-color: #'.$cor["cor_fundo"].'">Arquivada</td></tr>';
echo '</table>';
echo '<table><tr><td>&nbsp;</td></tr></table>';



echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor de '.ucfirst($config['mensagem']).' Não Lid'.$config['genero_mensagem'], 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor d'.$config['genero_mensagem'].'s '.$config['mensagens'].' não lid'.$config['genero_mensagem'].'s:</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_msg_nao_lida\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_msg_nao_lida" style="border:solid;border-width:1;background:#'.$cor['cor_msg_nao_lida'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_msg_nao_lida" id="cor_msg_nao_lida" value="'.$cor['cor_msg_nao_lida'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_msg_nao_lida\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';
echo '<table align="center" width="100%"><tr><td align="right" nowrap="nowrap" width="50%">'.dica('Cor d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).' Selecionada', 'Clique no retângulo colorido para escolher uma das 216 cores pré-definidas. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à direita.').'Cor d'.$config['genero_mensagem'].' '.$config['mensagem'].' selecionad'.$config['genero_mensagem'].':</td><td><a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setcor_msg_realce\', \'calwin\', \'width=310, height=300, scrollbars=no\');"><span id="caixacor_msg_realce" style="border:solid;border-width:1;background:#'.$cor['cor_msg_realce'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'&nbsp;<input type="text" name="cor_msg_realce" id="cor_msg_realce" value="'.$cor['cor_msg_realce'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'onchange="alteracor(\'cor_msg_realce\');" size="10" maxlength="6" class="texto" style="width:50px" />'.dicaF().'</td></tr></table>';

echo '</td></tr></table>';

echo estiloFundoCaixa(770);
echo '</form>';


?>

<SCRIPT LANGUAGE="JavaScript">
var f = document.cor;

function setcor_msg_nao_lida(cor) {
	f.cor_msg_nao_lida.value = cor;
	document.getElementById('caixacor_msg_nao_lida').style.background = '#'+cor;
	}

function setcor_msg_realce(cor) {
	f.cor_msg_realce.value = cor;
	document.getElementById('caixacor_msg_realce').style.background = '#'+cor;
	}

function setcor_menu(cor) {
	f.cor_menu.value = cor;
	document.getElementById('caixacor_menu').style.background = '#'+cor;
	}

function alteracor(item) {
	document.getElementById('caixa'+item).style.background = '#'+document.getElementById(item).value;
	}

function setcor_msg(cor) {
	if (cor) f.cor_msg.value = cor;
	document.getElementById('caixacor_msg').style.background = '#'+cor;
	}

function setcor_anexo(cor) {
	if (cor) f.cor_anexo.value = cor;
	document.getElementById('caixacor_anexo').style.background = '#'+cor;
	}

function setcor_referencia(cor) {
	if (cor) f.cor_referencia.value = cor;
	document.getElementById('caixacor_referencia').style.background = '#'+cor;
	}

function setcor_referenciado(cor) {
	if (cor) f.cor_referenciado.value = cor;
	document.getElementById('caixacor_referenciado').style.background = '#'+cor;
	}


function setcor_despacho(cor) {
	if (cor) f.cor_despacho.value = cor;
	document.getElementById('caixacor_despacho').style.background = '#'+cor;
	}

function setcor_resposta(cor) {
	if (cor) f.cor_resposta.value = cor;
	document.getElementById('caixacor_resposta').style.background = '#'+cor;
	}

function setcor_anotacao(cor) {
	if (cor) f.cor_anotacao.value = cor;
	document.getElementById('caixacor_anotacao').style.background = '#'+cor;
	}

function setcor_encamihamentos(cor) {
	if (cor) f.cor_encamihamentos.value = cor;
	document.getElementById('caixacor_encamihamentos').style.background = '#'+cor;
	}

function setcor_fundo(cor) {
	if (cor) f.cor_fundo.value = cor;
	document.getElementById('caixacor_fundo').style.background = '#'+cor;
	}
</SCRIPT>

