<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$pagina = getParam($_REQUEST, 'pagina', 1);
$alterado_ponto=getParam($_REQUEST, 'alterado_ponto', 0);
$alterar_ponto=getParam($_REQUEST, 'alterar_ponto', 0);
$projeto_area_id=getParam($_REQUEST, 'projeto_area_id', 0);
$novo=getParam($_REQUEST, 'novo', 0);
$mudar=getParam($_REQUEST, 'mudar_nome', 0);
$projeto_area_nome=getParam($_REQUEST, 'projeto_area_nome', '');
$projeto_area_obs=getParam($_REQUEST, 'projeto_area_obs', '');
$projeto_area_cor=getParam($_REQUEST, 'projeto_area_cor', '');
$projeto_area_opacidade=getParam($_REQUEST, 'projeto_area_opacidade', '');
$projeto_area_espessura=getParam($_REQUEST, 'projeto_area_espessura', '');
$projeto_area_poligono=getParam($_REQUEST, 'projeto_area_poligono', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', null);

$sql = new BDConsulta;
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/editar_poligono_pro.php';


if ($novo || $mudar) {
    $Aplic->carregarCKEditorJS();
	}

$sql->adTabela('projetos');
$sql->adCampo('projeto_nome');
$sql->adOnde('projeto_id='.$projeto_id);
$projeto_nome = $sql->Resultado();
$sql->Limpar();


if (getParam($_REQUEST, 'excluir_ponto', 0)){

	$sql->setExcluir('projeto_ponto');
	$sql->adOnde('projeto_ponto_id = '.getParam($_REQUEST, 'excluir_ponto', 0));
	$sql->exec();
	$sql->limpar();
	}


if (getParam($_REQUEST, 'novo_ponto', 0)){
	$sql->adTabela('projeto_ponto');
	$sql->adInserir('projeto_area_id', $projeto_area_id);
	$sql->adInserir('projeto_ponto_latitude', getParam($_REQUEST, 'projeto_ponto_latitude', 0));
	$sql->adInserir('projeto_ponto_longitude', getParam($_REQUEST, 'projeto_ponto_longitude', 0));
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();
	}

if (getParam($_REQUEST, 'alterado_ponto', 0)){
	$sql->adTabela('projeto_ponto');
	$sql->adAtualizar('projeto_ponto_latitude', getParam($_REQUEST, 'projeto_ponto_latitude', 0));
	$sql->adAtualizar('projeto_ponto_longitude', getParam($_REQUEST, 'projeto_ponto_longitude', 0));
	$sql->adOnde('projeto_ponto_id = '.$alterado_ponto);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$alterado_ponto=0;
	$sql->limpar();
	}



if (getParam($_REQUEST, 'excluir', 0)){
	$sql->setExcluir('projeto_ponto');
	$sql->adOnde('projeto_area_id = '.$projeto_area_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('projeto_area');
	$sql->adOnde('projeto_area_id = '.$projeto_area_id);
	$sql->exec();
	$sql->limpar();
	$projeto_area_id = 0;
	ver2('Área excluida com sucesso.');
	}

if (getParam($_REQUEST, 'gravar', 0)){



	echo "<script language=Javascript>alert ('Área gravado com sucesso.');</script>";
	}

if (getParam($_REQUEST, 'altera_projeto_area', 0)){
	$sql->adTabela('projeto_area');
	$sql->adAtualizar('projeto_area_nome', $projeto_area_nome);
	$sql->adAtualizar('projeto_area_obs', $projeto_area_obs);
	$sql->adAtualizar('projeto_area_cor', $projeto_area_cor);
	$sql->adAtualizar('projeto_area_espessura', $projeto_area_espessura);
	$sql->adAtualizar('projeto_area_opacidade', $projeto_area_opacidade);
	$sql->adAtualizar('projeto_area_poligono', $projeto_area_poligono);
	$sql->adOnde('projeto_area_id = '.$projeto_area_id);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	else echo "<script language=Javascript>alert ('Área atualizada com sucesso.');</script>";
	$sql->limpar();
	}


if (getParam($_REQUEST, 'cadastrar_novo', 0)){
	$sql->adTabela('projeto_area');
	$sql->adInserir('projeto_area_nome', $projeto_area_nome);
	$sql->adInserir('projeto_area_projeto', $projeto_id);
	$sql->adInserir('projeto_area_tarefa', $tarefa_id);
	$sql->adInserir('projeto_area_obs', $projeto_area_obs);
	$sql->adInserir('projeto_area_cor', $projeto_area_cor);
	$sql->adInserir('projeto_area_espessura', $projeto_area_espessura);
	$sql->adInserir('projeto_area_opacidade', $projeto_area_opacidade);
	$sql->adInserir('projeto_area_poligono', $projeto_area_poligono);
	if (!$sql->exec()) die('Erro no SQL'.$bd->stderr(true));
	$sql->limpar();

	}
$botoesTitulo = new CBlocoTitulo('Áreas d'.$config['genero_projeto'].' '.ucfirst($config['genero_projeto']), 'projeto_area.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" id="m" value="projetos">';
echo '<input type=hidden name="u" id="u" value="">';
echo '<input type=hidden name="a" id="a" value="editar_poligono">';
echo '<input type=hidden name="pagina" id="pagina" value="'.$pagina.'">';

if($Aplic->profissional) echo '<input type=hidden name="carregar_logo" id="carregar_logo" value="">';

echo '<input type=hidden name="projeto_area_tarefa" id="projeto_area_tarefa" value="'.$tarefa_id.'">';
echo '<input type=hidden name="projeto_area_projeto" id="projeto_area_projeto" value="'.$projeto_id.'">';
echo '<input type=hidden name="excluir" id="excluir" value="">';
echo '<input type=hidden name="gravar" id="gravar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';
echo '<input type=hidden name="projeto_area_nome" id="novo" value="'.$projeto_area_nome.'">';
echo '<input type=hidden name="mudar_nome" id="mudar_nome" value="">';
echo '<input type=hidden name="cadastrar_novo" id="cadastrar_novo" value="">';
echo '<input type=hidden name="altera_projeto_area" id="altera_projeto_area" value="">';

echo '<input type=hidden name="novo_ponto" id="novo_ponto" value="0">';
echo '<input type=hidden name="alterar_ponto" id="alterar_ponto" value="0">';
echo '<input type=hidden name="alterado_ponto" id="alterado_ponto" value="0">';
echo '<input type=hidden name="excluir_ponto" id="excluir_ponto" value="0">';


if (!$mudar && !$novo){
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" class="std">';
	echo '<tr><td align="center" colspan=3><b>'.$projeto_nome.'</b></td></tr>';
	$vetor=array(0=>'');

	$sql->adTabela('projeto_area');
	$sql->adCampo('projeto_area_id, projeto_area_nome');
	$sql->adOnde('projeto_area_projeto='.$projeto_id);
	if ($tarefa_id) $sql->adOnde('projeto_area_tarefa='.$tarefa_id);
	$sql->adOrdem('projeto_area_nome ASC');
	$vetor+= $sql->listaVetorChave('projeto_area_id','projeto_area_nome');
	$sql->Limpar();

	echo '<tr><td align="right">'.dica('Área','Selecione um área para editar.').'Área:'.dicaF().'</td><td>'.selecionaVetor($vetor, 'projeto_area_id', 'class=texto size=1 style="width:300px;" onchange="env.submit();"', $projeto_area_id).'</td>';
	echo '<td align="center">'.($novo ? '<table><tr><td>'.dica("Selecionar","Selecionar um projeto_area para editar.").'<a  class="botao" href="administracao"><span><b>selecionar</b></span></a>'.dicaF().'</td></tr></table>' : '<table><tr><td>'.dica('Nova Área','Clique neste botão para criar uma nova área.').'<a class="botao" href="javascript:void(0);" onclick="document.getElementById(\'novo\').value=1; env.submit();"><span><b>novo</b></span></a>'.dicaF().'</td></tr></table>').'</td>';
	echo '<td align="center"><table><tr><td>'.dica('Sair','Sair da edição de áreas.').'<a class="botao" href="javascript:void(0);" onclick="self.close();"><span><b>sair</b></span></a>'.dicaF().'</td></tr></table></td>';
	echo '</tr>';
	echo '<tr><td align="left" colspan=3>&nbsp;</td></tr></table>';
	echo estiloFundoCaixa();
	echo '<br>';
	}

$transparencia=array('0.1'=>'10%', '0.2'=>'20%','0.3'=>'30%','0.4'=>'40%','0.5'=>'50%','0.6'=>'60%','0.7'=>'70%','0.8'=>'80%','0.9'=>'90%','1'=>'100%');
$espessura=array('1'=>'1', '2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6');
if ($novo) {
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20 align="center"><h1>Nova Área</h1></td></tr>';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">Nome:</td><td><input type="text" class="texto" style="width:300px;" name="projeto_area_nome"></td></tr>';
 	echo '<tr><td align="right">'.dica('Espessura da Linha','Espessura da linha que circunscreverá a área cadastrada').'Espessura da linha:'.dicaF().'</td><td>'.selecionaVetor($espessura, 'projeto_area_espessura','class="texto" size=1').'</td></tr>';
 	echo '<tr><td align="right">'.dica('Opacidade','Quanto mais opaco menos visivel serão os elementos do mapa sob a área cadastrada').'Opacidade:'.dicaF().'</td><td>'.selecionaVetor($transparencia, 'projeto_area_opacidade','class="texto" size=1').'</td></tr>';

 echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_area_cor" value="FFFFFF" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
  echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização da área pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_area_cor" value="FFFFFF" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
  echo '<tr><td colspan=20 align="center">Observações</td></tr>';
	echo '<tr><td colspan=20 align="center" style="background:#ffffff; max-width:700px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="projeto_area_obs" id="projeto_area_obs"></textarea></td></tr>';
 	echo '<input type=hidden name="projeto_area_projeto" id="projeto_area_projeto" value="'.$projeto_id.'">';
	echo '<input type=hidden name="projeto_area_tarefa" id="projeto_area_tarefa" value="'.$tarefa_id.'">';

	if($Aplic->profissional) echo '<tr><td colspan=20><table><tr><td>'.dica('Arquivo KML','Poderá importar polígonos no formato KML do Google Maps').'Arquivo KML:'.dicaF().'</td><td><input type="file" class="arquivo" name="logo" size="40"></td><td>'.dica('Carregar KLM','Clique neste botão para enviar o arquivo KLM.').'<a class="botao" href="javascript:void(0);" onclick="javascript: env.carregar_logo.value=1; env.submit();"><span><b>carregar</b></span></a>'.dicaF().'</td></tr></table></td></tr>';


  echo '<tr><td colspan=20><table width="100%"><tr><td>'.dica("Cadastrar","Clique neste botão para cadastrar o novo projeto_area.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.cadastrar_novo.value=1; env.submit();"><span><b>cadastrar</b></span></a>'.dicaF().'</td><td width="90%">&nbsp;</td><td align=right>'.dica("Cancelar","Clique neste botão para cancelar a criação do projeto_area.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>cancelar</b></span></a></td></tr></table></td></tr>';
  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if ($mudar) {
	$sql->adTabela('projeto_area');
  $sql->adCampo('projeto_area.*');
  $sql->adOnde('projeto_area_id='.$projeto_area_id);
	$rs = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table align="center" border=0 width="100%" cellpadding=0 cellspacing=0 class="std">';
	echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '<tr><td align="right">Nome:</td><td><input type="text" class="texto" style="width:300px;" name="projeto_area_nome" value="'.$rs['projeto_area_nome'].'"></td></tr>';
 	echo '<tr><td align="right">Espessura da linha:</td><td>'.selecionaVetor($espessura, 'projeto_area_espessura','class="texto" size=1', $rs['projeto_area_espessura']).'</td></tr>';
 	echo '<tr><td align="right">'.dica('Opacidade','Quanto mais opaco menos visivel serão os elementos do mapa sob a área cadastrada').'Opacidade:'.dicaF().'</td><td>'.selecionaVetor($transparencia, 'projeto_area_opacidade','class="texto" size=1', $rs['projeto_area_opacidade']).'</td></tr>';
  echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="projeto_area_cor" value="'.$rs['projeto_area_cor'].'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.$rs['projeto_area_cor'].';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
  echo '<tr><td colspan=20 align="center">Observações</td></tr>';
	echo '<tr><td colspan=20 align="center" style="background:#ffffff; max-width:700px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="projeto_area_obs" id="projeto_area_obs">'.$rs['projeto_area_obs'].'</textarea></td></tr>';
 	echo '<input type=hidden name="projeto_area_projeto" id="projeto_area_projeto" value="'.$projeto_id.'">';
	echo '<input type=hidden name="projeto_area_tarefa" id="projeto_area_tarefa" value="'.$tarefa_id.'">';
  echo '<input type=hidden name="projeto_area_id" id="projeto_area_id" value="'.$projeto_area_id.'">';



	echo '<tr><td>'.dica('Cancelar','Clique neste botão para cancelar a edição.').'<a class="botao" href="javascript:void(0);" onclick="javascript: env.submit();"><span><b>cancelar</b></span></a></td><td>'.dica('Confirmar','Clique neste botão para confirmar as alterações.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.altera_projeto_area.value=1; env.submit();"><span><b>confirmar</b></span></a>'.dicaF().'</td></tr>';

  echo '<tr><td colspan=20>&nbsp;</td></tr>';
  echo '</Table>';
  echo estiloFundoCaixa();
  }


if (!$novo  && $projeto_area_id && !$mudar) {
	$sql->adTabela('projeto_area');
  $sql->adCampo('*');
  $sql->adOnde('projeto_area_id='.$projeto_area_id);
	$rc = $sql->Linha();
	$sql->Limpar();
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" border=0 class="std">';
	echo '<tr><td colspan=20 align="center"><table><tr><td><h1>'.$rc['projeto_area_nome'].'</h1></td><td><a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$projeto_area_id.');">'.imagem('icones/coordenadas_p.png', 'Visualizar Área ou Ponto', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área ou ponto.').'</a></td></tr></table></td></tr>';
	echo '<tr><td colspan=20><table width="100%"><tr>';
	echo '<td>'.dica('Gravar','Clique neste botão para confirmar a alteração no calendário.').'<a class="botao" href="javascript:void(0);" onclick="gravar();"><span><b>gravar</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Alterar Dados','Clique neste botão para alterar os dados básicos desta área.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.projeto_area_id.value='.$projeto_area_id.'; env.mudar_nome.value=1; env.submit();"><span><b>alterar&nbsp;dados</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Excluir','Clique neste botão para excluir este calendário.').'<a pertence class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.projeto_area_id.value='.$projeto_area_id.'; env.submit();"><span><b>excluir</b></span></a>'.dicaF().'</td>';
	echo '<td>'.dica('Voltar','Clique neste botão para retornar a janela e seleção.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.projeto_area_id.value=0; env.submit();"><span><b>voltar</b></span></a></td>';
	echo '</tr></table></td></tr>';


	if ($alterar_ponto){
		$sql->adTabela('projeto_ponto');
	  $sql->adCampo('projeto_ponto_latitude, projeto_ponto_longitude');
	  $sql->adOnde('projeto_ponto_id='.$alterar_ponto);
		$linha = $sql->Linha();
		$sql->Limpar();
		$projeto_ponto_latitude=$linha['projeto_ponto_latitude'];
		$projeto_ponto_longitude=$linha['projeto_ponto_longitude'];
		}
	else {
		$projeto_ponto_latitude='';
		$projeto_ponto_longitude='';
		}

	//echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0 align=center><tr><td align="right">Latitude:</td><td><input type="text" class="texto" style="width:100px;" name="projeto_ponto_latitude" value="'.$projeto_ponto_latitude.'"></td><td align="right">Longitude:</td><td><input type="text" class="texto" style="width:100px;" name="projeto_ponto_longitude" value="'.$projeto_ponto_longitude.'"></td>'.($alterar_ponto ? '<td><a href="javascript: void(0);" onclick="env.alterado_ponto.value='.$alterar_ponto.'; env.submit();">'.imagem('icones/ok.png', 'Confirmar Alteração', 'Clique neste ícone '.imagem('icones/ok.png').' para confirmar as alterações neste ponto.').'</a><a href="javascript: void(0);" onclick="env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alteração', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações neste ponto.').'</a></td>': '<td><a href="javascript: void(0);" onclick="env.novo_ponto.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Editar Ponto', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar este ponto.').'</a></td>').'</tr></table></td></tr>';


	echo '<tr><td align="right">'.dica('Coordenadas', 'As coordenadas geográficas do ponto.').'Coordenadas:'.dicaF().'</td><td><table><tr><td><table cellpadding=0>';
	echo '<tr><td colspan=2 align=center>Geográfica</td><td colspan=2 align=center>UTM</td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type=text size=15 id="projeto_ponto_longitude" name="projeto_ponto_longitude" value="'.($projeto_ponto_latitude ? $projeto_ponto_latitude : 0).'" onChange="converter_decimal()"></td><td align=right>X:</td><td><input class="texto" type=text size=15 name="txtX" value=""></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type=text size=15 id="projeto_ponto_latitude" name="projeto_ponto_latitude" value="'.($projeto_ponto_longitude ? $projeto_ponto_longitude : 0).'"  onChange="converter_decimal()"></td><td align=right>Y:</td><td><input class="texto" type=text size=15 name="txtY" value=""></td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type="text" name="txtlongraus" size="2" onChange="btnToUTM_OnClick()" value="0">°<input class="texto" type="text" name="txtlonmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlonsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'</td><td align=right>Zona:</td><td><input class="texto" type=text size=4 name="txtZone" value="22" value="0"></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type="text" name="txtlatgraus" size="2" onChange="btnToUTM_OnClick()" value="0">°<input class="texto" type="text" name="txtlatmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlatsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'&nbsp;&nbsp;</td><td colspan=2>Hemisfério:<input class="texto" type=radio name="rbtnHemisphere" value="N" OnClick="0">N<input class="texto" type=radio name="rbtnHemisphere" value="S" OnClick="0" checked>S</td></tr>';
	echo '<tr><td></td><td align=center>'.botao('>>', 'Transformar em UTM', 'Clique neste botão para converter as coordenadas de grau para UTM.','','btnToUTM_OnClick()').'</td><td></td><td align=center>'.botao('<<', 'Transformar em Grau', 'Clique neste botão para converter as coordenadas de UTM para grau.','','btnToGeographic_OnClick()').'</td></tr>';
	echo '</table></td><td>'.($alterar_ponto ? '<td><a href="javascript: void(0);" onclick="env.alterado_ponto.value='.$alterar_ponto.'; env.submit();">'.imagem('icones/ok.png', 'Confirmar Alteração', 'Clique neste ícone '.imagem('icones/ok.png').' para confirmar as alterações neste ponto.').'</a><a href="javascript: void(0);" onclick="env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alteração', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações neste ponto.').'</a></td>': '<td><a href="javascript: void(0);" onclick="env.novo_ponto.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Adicionar Ponto', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar este ponto.').'</a></td>').'</td></tr></table></td></tr>';



	$xpg_tamanhoPagina = 30;
	$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);


	$sql->adTabela('projeto_ponto');
  $sql->adCampo('projeto_ponto.*');
  $sql->adOnde('projeto_area_id='.$projeto_area_id);
	$pontos = $sql->Lista();
	$sql->Limpar();

	$xpg_totalregistros = ($pontos ? count($pontos) : 0);
	$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
	if ($xpg_total_paginas > 1) mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'ponto', 'pontos');


	if (count($pontos)){
		echo '<tr><td colspan=20><table class="tbl1" cellpadding=0 cellspacing=0 align=center><tr><th>Latitude</th><th>Longitude</th><th>&nbsp;</th>';
		for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
			$ponto = $pontos[$i];

			echo '<tr><td>'.$ponto['projeto_ponto_latitude'].'</td><td>'.$ponto['projeto_ponto_longitude'].'</td><td><a href="javascript: void(0);" onclick="popCoordenadas('.$ponto['projeto_ponto_latitude'].','.$ponto['projeto_ponto_longitude'].',0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Coordenadas', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa as coordenadas geográficas deste ponto.').'</a><a href="javascript: void(0);" onclick="env.alterar_ponto.value='.$ponto['projeto_ponto_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Ponto', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este ponto.').'</a><a href="javascript: void(0);" onclick="env.excluir_ponto.value='.$ponto['projeto_ponto_id'].'; env.submit();">'.imagem('icones/remover.png', 'Excluir Ponto', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este ponto.').'</a></td></tr>';
			}
		echo '</table></td></tr>';
		}

	echo '</table>';
	echo '</form>';
	echo estiloFundoCaixa();
	}



echo '</form>';

?>

<script LANGUAGE="javascript">

function altera_gru(){
	env.projeto_area_id.value=document.getElementById('projeto_area_id').value;
	env.submit();
}

function popCoordenadas(latitude, longitude, projeto_area_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : ''), 'Ver Coordenada','height=467,width=770px,resizable,scrollbars=no');
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_area_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_area_cor.value;
	}

function gravar(){
	env.gravar.value=1;
	env.projeto_area_id.value=<?php echo $projeto_area_id ?>;
	env.submit();
	}








var pi = 3.14159265358979;
/* Ellipsoide (WGS84) */
/* var sm_a = 6378137.0; */
var sm_a = 6378160.0;
var sm_b = 6356752.314;
var sm_EccSquared = 6.69437999013e-03;
var wnumero = 0
var wgrau = 0
var wmin = 0
var wsec = 0
var UTMScaleFactor = 0.9996;



function DegToRad (deg){
  return (deg / 180.0 * pi);
	}





function RadToDeg (rad){
  return (rad / pi * 180.0);
	}




function ArcLengthOfMeridian (phi){
  var alpha, beta, gamma, delta, epsilon, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha = ((sm_a + sm_b) / 2.0) * (1.0 + (Math.pow (n, 2.0) / 4.0) + (Math.pow (n, 4.0) / 64.0));
  beta = (-3.0 * n / 2.0) + (9.0 * Math.pow (n, 3.0) / 16.0) + (-3.0 * Math.pow (n, 5.0) / 32.0);
  gamma = (15.0 * Math.pow (n, 2.0) / 16.0) + (-15.0 * Math.pow (n, 4.0) / 32.0);
  delta = (-35.0 * Math.pow (n, 3.0) / 48.0) + (105.0 * Math.pow (n, 5.0) / 256.0);
  epsilon = (315.0 * Math.pow (n, 4.0) / 512.0);
	result = alpha * (phi + (beta * Math.sin (2.0 * phi)) + (gamma * Math.sin (4.0 * phi)) + (delta * Math.sin (6.0 * phi)) + (epsilon * Math.sin (8.0 * phi)));
	return result;
	}




function UTMCentralMeridian (zone){
  var cmeridian;
  cmeridian = DegToRad (-183.0 + (zone * 6.0));
  return cmeridian;
	}




function FootpointLatitude (y){
  var y_, alpha_, beta_, gamma_, delta_, epsilon_, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha_ = ((sm_a + sm_b) / 2.0) * (1 + (Math.pow (n, 2.0) / 4) + (Math.pow (n, 4.0) / 64));
  y_ = y / alpha_;
  beta_ = (3.0 * n / 2.0) + (-27.0 * Math.pow (n, 3.0) / 32.0) + (269.0 * Math.pow (n, 5.0) / 512.0);
  gamma_ = (21.0 * Math.pow (n, 2.0) / 16.0) + (-55.0 * Math.pow (n, 4.0) / 32.0);
  delta_ = (151.0 * Math.pow (n, 3.0) / 96.0) + (-417.0 * Math.pow (n, 5.0) / 128.0);
  epsilon_ = (1097.0 * Math.pow (n, 4.0) / 512.0);
  result = y_ + (beta_ * Math.sin (2.0 * y_))  + (gamma_ * Math.sin (4.0 * y_)) + (delta_ * Math.sin (6.0 * y_))  + (epsilon_ * Math.sin (8.0 * y_));
  return result;
	}




function MapLatLonToXY (phi, lambda, lambda0, xy){
  var N, nu2, ep2, t, t2, l;
  var l3coef, l4coef, l5coef, l6coef, l7coef, l8coef;
  var tmp;
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  nu2 = ep2 * Math.pow (Math.cos (phi), 2.0);
  N = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nu2));
  t = Math.tan (phi);
  t2 = t * t;
  tmp = (t2 * t2 * t2) - Math.pow (t, 6.0);
  l = lambda - lambda0;
  l3coef = 1.0 - t2 + nu2;
  l4coef = 5.0 - t2 + 9 * nu2 + 4.0 * (nu2 * nu2);
  l5coef = 5.0 - 18.0 * t2 + (t2 * t2) + 14.0 * nu2 - 58.0 * t2 * nu2;
  l6coef = 61.0 - 58.0 * t2 + (t2 * t2) + 270.0 * nu2 - 330.0 * t2 * nu2;
  l7coef = 61.0 - 479.0 * t2 + 179.0 * (t2 * t2) - (t2 * t2 * t2);
  l8coef = 1385.0 - 3111.0 * t2 + 543.0 * (t2 * t2) - (t2 * t2 * t2);
  xy[0] = N * Math.cos (phi) * l   + (N / 6.0 * Math.pow (Math.cos (phi), 3.0) * l3coef * Math.pow (l, 3.0)) + (N / 120.0 * Math.pow (Math.cos (phi), 5.0) * l5coef * Math.pow (l, 5.0)) + (N / 5040.0 * Math.pow (Math.cos (phi), 7.0) * l7coef * Math.pow (l, 7.0));
  xy[1] = ArcLengthOfMeridian (phi) + (t / 2.0 * N * Math.pow (Math.cos (phi), 2.0) * Math.pow (l, 2.0)) + (t / 24.0 * N * Math.pow (Math.cos (phi), 4.0) * l4coef * Math.pow (l, 4.0)) + (t / 720.0 * N * Math.pow (Math.cos (phi), 6.0) * l6coef * Math.pow (l, 6.0)) + (t / 40320.0 * N * Math.pow (Math.cos (phi), 8.0) * l8coef * Math.pow (l, 8.0));
  return;
	}




function MapXYToLatLon (x, y, lambda0, philambda){
  var phif, Nf, Nfpow, nuf2, ep2, tf, tf2, tf4, cf;
  var x1frac, x2frac, x3frac, x4frac, x5frac, x6frac, x7frac, x8frac;
  var x2poly, x3poly, x4poly, x5poly, x6poly, x7poly, x8poly;
  phif = FootpointLatitude (y);
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  cf = Math.cos (phif);
  nuf2 = ep2 * Math.pow (cf, 2.0);
  Nf = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nuf2));
  Nfpow = Nf;
  tf = Math.tan (phif);
  tf2 = tf * tf;
  tf4 = tf2 * tf2;
  x1frac = 1.0 / (Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**2) */
  x2frac = tf / (2.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**3) */
  x3frac = 1.0 / (6.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**4) */
  x4frac = tf / (24.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**5) */
  x5frac = 1.0 / (120.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**6) */
  x6frac = tf / (720.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**7) */
  x7frac = 1.0 / (5040.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**8) */
  x8frac = tf / (40320.0 * Nfpow);
  x2poly = -1.0 - nuf2;
  x3poly = -1.0 - 2 * tf2 - nuf2;
  x4poly = 5.0 + 3.0 * tf2 + 6.0 * nuf2 - 6.0 * tf2 * nuf2	- 3.0 * (nuf2 *nuf2) - 9.0 * tf2 * (nuf2 * nuf2);
  x5poly = 5.0 + 28.0 * tf2 + 24.0 * tf4 + 6.0 * nuf2 + 8.0 * tf2 * nuf2;
  x6poly = -61.0 - 90.0 * tf2 - 45.0 * tf4 - 107.0 * nuf2	+ 162.0 * tf2 * nuf2;
  x7poly = -61.0 - 662.0 * tf2 - 1320.0 * tf4 - 720.0 * (tf4 * tf2);
  x8poly = 1385.0 + 3633.0 * tf2 + 4095.0 * tf4 + 1575 * (tf4 * tf2);
  philambda[0] = phif + x2frac * x2poly * (x * x)	+ x4frac * x4poly * Math.pow (x, 4.0)	+ x6frac * x6poly * Math.pow (x, 6.0)	+ x8frac * x8poly * Math.pow (x, 8.0);
  philambda[1] = lambda0 + x1frac * x	+ x3frac * x3poly * Math.pow (x, 3.0)	+ x5frac * x5poly * Math.pow (x, 5.0)	+ x7frac * x7poly * Math.pow (x, 7.0);
  return;
	}





function LatLonToUTMXY (lat, lon, zone, xy){
  MapLatLonToXY (lat, lon, UTMCentralMeridian (zone), xy);
  /* Adjust easting and northing for UTM system. */
  xy[0] = xy[0] * UTMScaleFactor + 500000.0;
  xy[1] = xy[1] * UTMScaleFactor;
  if (xy[1] < 0.0) xy[1] = xy[1] + 10000000.0;
  return zone;
	}




function UTMXYToLatLon (x, y, zone, southhemi, latlon){
  var cmeridian;
  x -= 500000.0;
  x /= UTMScaleFactor;
  /* If in southern hemisphere, adjust y accordingly. */
  if (southhemi)
  y -= 10000000.0;
  y /= UTMScaleFactor;
 	cmeridian = UTMCentralMeridian (zone);
  MapXYToLatLon (x, y, cmeridian, latlon);
  return;
	}





function btnToUTM_OnClick (){
  var xy = new Array(2);
  if (document.env.txtlongraus.value!=null) {
   	wgrau = parseFloat (document.env.txtlongraus.value);
   	wmin = parseFloat (document.env.txtlonmin.value) / 60;
  	wsec = parseFloat (document.env.txtlonsec.value) / 3600;
   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;

   	document.env.projeto_ponto_longitude.value = wnumero;
		}
  if (isNaN (parseFloat (document.env.projeto_ponto_longitude.value))) {
    alert ("Entre com uma longitude válida.");
    return false;
		}
  lon = parseFloat (document.env.projeto_ponto_longitude.value);
  if ((lon < -180.0) || (180.0 <= lon)) {
    alert ("Entre com um número para latitude entre -180, 180.");
    return false;
		}
	if (document.env.txtlatgraus.value!=null) {
    wgrau = parseFloat (document.env.txtlatgraus.value);
    wmin = parseFloat (document.env.txtlatmin.value) / 60;
    wsec = parseFloat (document.env.txtlatsec.value) / 3600;

   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;


    document.env.projeto_ponto_latitude.value = wnumero;
  	}
  if (isNaN (parseFloat (document.env.projeto_ponto_latitude.value))) {
    alert ("Entre com uma latitude válida.");
    return false;
		}
  lat = parseFloat (document.env.projeto_ponto_latitude.value);
  if ((lat < -90.0) || (90.0 < lat)) {
    alert ("Entre com um número para latitude entre -90, 90.");
    return false;
		}
  zone = Math.floor ((lon + 180.0) / 6) + 1;
  zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);
  document.env.txtX.value = xy[0];
  document.env.txtY.value = xy[1];
  document.env.txtZone.value = zone;
  if (lat < 0) document.env.rbtnHemisphere[1].checked = true;
  else document.env.rbtnHemisphere[0].checked = true;
  return true;
	}



function btnToGeographic_OnClick (){
  latlon = new Array(2);
  var x, y, zone, southhemi;
  if (isNaN (parseFloat (document.env.txtX.value))) {
    alert ("Entre com uma Coordenada váida para X.");
    return false;
		}
  x = parseFloat (document.env.txtX.value);
  x = x - 75;
  if (isNaN (parseFloat (document.env.txtY.value))) {
    alert ("Entre com uma Coordenada váida para Y.");
    return false;
		}
  y = parseFloat (document.env.txtY.value);
  y = y - 25;
  if (isNaN (parseInt (document.env.txtZone.value))) {
    alert ("Entre com uma Zona válida.");
    return false;
		}
  zone = parseFloat (document.env.txtZone.value);
  if ((zone < 1) || (60 < zone)) {
    alert ("Zona Inválida entre com um número de 1 à 60");
    return false;
		}
  if (document.env.rbtnHemisphere[1].checked == true) southhemi = true;
  else southhemi = false;
  UTMXYToLatLon (x, y, zone, southhemi, latlon);
  document.env.projeto_ponto_longitude.value = RadToDeg (latlon[1]);
  document.env.projeto_ponto_latitude.value = RadToDeg (latlon[0]);
  wnumero = Math.abs(RadToDeg (latlon[1]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlongraus.value = wgrau;
  document.env.txtlonmin.value = wmin;
  document.env.txtlonsec.value = wsec;
  wnumero = Math.abs(RadToDeg (latlon[0]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlatgraus.value = wgrau;
  document.env.txtlatmin.value = wmin;
  document.env.txtlatsec.value = wsec;
  return true;
	}

function converter_decimal(){
	var long=env.projeto_ponto_longitude.value;
	grau_long = parseInt(long);
	minuto=long-grau_long;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_long=parseInt(minuto);
	segundo=minuto-minuto_long;
	segundo=segundo*60;
	segundo_long=parseInt(segundo);
	env.txtlongraus.value=grau_long;
	env.txtlonmin.value=minuto_long;
	env.txtlonsec.value=segundo_long;

	var lat=env.projeto_ponto_latitude.value;
	grau_lat = parseInt(lat);
	minuto=lat-grau_lat;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_lat=parseInt(minuto);
	segundo=minuto-minuto_lat;
	segundo=segundo*60;
	segundo_lat=parseInt(segundo);

	env.txtlatgraus.value=grau_lat;
	env.txtlatmin.value=minuto_lat;
	env.txtlatsec.value=segundo_lat;
	}


converter_decimal();

</script>





