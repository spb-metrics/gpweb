<?php 

require_once BASE_DIR.'/codigo/instalacao.inc.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

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
$configuracao['mod_descricao'] = 'Módulo para trabalhar projetos sociais com famílias.';
$configuracao['mod_classe_principal'] = 'CSocial';
$configuracao['mod_texto_botao'] = 'Exibir a lista de opções do programa social';
$configuracao['permissoes_item_tabela'] = 'social';
$configuracao['permissoes_item_campo'] = 'social_id';
$configuracao['permissoes_item_legenda'] = 'social_nome';
$configuracao['mod_menu'] = 'Programas Sociais:social_p.gif::Menu de programas sociais.;Lista de programas sociais:social_p.gif:m=social&a=index:Lista de programas sociais cadastrados.;Lista de ações sociais:acao_p.png:m=social&a=acao_lista:Lista de ações sociais, que são parte de programas sociais, cadastradas.;Lista de comitês:comite_p.gif:m=social&a=comite_lista:Lista de comitês cadastrados.;Lista de comunidades:comunidade_p.gif:m=social&a=comunidade_lista:Lista de comunidades cadastradas.;Lista de beneficiários:familia_p.gif:m=social&a=familia_lista:Lista de beneficiários cadastrados.;Lista de problemas:problema_p.gif:m=social&a=problema_lista:Lista de problemas relacionados com a execução das ações sociais nas famílias.;Superitendências:superintendencia_p.gif:m=social&a=superintendencia_lista:Lista de superitendências.;Relatórios:relatorio_p.gif:m=social&a=relatorio_lista:Lista de relatórios relacionados com a execução das ações sociais.';  
  
  
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