<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
//$niveis_acesso=array( 0 => 'Público' , 1 => 'Protegido', 4 => 'Protegido II', 3 => 'Privado');

$cia_id = intval(getParam($_REQUEST, 'cia_id', null));

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$sql = new BDConsulta;

if(getParam($_REQUEST, 'carregar_logo', null)){
	if(isset($_FILES['logo']['name']) && file_exists($_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name']) && $_FILES['logo']["size"]>0){
		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0755);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}
		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0755);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/organizacoes')){
			$res = mkdir($base_dir.'/arquivos/organizacoes', 0755);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\organizacoes', UI_MSG_ALERTA);
				return false;
				}
			}
		if (!is_dir($base_dir.'/arquivos/organizacoes/'.$cia_id)){
			$res = mkdir($base_dir.'/arquivos/organizacoes/'.$cia_id, 0755);
			if (!$res) {
				$Aplic->setMsg('A pasta para a organização não foi configurada para receber arquivos - mude as permissões no arquivos\organizacoes.', UI_MSG_ALERTA);
				return false;
				}
			}
		//apagar o antigo logo
		$sql->adTabela('cias');
		$sql->adCampo('cia_logo');
		$sql->adOnde('cia_id='.(int)$cia_id);
		$cia_logo= $sql->resultado();
		$sql->limpar();
		if ($cia_logo) @unlink($base_dir.'/arquivos/organizacoes/'.$cia_logo);
		move_uploaded_file($_FILES['logo']['tmp_name'], $base_dir.'/arquivos/organizacoes/'.$cia_id.'/'.$_FILES['logo']['name']);
		//inserir o novo na tabela
		$sql->adTabela('cias');
		$sql->adAtualizar('cia_logo', $cia_id.'/'.$_FILES['logo']['name']);
		$sql->adOnde('cia_id = '.$cia_id);
		if (!$sql->exec()) die('Não foi possível alterar a tabela cias.');
		$sql->limpar();
		echo '<script>alert("Arquivo enviado com sucesso.")</script>';
		}
	else echo '<script>alert("Houve um erro no envio do arquivo.")</script>';
	$carregar_modelo=0;
	}

$obj = New CCia();
$obj->load($cia_id);
$permiteEditar=permiteEditarCia($cia_id, $obj->cia_acesso);
if ($cia_id) $podeEditar = ($podeEditar && permiteEditarCia($cia_id, $obj->cia_acesso));
if (!(($podeEditar && $permiteEditar) || $Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$tipos = getSisValor('TipoOrganizacao');
$paises = array('' => '(Selecione um país)') + getPais('Paises');


$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

if (!$obj && $cia_id > 0) {
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=cias');
	}

if ($obj && $cia_id && !permiteEditarCia($cia_id, $obj->cia_acesso)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$ttl = $cia_id > 0 ? 'Editar '.ucfirst($config['organizacao']) : 'Adicionar '.ucfirst($config['organizacao']);
$botoesTitulo = new CBlocoTitulo($ttl, 'organizacao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$contatos_selecionados = array();
$usuarios_selecionados = array();
if ($cia_id) {
	$sql->adTabela('cia_contatos');
	$sql->adCampo('cia_contato_contato');
	$sql->adOnde('cia_contato_cia = '.(int)$cia_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('cia_usuario');
	$sql->adCampo('cia_usuario_usuario');
	$sql->adOnde('cia_usuario_cia='.(int)$cia_id);
	$usuarios_selecionados=$sql->carregarColuna();
	$sql->limpar();
	
	
	}




echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="cias" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_cia_aed" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$cia_id.'" />';
echo '<input name="cia_contatos" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo '<input name="cia_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input type=hidden name="carregar_logo" id="carregar_logo" value="">';
echo '<input type=hidden name="cia_logo" id="cia_logo" value="'.$obj->cia_logo.'">';

echo estiloTopoCaixa();
echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Tod'.$config['genero_organizacao'].' '.$config['organizacao'].' deve ter um nome exclusivo e obrigatório.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="cia_nome_completo" value="'.(isset($obj->cia_nome_completo) ? $obj->cia_nome_completo : '').'" style="width:250px;" />*</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Abreviatura d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Tod'.$config['genero_organizacao'].' '.$config['organizacao'].' deve ter uma abreviatura, para otimizar a exibição das diversas tabelas.').'Abreviatura:'.dicaF().'</td><td><input type="text" class="texto" name="cia_nome" value="'.(isset($obj->cia_nome) ? $obj->cia_nome : '').'" style="width:250px;" />*</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Superior', 'Escolha na caixa de opção à direita a '.strtolower($config['organizacao']).' superior a esta, caso esteja subordinada.').'Superior:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om((isset($obj->cia_superior) ? $obj->cia_superior : null), 'cia_superior', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

echo '<tr><td align="right">'.dica('Responsável pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Escolha na caixa de opção à direita o responsável pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="cia_responsavel" name="cia_responsavel" value="'.(isset($obj->cia_responsavel) ? $obj->cia_responsavel : null).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_contato((isset($obj->cia_responsavel) ? $obj->cia_responsavel : null)).'" style="width:250px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


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
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:252px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';




$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(strtolower($config['contatos']), 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:252px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';


echo '<tr><td align="right">'.dica('CNPJ', 'Escreva, caso exista, o CNPJ dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'CNPJ:'.dicaF().'</td><td><input type="text" class="texto" name="cia_cnpj" value="'.(isset($obj->cia_cnpj) ? $obj->cia_cnpj : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Código:'.dicaF().'</td><td><input type="text" class="texto" name="cia_codigo" value="'.(isset($obj->cia_codigo) ? $obj->cia_codigo : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('E-mail', 'Escreva o e-mail d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'E-mail:'.dicaF().'</td><td><input type="text" class="texto" name="cia_email" value="'.(isset($obj->cia_email) ? $obj->cia_email : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Telefone', 'Escreva o telefone d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone:'.dicaF().'</td><td><input type="text" class="texto" name="cia_tel1" value="'.(isset($obj->cia_tel1) ? $obj->cia_tel1 : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Telefone 2', 'Escreva o telefone alternativo d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone 2:'.dicaF().'</td><td><input type="text" class="texto" name="cia_tel2" value="'.(isset($obj->cia_tel2) ? $obj->cia_tel2 : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Fax', 'Escreva o fax d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Fax:'.dicaF().'</td><td><input type="text" class="texto" name="cia_fax" value="'.(isset($obj->cia_fax) ? $obj->cia_fax : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Endereço', 'Escreva o enderço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Endereço:'.dicaF().'</td><td><input type="text" class="texto" name="cia_endereco1" value="'.(isset($obj->cia_endereco1) ? $obj->cia_endereco1 : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endereço', 'Escreva o complemento do enderço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="cia_endereco2" value="'.(isset($obj->cia_endereco2) ? $obj->cia_endereco2 : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'cia_estado', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $obj->cia_estado).'</td></tr>';
echo '<tr><td align="right">'.dica('Município', 'O município d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->cia_estado, 'cia_cidade', 'class="texto" onchange="mudar_comunidades()" style="width:250px;"', '', $obj->cia_cidade, true, false).'</div></td></tr>';
echo '<tr><td align="right">'.dica('CEP', 'Escreva o CEP d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" name="cia_cep" value="'.(isset($obj->cia_cep) ? $obj->cia_cep : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right">'.dica('País', 'Escolha na caixa de opção à direita o País d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'País:'.dicaF().'</td><td>'.selecionaVetor($paises, 'cia_pais', 'size="1" class="texto" style="width:250px;"', (isset($obj->cia_pais) && $obj->cia_pais ? $obj->cia_pais : 'BR')).'</td></tr>';
echo '<tr><td align="right">'.dica('Página Web d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Escreva o endereço da página internet d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'URL http://'.dicaF().'<a name="x"></a></td><td><input type="text" class="texto" value="'.(isset($obj->cia_url) ? $obj->cia_url : '').'" name="cia_url" style="width:250px;" /><a href="javascript: void(0);" onclick="testeURL(\'CiaURLOne\')">'.dica(' Testar Endereço', 'Clique para abrir em uma nova janela o link digitado à esquerda.').'[testar]'.dicaF().'</a></td></tr>';
echo '<tr><td align="right">'.dica('Tipos de '.$config['organizacao'], 'Qual o tipo de '.$config['organizacao'].' para fins de categorização.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'cia_tipo', 'size="1" class="texto" style="width:250px;"', (isset($obj->cia_tipo) ? $obj->cia_tipo : '')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($niveis_acesso, 'cia_acesso', 'class="texto" style="width:250px;"', ($cia_id ? $obj->cia_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Código Principal da Unidade Gestora', 'Para os órgãos do Governo Federal é um código de 6 algarismos.').'UASG Principal:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_ug" value="'.(isset($obj->cia_ug) ? $obj->cia_ug : '').'" style="width:250px;" maxlength="6" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Código Secundário da Unidade Gestora.', 'Para os órgãos do Governo Federal é um código de 6 algarismos.').'UASG Secundário:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_ug2" value="'.(isset($obj->cia_ug2) ? $obj->cia_ug2 : '').'" style="width:250px;" maxlength="6" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador d'.$config['genero_organizacao'].' '.$config['organizacao'].' para NUP', 'Caso utilize o sistema único e processos faz-se necessário informar o número identificador d'.$config['genero_organizacao'].' '.$config['organizacao'].' de 5 algarismos.').'Identificador de NUP:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_nup" value="'.(isset($obj->cia_nup) ? $obj->cia_nup : '').'" style="width:250px;" maxlength="5" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Último NUP d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Caso utilize o sistema único e processos faz-se necessário informar o quantos NUP já foram emitidos, para que aqueles emitidos pelo '.$config['gpweb'].' sigam a sequencia numérica crescente.').'Quantidade de NUP:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_qnt_nup" value="'.(isset($obj->cia_qnt_nup) ? (int)$obj->cia_qnt_nup : '').'" style="width:250px;" maxlength="6" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantos Protocolos d'.$config['genero_organizacao'].' '.$config['organizacao'].' Sem Ser NUP', 'Caso utilize utilize um sistema de protocolo diferente ou paralelamente ao sistema de número único e processos(NUP), faz-se necessário informar quantos protocolos já foram emitidos, para que aqueles emitidos pelo '.$config['gpweb'].' sigam a sequencia numérica crescente.').'Quantos protocolos:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_qnt_nr" value="'.(isset($obj->cia_qnt_nr) ? (int)$obj->cia_qnt_nr : '').'" style="width:250px;" maxlength="20" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Prefixo','Preencha, caso exista, o prefixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Prefixo:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_prefixo" value="'.(isset($obj->cia_prefixo) ? $obj->cia_prefixo : '').'" style="width:250px;" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Sufixo','Preencha, caso exista, o sufixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Sufixo:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" class="texto" name="cia_sufixo" value="'.(isset($obj->cia_sufixo) ? $obj->cia_sufixo : '').'" style="width:250px;" /></td></tr>';

echo '<tr><td align="right">'.dica('Descrição d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Escreva uma descrição para '.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização.').'Descrição:'.dicaF().'</td><td><textarea cols="70" rows="5" id="cia_descricao" name="cia_descricao" data-gpweb-cmp="ckeditor">'.(isset($obj->cia_descricao) ? $obj->cia_descricao : '').'</textarea></td></tr>';
echo '<tr><td align="right">'.dica('Cabeçalho dos Documentos d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Caso envie documentos criados dentro do '.$config['gpweb'].', este campo formata o cabeçalho dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Cabeçalho:'.dicaF().'</td><td><textarea cols="70" rows="5" id="cia_cabacalho" name="cia_cabacalho" data-gpweb-cmp="ckeditor">'.(isset($obj->cia_cabacalho) ? $obj->cia_cabacalho : '').'</textarea></td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativ'.$config['genero_organizacao'], 'Caso '.$config['genero_organizacao'].' '.$config['organizacao'].' ainda esteja ativ'.$config['genero_organizacao'].' deverá estar marcado este campo.').'Ativ'.$config['genero_organizacao'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="cia_ativo" '.($obj->cia_ativo || !$cia_id ? 'checked="checked"' : '').' /></td></tr>';

if ($obj->cia_logo) echo '<tr><td align="right" valign="middle">'.dica('Logotipo d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Logotipo dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Logotipo:'.dicaF().'</td><td align="left"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/organizacoes/'.$obj->cia_logo.'" alt="" border=0 /></td></tr>';
if ($cia_id) echo '<tr><td align="right" valign="middle">Novo logo:</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="file" class="arquivo" name="logo" size="40"></td><td>'.dica('Carregar Logo','Clique neste botão para enviar o logotipo dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'<a class="botao" href="javascript:void(0);" onclick="javascript: env.carregar_logo.value=1; env.a.value=\'editar\'; env.fazerSQL.value=\'\'; env.submit();"><span><b>carregar</b></span></a>'.dicaF().'</td></tr></table></td></tr>';



require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados($m, (isset($obj->cia_id) ? $obj->cia_id : ''), 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.(isset($obj->cia_id) ? 'm=cias&a=ver&cia_id='.(int)$obj->cia_id : 'm=cias&a=index').'\');}').'</td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();

?>

<script language="javascript">

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.cia_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}



var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('cia_id').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('cia_id').value+'&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.cia_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('cia_estado').value,'cia_cidade','combo_cidade', 'class="texto" size=1 style="width:250px;"', (document.getElementById('cia_cidade').value ? document.getElementById('cia_cidade').value : null));
	}


function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&contato_id='+document.getElementById('cia_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&contato=1&contato_id='+document.getElementById('cia_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('cia_responsavel').value=contato_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_superior').value,'cia_superior','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}



function enviarDados() {
	var form = document.env;
	if (form.cia_nome_completo.value.length < 3) {
		alert( "Insira um nome válido para a <?php echo $config['organizacao']?>");
		form.cia_nome_completo.focus();
		}
	else if (form.cia_nome.value.length < 2) {
		alert( "Insira uma abreviatura válida para a <?php echo $config['organizacao']?>" );
		form.cia_nome.focus();
		}
	else if (form.cia_nup.value.length > 0 && form.cia_nup.value.length!=5){
		alert( "O código da organização de número único de processo(NUP) é composto de 6 algarismos!" );
		form.cia_nup.focus();
		}
	else form.submit();
	}

function testeURL( x ) {
	var teste = 'document.env.cia_url.value';
	teste = eval(teste);
	if (teste.length > 6) newwin = window.open( 'http://' + teste, 'newwin', '' );
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

</script>
