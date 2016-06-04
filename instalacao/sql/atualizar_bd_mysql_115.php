<?php

if (!file_exists('./sql/atualizar_bd_mysql_115_pro.sql')){
	if (file_exists('../modulos/projetos/template_pro_importar_ajax.php')) @unlink('../modulos/projetos/template_pro_importar_ajax.php');
	if (file_exists('../modulos/projetos/template_pro_importar.php')) @unlink('../modulos/projetos/template_pro_importar.php');
	if (file_exists('../modulos/projetos/ver_idx_portifolio_pro.php')) @unlink('../modulos/projetos/ver_idx_portifolio_pro.php');
	if (file_exists('../modulos/projetos/ver_idx_modelo_pro.php')) @unlink('../modulos/projetos/ver_idx_modelo_pro.php');
	if (file_exists('../modulos/projetos/template_pro_editar.php')) @unlink('../modulos/projetos/template_pro_editar.php');
	if (file_exists('../modulos/projetos/funcoes_pro.php')) @unlink('../modulos/projetos/funcoes_pro.php');
	if (file_exists('../modulos/projetos/template_pro_importar_fazer_sql.php')) @unlink('../modulos/projetos/template_pro_importar_fazer_sql.php');
	if (file_exists('../modulos/projetos/template_pro_ver_idx.php')) @unlink('../modulos/projetos/template_pro_ver_idx.php');
	if (file_exists('../modulos/projetos/template_pro_ver.php')) @unlink('../modulos/projetos/template_pro_ver.php');
	if (file_exists('../modulos/projetos/template_pro_lista.php')) @unlink('../modulos/projetos/template_pro_lista.php');
	if (file_exists('../modulos/projetos/template_pro_editar_ajax.php')) @unlink('../modulos/projetos/template_pro_editar_ajax.php');
	if (file_exists('../modulos/projetos/template_pro_lista_ajax.php')) @unlink('../modulos/projetos/template_pro_lista_ajax.php');
	if (file_exists('../modulos/projetos/template_pro.class.php')) @unlink('../modulos/projetos/template_pro.class.php');
	if (file_exists('../modulos/projetos/editar_pro.php')) @unlink('../modulos/projetos/editar_pro.php');
	if (file_exists('../modulos/projetos/editar_ajax_pro.php')) @unlink('../modulos/projetos/editar_ajax_pro.php');
	if (file_exists('../modulos/projetos/resumo_evento_imprimir_pro.php')) @unlink('../modulos/projetos/resumo_evento_imprimir_pro.php');
	if (file_exists('../modulos/projetos/template_pro_fazer_sql.php')) @unlink('../modulos/projetos/template_pro_fazer_sql.php');
	if (file_exists('../modulos/projetos/editar_poligono_pro.php')) @unlink('../modulos/projetos/editar_poligono_pro.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro_relatorio.php')) @unlink('../modulos/calendario/folha_ponto_pro_relatorio.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro.php')) @unlink('../modulos/calendario/folha_ponto_pro.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro_ajax.php')) @unlink('../modulos/calendario/folha_ponto_pro_ajax.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro_download.php')) @unlink('../modulos/calendario/folha_ponto_pro_download.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro_relatorio_ajax.php')) @unlink('../modulos/calendario/folha_ponto_pro_relatorio_ajax.php');
	if (file_exists('../modulos/calendario/folha_ponto_pro_relatorio.php')) @unlink('../modulos/calendario/folha_ponto_pro_relatorio.php');
	if (file_exists('../modulos/tarefas/funcoes_pro.php')) @unlink('../modulos/tarefas/funcoes_pro.php');
	if (file_exists('../modulos/praticas/indicador_editar_pro.php')) @unlink('../modulos/praticas/indicador_editar_pro.php');
	if (file_exists('../modulos/praticas/indicador_simples.class_pro.php')) @unlink('../modulos/praticas/indicador_simples.class_pro.php');
	if (file_exists('../codigo/login_externo_pro.php')) @unlink('../codigo/login_externo_pro.php');
	if (file_exists('../modulos/email/ver_msg_pro.php')) @unlink('../modulos/email/ver_msg_pro.php');
	if (file_exists('../incluir/funcoes_principais_pro.php')) @unlink('../incluir/funcoes_principais_pro.php');
	if (file_exists('./sql/gpweb_mysql_pro.sql')) @unlink('./sql/gpweb_mysql_pro.sql');
	}

?>