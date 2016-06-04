<?php 

require_once BASE_DIR.'/codigo/instalacao.inc.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$mod_bd=22;
$configuracao = array();
$configuracao['mod_versao'] = 22;
$configuracao['mod_ui_ativo'] = 1;
$configuracao['mod_ativo'] = 1;
$configuracao['mod_nome'] = 'Social';
$configuracao['mod_diretorio'] = 'social';
$configuracao['mod_classe_configurar'] = 'CSetupSocial';
$configuracao['mod_tipo'] = 'usuario';
$configuracao['mod_ui_nome'] = 'Social';
$configuracao['mod_ui_icone'] = 'social_p.gif';
$configuracao['mod_descricao'] = 'M�dulo para trabalhar projetos sociais com fam�lias.';
$configuracao['mod_classe_principal'] = 'CSocial';
$configuracao['mod_texto_botao'] = 'Exibir a lista de op��es do programa social';
$configuracao['permissoes_item_tabela'] = 'social';
$configuracao['permissoes_item_campo'] = 'social_id';
$configuracao['permissoes_item_legenda'] = 'social_nome';
$configuracao['mod_menu'] = 'Programas Sociais:social_p.gif::Menu de programas sociais.;Lista de programas sociais:social_p.gif:m=social&a=index:Lista de programas sociais cadastrados.;Lista de a��es sociais:acao_p.png:m=social&a=acao_lista:Lista de a��es sociais, que s�o parte de programas sociais, cadastradas.;Lista de comit�s:comite_p.gif:m=social&a=comite_lista:Lista de comit�s cadastrados.;Lista de comunidades:comunidade_p.gif:m=social&a=comunidade_lista:Lista de comunidades cadastradas.;Lista de benefici�rios:familia_p.gif:m=social&a=familia_lista:Lista de benefici�rios cadastrados.;Lista de problemas:problema_p.gif:m=social&a=problema_lista:Lista de problemas relacionados com a execu��o das a��es sociais nas fam�lias.;Superitend�ncias:superintendencia_p.gif:m=social&a=superintendencia_lista:Lista de superitend�ncias.;Relat�rios:relatorio_p.gif:m=social&a=relatorio_lista:Lista de relat�rios relacionados com a execu��o das a��es sociais.';  
  
  
class CSetupSocial {

	public function instalar(CAplic $Aplic = NULL) {
  	global $configuracao, $Aplic, $config;
		instalacao_carregarSQL(BASE_DIR.'/modulos/social/sql/instalar_'.$config['tipoBd'].'.sql');
    if ($Aplic->profissional) {
			instalacao_carregarSQL(BASE_DIR.'/modulos/social/sql/menu_pro.sql');
			}
    return true;
		}

	public function exemplo(CAplic $Aplic = NULL) {
		global $Aplic, $config;
		instalacao_carregarSQL(BASE_DIR.'/modulos/social/sql/exemplo_'.$config['tipoBd'].'.sql');
		executar_php(BASE_DIR.'/modulos/social/sql/exemplo_'.$config['tipoBd'].'.php');
	  return true;
		}

	public function remover(CAplic $Aplic = NULL) {
		global $configuracao, $Aplic, $config;
		instalacao_carregarSQL(BASE_DIR.'/modulos/social/sql/desinstalar_'.$config['tipoBd'].'.sql');
	  if ($Aplic->profissional) {
			include_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
			desinstalar_menu_pro($configuracao['mod_diretorio']);
			}
	  return true;
		}

	public function atualizar($versao_antiga) {
		global $Aplic, $config, $mod_bd;
		while ($versao_antiga< $mod_bd){
			++$versao_antiga;
			instalacao_carregarSQL(BASE_DIR.'/modulos/social/sql/atualizar_bd_'.$config['tipoBd'].'_'.$versao_antiga.'.sql');
			executar_php(BASE_DIR.'/modulos/social/sql/atualizar_bd_'.$config['tipoBd'].'_'.$versao_antiga.'.php');
			}
		return true;
		}
		
	public function configurar() {
		global $Aplic;
		$Aplic->redirecionar('m=social&a=configurar');
		return true;
		}	
		
}