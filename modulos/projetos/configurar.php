<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor. 
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

if (!$Aplic->checarModulo('sistema', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Configuração', 'demanda.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=vermods', 'voltar','','Voltar','Voltar à tela de administração de módulos.');
$botoesTitulo->mostrar();

$sql = new BDConsulta();

if (getParam($_REQUEST, 'gravar', null)){
	$sql->adTabela('demanda_config');
	$sql->adAtualizar('demanda_config_exibe_funcao', getParam($_REQUEST, 'demanda_config_exibe_funcao', null));
	$sql->adAtualizar('demanda_config_exibe_tipo_parecer', getParam($_REQUEST, 'demanda_config_exibe_tipo_parecer', null));
	$sql->adAtualizar('demanda_config_exibe_linha2', getParam($_REQUEST, 'demanda_config_exibe_linha2', null));
	$sql->adAtualizar('demanda_config_linha2_legenda', getParam($_REQUEST, 'demanda_config_linha2_legenda', null));
	$sql->adAtualizar('demanda_config_exibe_linha3', getParam($_REQUEST, 'demanda_config_exibe_linha3', null));
	$sql->adAtualizar('demanda_config_linha3_legenda', getParam($_REQUEST, 'demanda_config_linha3_legenda', null));
	$sql->adAtualizar('demanda_config_exibe_linha4', getParam($_REQUEST, 'demanda_config_exibe_linha4', null));
	$sql->adAtualizar('demanda_config_linha4_legenda', getParam($_REQUEST, 'demanda_config_linha4_legenda', null));
	$sql->adAtualizar('demanda_config_trava_aprovacao', getParam($_REQUEST, 'demanda_config_trava_aprovacao', null));
	
	$sql->adAtualizar('demanda_config_trava_edicao', getParam($_REQUEST, 'demanda_config_trava_edicao', null));
	$sql->adAtualizar('demanda_config_diretriz_iniciacao', getParam($_REQUEST, 'demanda_config_diretriz_iniciacao', null));
	$sql->adAtualizar('demanda_config_ativo_diretriz_iniciacao', getParam($_REQUEST, 'demanda_config_ativo_diretriz_iniciacao', null));
	$sql->adAtualizar('demanda_config_estudo_viabilidade', getParam($_REQUEST, 'demanda_config_estudo_viabilidade', null));
	$sql->adAtualizar('demanda_config_ativo_estudo_viabilidade', getParam($_REQUEST, 'demanda_config_ativo_estudo_viabilidade', null));
	$sql->adAtualizar('demanda_config_diretriz_implantacao', getParam($_REQUEST, 'demanda_config_diretriz_implantacao', null));
	$sql->adAtualizar('demanda_config_ativo_diretriz_implantacao', getParam($_REQUEST, 'demanda_config_ativo_diretriz_implantacao', null));
	$sql->adAtualizar('demanda_config_declaracao_escopo', getParam($_REQUEST, 'demanda_config_declaracao_escopo', null));
	$sql->adAtualizar('demanda_config_ativo_declaracao_escopo', getParam($_REQUEST, 'demanda_config_ativo_declaracao_escopo', null));
	$sql->adAtualizar('demanda_config_estrutura_analitica', getParam($_REQUEST, 'demanda_config_estrutura_analitica', null));
	$sql->adAtualizar('demanda_config_ativo_estrutura_analitica', getParam($_REQUEST, 'demanda_config_ativo_estrutura_analitica', null));
	$sql->adAtualizar('demanda_config_dicionario_eap', getParam($_REQUEST, 'demanda_config_dicionario_eap', null));
	$sql->adAtualizar('demanda_config_ativo_dicionario_eap', getParam($_REQUEST, 'demanda_config_ativo_dicionario_eap', null));
	$sql->adAtualizar('demanda_config_cronograma_fisico', getParam($_REQUEST, 'demanda_config_cronograma_fisico', null));
	$sql->adAtualizar('demanda_config_ativo_cronograma_fisico', getParam($_REQUEST, 'demanda_config_ativo_cronograma_fisico', null));
	$sql->adAtualizar('demanda_config_plano_projeto', getParam($_REQUEST, 'demanda_config_plano_projeto', null));
	$sql->adAtualizar('demanda_config_ativo_plano_projeto', getParam($_REQUEST, 'demanda_config_ativo_plano_projeto', null));
	$sql->adAtualizar('demanda_config_cronograma', getParam($_REQUEST, 'demanda_config_cronograma', null));
	$sql->adAtualizar('demanda_config_ativo_cronograma', getParam($_REQUEST, 'demanda_config_ativo_cronograma', null));
	$sql->adAtualizar('demanda_config_planejamento_custo', getParam($_REQUEST, 'demanda_config_planejamento_custo', null));
	$sql->adAtualizar('demanda_config_ativo_planejamento_custo', getParam($_REQUEST, 'demanda_config_ativo_planejamento_custo', null));
	$sql->adAtualizar('demanda_config_gerenciamento_humanos', getParam($_REQUEST, 'demanda_config_gerenciamento_humanos', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_humanos', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_humanos', null));
	$sql->adAtualizar('demanda_config_gerenciamento_comunicacoes', getParam($_REQUEST, 'demanda_config_gerenciamento_comunicacoes', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_comunicacoes', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_comunicacoes', null));
	$sql->adAtualizar('demanda_config_gerenciamento_partes', getParam($_REQUEST, 'demanda_config_gerenciamento_partes', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_partes', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_partes', null));
	$sql->adAtualizar('demanda_config_gerenciamento_riscos', getParam($_REQUEST, 'demanda_config_gerenciamento_riscos', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_riscos', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_riscos', null));
	$sql->adAtualizar('demanda_config_gerenciamento_qualidade', getParam($_REQUEST, 'demanda_config_gerenciamento_qualidade', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_qualidade', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_qualidade', null));
	$sql->adAtualizar('demanda_config_gerenciamento_mudanca', getParam($_REQUEST, 'demanda_config_gerenciamento_mudanca', null));
	$sql->adAtualizar('demanda_config_ativo_gerenciamento_mudanca', getParam($_REQUEST, 'demanda_config_ativo_gerenciamento_mudanca', null));
	$sql->adAtualizar('demanda_config_controle_mudanca', getParam($_REQUEST, 'demanda_config_controle_mudanca', null));
	$sql->adAtualizar('demanda_config_ativo_controle_mudanca', getParam($_REQUEST, 'demanda_config_ativo_controle_mudanca', null));
	$sql->adAtualizar('demanda_config_aceite_produtos', getParam($_REQUEST, 'demanda_config_aceite_produtos', null));
	$sql->adAtualizar('demanda_config_ativo_aceite_produtos', getParam($_REQUEST, 'demanda_config_ativo_aceite_produtos', null));
	$sql->adAtualizar('demanda_config_relatorio_situacao', getParam($_REQUEST, 'demanda_config_relatorio_situacao', null));
	$sql->adAtualizar('demanda_config_ativo_relatorio_situacao', getParam($_REQUEST, 'demanda_config_ativo_relatorio_situacao', null));
	$sql->adAtualizar('demanda_config_termo_encerramento', getParam($_REQUEST, 'demanda_config_termo_encerramento', null));
	$sql->adAtualizar('demanda_config_ativo_termo_encerramento', getParam($_REQUEST, 'demanda_config_ativo_termo_encerramento', null));
	$sql->adOnde('demanda_config_id = 1');
	$sql->exec();
	$sql->Limpar();
	}


$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$linha = $sql->linha();
$sql->Limpar();

$opcao=array(0=>'Não', 1=>'Sim');

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="gravar" value="1" />';

echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" width=100>'.dica('Exibir Função', 'O campo de função para o cadastro dos '.$config['usuarios'].' internos que assinam a demanda, estudo de viabilidade ou termo de abertura, estará visivel.').'Exibir Função:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_exibe_funcao', 'style="width:50px;" class="texto"', $linha['demanda_config_exibe_funcao']).'</td></tr>';
echo '<tr><td align="right">'.dica('Exibir Parecer', 'O campo de parecer para o cadastro dos '.$config['usuarios'].' internos que assinam a demanda, estudo de viabilidade ou termo de abertura, estará visivel.').'Exibir Parecer:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_exibe_tipo_parecer', 'style="width:50px;" class="texto"', $linha['demanda_config_exibe_tipo_parecer']).'</td></tr>';
echo '<tr><td align="right">'.dica('Exibir 2ª Linha', 'A 2ª linha de dado do cadastro dos usuários externos que assinam a demanda estará visivel.').'Exibir 2ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_exibe_linha2', 'style="width:50px;" class="texto"', $linha['demanda_config_exibe_linha2']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da 2ª Linha', 'A legenda da 2ª linha de dado do cadastro dos usuários externos que assinam a demanda, estudo de viabilidade ou termo de abertura.').'Legenda da 2ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_linha2_legenda" name="demanda_config_linha2_legenda" value="'.$linha['demanda_config_linha2_legenda'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Exibir 3ª Linha', 'A 3ª linha de dado do cadastro dos usuários externos que assinam a demanda estará visivel.').'Exibir 3ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_exibe_linha3', 'style="width:50px;" class="texto"', $linha['demanda_config_exibe_linha3']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da 3ª Linha', 'A legenda da 3ª linha de dado do cadastro dos usuários externos que assinam a demanda, estudo de viabilidade ou termo de abertura.').'Legenda da 3ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_linha3_legenda" name="demanda_config_linha3_legenda" value="'.$linha['demanda_config_linha3_legenda'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Exibir 4ª Linha', 'A 4ª linha de dado do cadastro dos usuários externos que assinam a demanda estará visivel.').'Exibir 4ª Linha:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_exibe_linha4', 'style="width:50px;" class="texto"', $linha['demanda_config_exibe_linha4']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Legenda da 4ª Linha', 'A legenda da 4ª linha de dado do cadastro dos usuários externos que assinam a demanda, estudo de viabilidade ou termo de abertura.').'Legenda da 4ª Linha:'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_linha4_legenda" name="demanda_config_linha4_legenda" value="'.$linha['demanda_config_linha4_legenda'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Travar Aprovação', 'Impedir que se mude despacho após aprovação final de demanda, estudo de viabilidade ou termo de abertura.').'Travar Aprovação:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_trava_aprovacao', 'style="width:50px;" class="texto"', $linha['demanda_config_trava_aprovacao']).'</td></tr>';

echo '<tr><td align="right">'.dica('Travar Edição', 'Impedir que após aprovação final de demanda, estudo de viabilidade ou termo de abertura se possa editar estes objetos.').'Travar Edição:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_trava_edicao', 'style="width:50px;" class="texto"', $linha['demanda_config_trava_edicao']).'</td></tr>';


//echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_diretriz_iniciacao'], 'Exibir o artefato '.$linha['demanda_config_diretriz_iniciacao'].'.').'Exibir '.$linha['demanda_config_diretriz_iniciacao'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_diretriz_iniciacao', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_diretriz_iniciacao']).'</td></tr>';
echo '<input type="hidden" name="demanda_config_ativo_diretriz_iniciacao" value="1" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_diretriz_iniciacao'], 'Nome para o artefato '.$linha['demanda_config_diretriz_iniciacao'].'.').$linha['demanda_config_diretriz_iniciacao'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_diretriz_iniciacao" name="demanda_config_diretriz_iniciacao" value="'.$linha['demanda_config_diretriz_iniciacao'].'" style="width:250px;" class="texto" /></td></tr>';

//echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_estudo_viabilidade'], 'Exibir o artefato '.$linha['demanda_config_estudo_viabilidade'].'.').'Exibir '.$linha['demanda_config_estudo_viabilidade'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_estudo_viabilidade', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_estudo_viabilidade']).'</td></tr>';
echo '<input type="hidden" name="demanda_config_ativo_estudo_viabilidade" value="1" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_estudo_viabilidade'], 'Nome para o artefato '.$linha['demanda_config_estudo_viabilidade'].'.').$linha['demanda_config_estudo_viabilidade'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_estudo_viabilidade" name="demanda_config_estudo_viabilidade" value="'.$linha['demanda_config_estudo_viabilidade'].'" style="width:250px;" class="texto" /></td></tr>';

//echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_diretriz_implantacao'], 'Exibir o artefato '.$linha['demanda_config_diretriz_implantacao'].'.').'Exibir '.$linha['demanda_config_diretriz_implantacao'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_diretriz_implantacao', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_diretriz_implantacao']).'</td></tr>';
echo '<input type="hidden" name="demanda_config_ativo_diretriz_implantacao" value="1" />';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_diretriz_implantacao'], 'Nome para o artefato '.$linha['demanda_config_diretriz_implantacao'].'.').$linha['demanda_config_diretriz_implantacao'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_diretriz_implantacao" name="demanda_config_diretriz_implantacao" value="'.$linha['demanda_config_diretriz_implantacao'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_declaracao_escopo'], 'Exibir o artefato '.$linha['demanda_config_declaracao_escopo'].'.').'Exibir '.$linha['demanda_config_declaracao_escopo'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_declaracao_escopo', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_declaracao_escopo']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_declaracao_escopo'], 'Nome para o artefato '.$linha['demanda_config_declaracao_escopo'].'.').$linha['demanda_config_declaracao_escopo'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_declaracao_escopo" name="demanda_config_declaracao_escopo" value="'.$linha['demanda_config_declaracao_escopo'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_estrutura_analitica'], 'Exibir o artefato '.$linha['demanda_config_estrutura_analitica'].'.').'Exibir '.$linha['demanda_config_estrutura_analitica'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_estrutura_analitica', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_estrutura_analitica']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_estrutura_analitica'], 'Nome para o artefato '.$linha['demanda_config_estrutura_analitica'].'.').$linha['demanda_config_estrutura_analitica'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_estrutura_analitica" name="demanda_config_estrutura_analitica" value="'.$linha['demanda_config_estrutura_analitica'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_dicionario_eap'], 'Exibir o artefato '.$linha['demanda_config_dicionario_eap'].'.').'Exibir '.$linha['demanda_config_dicionario_eap'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_dicionario_eap', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_dicionario_eap']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_dicionario_eap'], 'Nome para o artefato '.$linha['demanda_config_dicionario_eap'].'.').$linha['demanda_config_dicionario_eap'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_dicionario_eap" name="demanda_config_dicionario_eap" value="'.$linha['demanda_config_dicionario_eap'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_cronograma_fisico'], 'Exibir o artefato '.$linha['demanda_config_cronograma_fisico'].'.').'Exibir '.$linha['demanda_config_cronograma_fisico'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_cronograma_fisico', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_cronograma_fisico']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_cronograma_fisico'], 'Nome para o artefato '.$linha['demanda_config_cronograma_fisico'].'.').$linha['demanda_config_cronograma_fisico'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_cronograma_fisico" name="demanda_config_cronograma_fisico" value="'.$linha['demanda_config_cronograma_fisico'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_plano_projeto'], 'Exibir o artefato '.$linha['demanda_config_plano_projeto'].'.').'Exibir '.$linha['demanda_config_plano_projeto'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_plano_projeto', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_plano_projeto']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_plano_projeto'], 'Nome para o artefato '.$linha['demanda_config_plano_projeto'].'.').$linha['demanda_config_plano_projeto'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_plano_projeto" name="demanda_config_plano_projeto" value="'.$linha['demanda_config_plano_projeto'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_cronograma'], 'Exibir o artefato '.$linha['demanda_config_cronograma'].'.').'Exibir '.$linha['demanda_config_cronograma'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_cronograma', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_cronograma']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_cronograma'], 'Nome para o artefato '.$linha['demanda_config_cronograma'].'.').$linha['demanda_config_cronograma'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_cronograma" name="demanda_config_cronograma" value="'.$linha['demanda_config_cronograma'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_planejamento_custo'], 'Exibir o artefato '.$linha['demanda_config_planejamento_custo'].'.').'Exibir '.$linha['demanda_config_planejamento_custo'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_planejamento_custo', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_planejamento_custo']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_planejamento_custo'], 'Nome para o artefato '.$linha['demanda_config_planejamento_custo'].'.').$linha['demanda_config_planejamento_custo'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_planejamento_custo" name="demanda_config_planejamento_custo" value="'.$linha['demanda_config_planejamento_custo'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_humanos'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_humanos'].'.').'Exibir '.$linha['demanda_config_gerenciamento_humanos'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_humanos', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_humanos']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_humanos'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_humanos'].'.').$linha['demanda_config_gerenciamento_humanos'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_humanos" name="demanda_config_gerenciamento_humanos" value="'.$linha['demanda_config_gerenciamento_humanos'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_comunicacoes'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_comunicacoes'].'.').'Exibir '.$linha['demanda_config_gerenciamento_comunicacoes'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_comunicacoes', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_comunicacoes']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_comunicacoes'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_comunicacoes'].'.').$linha['demanda_config_gerenciamento_comunicacoes'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_comunicacoes" name="demanda_config_gerenciamento_comunicacoes" value="'.$linha['demanda_config_gerenciamento_comunicacoes'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_partes'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_partes'].'.').'Exibir '.$linha['demanda_config_gerenciamento_partes'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_partes', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_partes']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_partes'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_partes'].'.').$linha['demanda_config_gerenciamento_partes'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_partes" name="demanda_config_gerenciamento_partes" value="'.$linha['demanda_config_gerenciamento_partes'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_riscos'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_riscos'].'.').'Exibir '.$linha['demanda_config_gerenciamento_riscos'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_riscos', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_riscos']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_riscos'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_riscos'].'.').$linha['demanda_config_gerenciamento_riscos'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_riscos" name="demanda_config_gerenciamento_riscos" value="'.$linha['demanda_config_gerenciamento_riscos'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_qualidade'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_qualidade'].'.').'Exibir '.$linha['demanda_config_gerenciamento_qualidade'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_qualidade', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_qualidade']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_qualidade'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_qualidade'].'.').$linha['demanda_config_gerenciamento_qualidade'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_qualidade" name="demanda_config_gerenciamento_qualidade" value="'.$linha['demanda_config_gerenciamento_qualidade'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_gerenciamento_mudanca'], 'Exibir o artefato '.$linha['demanda_config_gerenciamento_mudanca'].'.').'Exibir '.$linha['demanda_config_gerenciamento_mudanca'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_gerenciamento_mudanca', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_gerenciamento_mudanca']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_gerenciamento_mudanca'], 'Nome para o artefato '.$linha['demanda_config_gerenciamento_mudanca'].'.').$linha['demanda_config_gerenciamento_mudanca'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_gerenciamento_mudanca" name="demanda_config_gerenciamento_mudanca" value="'.$linha['demanda_config_gerenciamento_mudanca'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_controle_mudanca'], 'Exibir o artefato '.$linha['demanda_config_controle_mudanca'].'.').'Exibir '.$linha['demanda_config_controle_mudanca'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_controle_mudanca', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_controle_mudanca']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_controle_mudanca'], 'Nome para o artefato '.$linha['demanda_config_controle_mudanca'].'.').$linha['demanda_config_controle_mudanca'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_controle_mudanca" name="demanda_config_controle_mudanca" value="'.$linha['demanda_config_controle_mudanca'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_aceite_produtos'], 'Exibir o artefato '.$linha['demanda_config_aceite_produtos'].'.').'Exibir '.$linha['demanda_config_aceite_produtos'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_aceite_produtos', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_aceite_produtos']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_aceite_produtos'], 'Nome para o artefato '.$linha['demanda_config_aceite_produtos'].'.').$linha['demanda_config_diretriz_iniciacao'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_aceite_produtos" name="demanda_config_aceite_produtos" value="'.$linha['demanda_config_aceite_produtos'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_relatorio_situacao'], 'Exibir o artefato '.$linha['demanda_config_relatorio_situacao'].'.').'Exibir '.$linha['demanda_config_relatorio_situacao'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_relatorio_situacao', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_relatorio_situacao']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_relatorio_situacao'], 'Nome para o artefato '.$linha['demanda_config_relatorio_situacao'].'.').$linha['demanda_config_relatorio_situacao'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_relatorio_situacao" name="demanda_config_relatorio_situacao" value="'.$linha['demanda_config_relatorio_situacao'].'" style="width:250px;" class="texto" /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Exibir '.$linha['demanda_config_termo_encerramento'], 'Exibir o artefato '.$linha['demanda_config_termo_encerramento'].'.').'Exibir '.$linha['demanda_config_termo_encerramento'].':'.dicaF().'</td><td>'.selecionaVetor($opcao, 'demanda_config_ativo_termo_encerramento', 'style="width:50px;" class="texto"', $linha['demanda_config_ativo_termo_encerramento']).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica($linha['demanda_config_termo_encerramento'], 'Nome para o artefato '.$linha['demanda_config_termo_encerramento'].'.').$linha['demanda_config_termo_encerramento'].':'.dicaF().'</td><td colspan="2"><input type="text" id="demanda_config_termo_encerramento" name="demanda_config_termo_encerramento" value="'.$linha['demanda_config_termo_encerramento'].'" style="width:250px;" class="texto" /></td></tr>';



echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Clique neste botão para salvar as alterações.','','env.submit()').'</td></tr></table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();



echo '</form>';
?>