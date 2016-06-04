<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
require_once (BASE_DIR.'/modulos/projetos/licao.class.php');
$Aplic->carregarCalendarioJS();

$Aplic->carregarCKEditorJS();

$projeto_id =getParam($_REQUEST, 'projeto_id', null);

$licao_id =getParam($_REQUEST, 'licao_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

$obj = new CLicao();

if ($licao_id){
	$obj->load($licao_id);
	$cia_id=$obj->licao_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
	}


if ($projeto_id && !$licao_id) $obj->licao_projeto=$projeto_id;


if($licao_id && !(permiteEditarLicao($obj->licao_acesso,$licao_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$licao_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$licao_tipo = array('0' => 'Negativa', '1' => 'Positiva');
$licao_categoria=getSisValor('LicaoCategoria');
$licao_status = getSisValor('StatusLicao');

$botoesTitulo = new CBlocoTitulo(($licao_id ? 'Editar Li��o Aprendida' : 'Criar Li��o Aprendida'), 'licoes.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
$cias_selecionadas = array();
$usuarios_selecionados=array();
$depts_selecionados=array();
if ($licao_id) {
	$sql->adTabela('licao_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('licao_id = '.(int)$licao_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('licao_dept');
	$sql->adCampo('licao_dept_dept');
	$sql->adOnde('licao_dept_licao ='.(int)$licao_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('licao_cia');
		$sql->adCampo('licao_cia_cia');
		$sql->adOnde('licao_cia_licao = '.(int)$licao_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_licao" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input name="licao_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="licao_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="licao_cias"  id="licao_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="licao_data" value="'.($obj->licao_data ? $obj->licao_data  : date('Y-m-d H:i:s')).'" />';





echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome da Li��o Aprendida', 'Toda li��o aprendida necessita ter um nome para identifica��o pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'*Nome:'.dicaF().'</td><td><input type="text" name="licao_nome" value="'.$obj->licao_nome.'" style="width:284px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'A qual '.$config['organizacao'].' pertence esta li��o aprendida.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'licao_cia', 'class=texto size=1 style="width:288px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', 'Escolha pressionando o �cone � direita qual '.$config['genero_dept'].' '.$config['dept'].' respons�vel por esta li��o aprendida.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td><input type="hidden" name="licao_dept" id="licao_dept" value="'.($licao_id ? $obj->licao_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($licao_id ? $obj->licao_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel pela Li��o Aprendida', 'Toda li��o aprendida deve ter um respons�vel.').'*Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="licao_responsavel" name="licao_responsavel" value="'.($obj->licao_responsavel ? $obj->licao_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->licao_responsavel ? $obj->licao_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est�o envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


echo '<tr><td nowrap="nowrap" align="right">'.dica(ucfirst($config['projeto']), 'A qual '.$config['projeto'].' esta li��o aprendida est� relacionada.').'*'.ucfirst($config['projeto']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="licao_projeto" id="licao_projeto" value="'.$obj->licao_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($obj->licao_projeto).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';

$data_inicio = intval($obj->licao_data_final) ? new CData($obj->licao_data_final) :  new CData(date("Y-m-d H:i:s"));
echo '<tr><td align="right" nowrap="nowrap">'.dica('Datao', 'Digite ou escolha no calend�rio a data da li��o aprendida.').'Data:'.dicaF().'</td><td nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="licao_data_final" id="licao_data_final" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'licao_data_final\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a></td></tr></table></td></tr>';


echo '<tr><td align="right">'.dica('Ocorr�ncia', 'A ocorr�ncia que gerou esta li��o aprendida.').'Ocorr�ncia:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="licao_ocorrencia" style="width:600px;" class="textarea">'.$obj->licao_ocorrencia.'</textarea></td></tr>';
echo '<tr><td align="right">'.dica('Status', 'O status que reflita sua situa��o atual.').'Status:'.dicaF().'</td><td>'.selecionaVetor($licao_status, 'licao_status', 'size="1" class="texto" style="width:284px;"', $obj->licao_status).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'A li��o aprendida pode ser positiva ou negativa, baseado nas consequ�ncias da ocorr�ncia.').'Tipo:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($licao_tipo, 'licao_tipo', 'class="texto" style="width:284px;"', $obj->licao_tipo).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria', 'Categoria a qual o evento se aplica.').'Categoria:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($licao_categoria, 'licao_categoria', 'class="texto" style="width:284px;"', $obj->licao_categoria).'</td></tr>';
echo '<tr><td align="right">'.dica('Consequ�ncias', 'As consequ�ncias da ocorr�ncia.').'Consequ�ncias:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="licao_consequencia" style="width:600px;" class="textarea">'.$obj->licao_consequencia.'</textarea></td></tr>';
echo '<tr><td align="right">'.dica('A��o Tomada', 'A a��o tomada ap�s a ocorr�ncia.').'A��o tomada:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="licao_acao_tomada" style="width:600px;" class="textarea">'.$obj->licao_acao_tomada.'</textarea></td></tr>';
echo '<tr><td align="right">'.dica('Aprendizado', 'Como melhorar n'.$config['genero_projeto'].'s '.$config['projetos'].' futuros.').'Aprendizado:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="licao_aprendizado" style="width:600px;" class="textarea">'.$obj->licao_aprendizado.'</textarea></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="licao_cor" value="'.($obj->licao_cor ? $obj->licao_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->licao_cor ? $obj->licao_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativa', 'Caso a li��o ainda esteja ativa dever� estar marcado este campo.').'Ativa:'.dicaF().'</td><td><input type="checkbox" value="1" name="licao_ativa" '.($obj->licao_ativa || !$licao_id ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'Pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel e os designados podem ver e editar a li��o aprendida</li><li><b>Privado</b> - Somente o respons�vel e os designados podem ver, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($licao_acesso, 'licao_acesso', 'class="texto"', ($licao_id ? $obj->licao_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('licao_aprendida', $licao_id, 'editar');
if ($campos_customizados->count()) {
    echo '<tr><td colspan="2">';
    $campos_customizados->imprimirHTML();
    echo '</td></tr>';
    }

if ($Aplic->profissional) {
	echo '<tr><td colspan=2 align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necess�rios.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
	echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';


	//arquivo anexo
	$sql->adTabela('licao_arquivo');
	$sql->adCampo('licao_arquivo_id, licao_arquivo_usuario, licao_arquivo_data, licao_arquivo_ordem, licao_arquivo_nome, licao_arquivo_endereco');
	$sql->adOnde('licao_arquivo_licao='.(int)$licao_id);
	$sql->adOrdem('licao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if ($arquivos && count($arquivos)) {
		echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(count($arquivos)>1 ? 'Anexos':'Anexo', 'Clique no nome para abrir o arquivo selecionado.').(count($arquivos)>1 ? 'Anexos':'Anexo').dicaF().':</td><td class="realce" width="100%"><div id="combo_arquivos"><table cellpadding=0 cellspacing=0>';
		foreach ($arquivos as $arquivo) {
			echo '<tr><td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['licao_arquivo_ordem'].', '.$arquivo['licao_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['licao_arquivo_ordem'].', '.$arquivo['licao_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['licao_arquivo_ordem'].', '.$arquivo['licao_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['licao_arquivo_ordem'].', '.$arquivo['licao_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=projetos&a=licao_pro_download&sem_cabecalho=1&licao_arquivo_id='.$arquivo['licao_arquivo_id'].'\');">'.$arquivo['licao_arquivo_nome'].'</a></td>';
			echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['licao_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}

		echo '</table></div></td></tr>';
		}



	}


echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($licao_id > 0 ? 'modifica��o' : 'cria��o').' da li��o aprendida.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pela Li��o Aprendida', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel por esta li��o aprendida.').'<label for="email_responsavel">Respons�vel</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para a Li��o Aprendida', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para esta li��o aprendida.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';

echo '<tr><td colspan=2><table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre esta li��o aprendida.','','popEmailContatos()');
echo '</td><td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados desta li��o aprendida.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';

echo '<tr><td colspan=2>* Campos obrigat�rios</td><td colspan="2"></td></tr>';
echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($licao_id ? 'edi��o' : 'cria��o').' da li��o aprendida.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('licao_cia').value+'&cias_id_selecionadas='+document.getElementById('licao_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.licao_cias.value = organizacao_id_string;
	document.getElementById('licao_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('licao_cias').value);
	__buildTooltip();
	}


function excluir_arquivo(licao_arquivo_id){
	xajax_excluir_arquivo(licao_arquivo_id, document.getElementById('licao_id').value);
	}

function mudar_posicao_arquivo(licao_arquivo_ordem, licao_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(licao_arquivo_ordem, licao_arquivo_id, direcao, document.getElementById('licao_id').value);
	}


function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');

	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "licao_data_final",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("licao_data_final").value = Calendario.printDate(date, "%Y-%m-%d");
	    }
		cal1.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('licao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('licao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.licao_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('licao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('licao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.licao_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('licao_dept').value+'&cia_id='+document.getElementById('licao_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('licao_dept').value+'&cia_id='+document.getElementById('licao_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('licao_cia').value=cia_id;
	document.getElementById('licao_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('licao_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}



function popGerente() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('licao_cia').value+'&usuario_id='+document.getElementById('licao_responsavel').value, window.setGerente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('licao_cia').value+'&usuario_id='+document.getElementById('licao_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('licao_responsavel').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function mudar_om(){
	var cia_id=document.getElementById('licao_cia').value;
	xajax_selecionar_om_ajax(cia_id,'licao_cia','combo_cia', 'class="texto" size=1 style="width:288px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta li��o aprendida?")) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_licao';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.licao_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.licao_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.licao_nome.value.length < 3) {
		alert('Escreva um nome v�lido');
		f.licao_nome.focus();
		}
	else if (f.licao_projeto.value < 1) {
		alert('Escolha <?php echo $config["genero_projeto"]." ".$config["projeto"] ?>');
		f.projeto_nome.focus();
		}
	else if (f.licao_responsavel.value < 1) {
		alert('Escolha um respons�vel pela inser��o da li��o aprendida');
		f.nome_gerente.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('licao_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('licao_cia').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
function setProjeto(chave, valor){
	document.getElementById('licao_projeto').value=(chave > 0 ? chave : null);
	document.getElementById('projeto_nome').value=valor;
	}

</script>

