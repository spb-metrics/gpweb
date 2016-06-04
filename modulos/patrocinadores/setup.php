<?php

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/codigo/instalacao.inc.php';

$mod_bd=2;
$configuracao = array();
$configuracao['mod_versao'] = 2;
$configuracao['mod_ui_ativo'] = 1;
$configuracao['mod_ativo'] = 1;
$configuracao['mod_nome'] = 'Patrocinadores';
$configuracao['mod_diretorio'] = 'patrocinadores';
$configuracao['mod_classe_configurar'] = 'CSetupPatrocinador';
$configuracao['mod_tipo'] = 'usuario';
$configuracao['mod_ui_nome'] = 'Patrocinadores';
$configuracao['mod_ui_icone'] = 'patrocinador_p.gif';
$configuracao['mod_descricao'] = 'Módulo para relacionar patrocinadores com os projetos.';
$configuracao['mod_classe_principal'] = 'CPatrocinador';
$configuracao['mod_texto_botao'] = 'Exibir a lista de patrocinadores';
$configuracao['permissoes_item_tabela'] = 'patrocinadores';
$configuracao['permissoes_item_campo'] = 'patrocinador_id';
$configuracao['permissoes_item_legenda'] = 'patrocinador_nome';
$configuracao['mod_menu'] = 'Patrocinadores:patrocinador_p.gif::Menu de patrocinadores.;Lista de patrocinadores:patrocinador_p.gif:m=patrocinadores&a=index:Relacionar patrocinadores com os projetos, atraves dos instrumentos.';  
  
  
class CSetupPatrocinador {

	public function instalar() {
		global $configuracao, $Aplic, $config;
		instalacao_carregarSQL(BASE_DIR.'/modulos/patrocinadores/sql/instalar_'.$config['tipoBd'].'.sql');
		if ($Aplic->profissional) {
			instalacao_carregarSQL(BASE_DIR.'/modulos/patrocinadores/sql/menu_pro.sql');
			}
    return true;
		}

	public function remover() {

		global $configuracao, $Aplic, $config;
		instalacao_carregarSQL(BASE_DIR.'/modulos/patrocinadores/sql/desinstalar_'.$config['tipoBd'].'.sql');

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
				instalacao_carregarSQL(BASE_DIR.'/modulos/patrocinadores/sql/atualizar_bd_'.$config['tipoBd'].'_'.$versao_antiga.'.sql');
				executar_php(BASE_DIR.'/modulos/patrocinadores/sql/atualizar_bd_'.$config['tipoBd'].'_'.$versao_antiga.'.php');
				}
		return true;
		}
		
	public function configurar() {
		global $Aplic;
		$Aplic->redirecionar('m=patrocinador&a=configurar');
		return true;
		}	
		
}