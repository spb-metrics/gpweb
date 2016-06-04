<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

class Lang extends DefaultLang
{
	var $js = array
	(
		'actionNote' => "nota",
		'actionEdit' => "modificar",
		'actionDelete' => "excluir",
		'taskDate' => array("function(date) { return 'adicionado em '+date; }"),
		'confirmDelete' => "Tem certeza?",
		'actionNoteSave' => "salvar",
		'actionNoteCancel' => "cancelar",
		'error' => "Algum erro ocorreu (clique para detalhes)",
		'denied' => "Acesso negado",
		'invalidpass' => "Senha errada",
		'readonly' => "apenas-leitura",
		'tagfilter' => "Tag:",
		'adicionarLista' => "Criar nova lista",
		'renomearLista' => "Renomear lista",
		'excluiLista' => "Isto excluir� a lista corrente e todas as atividades na mesma.\\nTem certeza?",
		'settingsSaved' => "Salvando as configura��es saved. Carregando...",
	);

	var $inc = array
	(
		'My Tiny Todolist' => "Minha Lista de Coisa a Fazer",
		'htab_novatarefa' => "Nova atividade",
		'htab_pesquisar' => "Procurar",
		'btn_adicionar' => "Adicionar",
		'btn_search' => "Procurar",
		'advanced_add' => "Avan�ado",
		'searching' => "Procurando por",
		'tasks' => "Atividades",
		'edit_task' => "Editar Atividade",
		'add_task' => "Nova Atividade",
		'priority' => "Prioridade",
		'task' => "Atividade",
		'nota' => "Nota",
		'save' => "Salvar",
		'cancel' => "Cancelar",
		'btn_login' => "Login",
		'a_login' => "Login",
		'a_logout' => "Logout",
		'parafazer_chave' => "Tags",
		'tagfilter_cancel' => "cancelar filtro",
		'ordenarPorNome' => "Ordenar pessoalmente",
		'ordenarPorPrioridade' => "Ordenar por prioridade",
		'ordenarPorDataFinal' => "Ordenar por data",
		'due' => "Em",
		'daysago' => "%d dias atr�s",
		'indays' => "em %d dias",
		'months_short' => array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"),
		'months_long' => array("Janeiro","Fevereiro","Mar�o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"),
		'days_min' => array("Dom","2�","3�","4�","5�","6�","S�b"),
		'hoje' => "hoje",
		'yesterday' => "ontem",
		'tomorrow' => "amanh�",
		'f_passado' => "Atrasado",
		'f_hoje' => "Hoje e amanh�",
		'f_breve' => "Breve",
		'tasks_and_compl' => "Atividades feitas",
		'notas' => "Notas:",
		'notas_show' => "Mostrar",
		'notas_hide' => "Esconder",
		'list_new' => "Nova lista",
		'list_rename' => "Renomear",
		'list_delete' => "Excluir",
		'allparafazer_chave' => "Todas para fazer:",
		'allparafazer_chave_show' => "Mostrar todas",
		'allparafazer_chave_hide' => "Esconder todas",
		'a_settings' => "Configura��es",
		'rss_feed' => "RSS Feed",
		'feed_titulo' => "%s",
		'feed_description' => "Nova atividade em %s",
	);
}

?>