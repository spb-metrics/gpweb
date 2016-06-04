<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$dialogo) $Aplic->salvarPosicao();
$banco = getParam($_REQUEST, 'banco', '');
$hospedadoBd = getParam($_REQUEST, 'hospedadoBd', '');
$nomeBd = getParam($_REQUEST, 'nomeBd', '');
$usuarioBd = getParam($_REQUEST, 'usuarioBd', '');
$senhaBd = getParam($_REQUEST, 'senhaBd', '');
$botoesTitulo = new CBlocoTitulo('Importar do Web2Project/DotProject ou PECM', 'importar.jpg', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
$feito = (int)getParam($_REQUEST, 'feito', 0);
$tabela = getParam($_REQUEST, 'tabela', '');
$historico = getParam($_REQUEST, 'historico', '');
$formatacao = getParam($_REQUEST, 'formatacao', '8859');

if (!($podeAcessar || $tabela)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$tabela && $banco){
	if ($banco=='pecm') $tabela='anexos';
	if ($banco=='dotproject') $tabela='companies';
	}

$prefixo = getParam($_REQUEST, 'prefixo', '');
$linhas = (int)getParam($_REQUEST, 'linhas', 500);
$usuario_perfil=getParam($_REQUEST, 'usuario_perfil', '');
$funcao = getParam($_REQUEST, 'funcao', '');
$nome = getParam($_REQUEST, 'nome', '');

$sql = new BDConsulta;


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="feito" value="'.$feito.'" />';
echo '<input type="hidden" name="tabela" value="'.$tabela.'" />';	
echo '<input type="hidden" name="linhas" value="'.$linhas.'" />';	
echo '<input type="hidden" name="funcao" value="'.$funcao.'" />';	
echo '<input type="hidden" name="nome" value="'.$nome.'" />';	
echo '<input type="hidden" name="usuario_perfil" value="'.$usuario_perfil.'" />';	
echo '<input type="hidden" name="historico" value="'.$historico.'" />';
echo '<input type="hidden" name="formatacao" value="'.$formatacao.'" />';

if (!$nomeBd){
	$sql->adTabela('perfil');
	$sql->adCampo('perfil.*');
	$perfis=$sql->lista();
	$sql->Limpar();
	$perfis_arr = array();
	$i=0;
	foreach ($perfis as $perfil) {
		if ($i++ || $Aplic->usuario_super_admin) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
		}
	
	echo estiloTopoCaixa();
	echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="2">';
	echo '<tr><td align="right" width="400"  align="right">'.dica('Sistema à Importar', 'Escolha de qual sistema deseja irá importar os dados.').'Sistema à importar os dados:'.dicaF().'</td><td align="left"><select class="texto" name="banco" size="1" style="width:200px;" onchange="opcao_importar();"><option value="dotproject" >Web2Project/DotProject</option><option value="pecm">PECM v 2.5</option></select></td></tr>';
	echo '<tr><td align="right">'.dica('Endereço do Servidor Hospedeiro do Banco de Dados', 'Caso o '.$config['gpweb'].' (páginas PHP) esteja instalado na mesma máquina onde esteja o banco de dados MySQL provavelmente o endereço seja 127.0.0.1 (localhost).').'Endereço do Servidor(Host) do Banco de Dados:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="hospedadoBd" value="'.$config['hospedadoBd'].'" /></td></tr>';
	
	echo '<tr><td align="right">'.dica('Prefixo das Tabelas', 'Caso o haja um prefixo nas tabelas deverá ser prenchido<br>Exemplo:<br>Padrão dotproject: companies<br>Atual: dotp_companies<br>Prefixo: dotp_').'Prefixo das Tabelas:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="prefixo" value="" /></td></tr>';
	
	echo '<tr><td align="right">'.dica('Nome do Banco de Dados', 'Nome da base de dados que conterá todas as tabelas do '.$config['gpweb'].'.').'Nome do Banco de Dados:'.dicaF().'</td><td align="left"><input type="text" name="nomeBd" value="dotproject" class="texto" /></td></tr>';
	echo '<tr><td align="right">'.dica('Login do Administrador', 'Nome do administrador configurado no servidor.<br> Por <i>default</i> tem o nome <b>root</b>.').'Login do administrador do SGDB:'.dicaF().'</td><td align="left"><input type="text" class="texto" name="usuarioBd" value="'.$config['usuarioBd'].'" /></td></tr>';
	echo '<tr><td align="right">'.dica('Senha do Administrador', 'Senha utilizada pelo administrador para acessar o SGDB.').'Senha do Administrador do SGDB:'.dicaF().'</td><td align="left"><table cellpadding=0 cellspacing=0><tr><td><input type="password" name="senhaBd" class="texto" value="'.$config['senhaBd'].'" /></td><td>'.botao('testar&nbsp;conexão', 'Testar Conexão', 'Testar a conexão configurada para acessar o PECM.','','testar();').'</td></tr></table></td></tr>';
	echo '<tr><td align="right">'.dica('Número de linhas à Importar de Cada Vez', 'Defina o número de linhas a serem importadas de cada vez.<br><br>Caso escolha um número muito elevado há uma chance de o servidor interromper a importação por ter estourado o limite de tempo (em média 5 minutos).').'Número de linhas à importar de cada vez:'.dicaF().'</td><td align="left"><input type="text" name="linhas" class="texto" value="500" /></td></tr>';
	echo '<tr><td align="right">'.dica('Perfil de Acesso', 'Defina qual o perfil de acesso d'.$config['genero_usuario'].'s '.$config['usuarios'].' importados do PECM.').'Perfil de acesso d'.$config['genero_usuario'].'s '.$config['usuarios'].':'.dicaF().'</td><td align="left">'.selecionaVetor($perfis_arr, 'usuario_perfil', 'style="width:260px;" size="1" class="texto"', '').'</td></tr>';

	//opções do PECM
	echo '<tr id="pecm" style="display:none"><td colspan=20><table width="100%">';
	echo '<tr><td align="right">'.dica('Campo do PECM para Nome', 'Defina qual campo da tabela do PECM representará o nome d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Campo do PECM para o nome d'.$config['genero_usuario'].' '.$config['usuario'].':'.dicaF().'</td><td align="left"><input type="radio" class="std2" name="nome" value="nickname" />Nickname&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="std2" name="nome" value="funcao" checked="checked" />Funcao&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="std2" name="nome" value="nenhum" />Nenhum</td></tr>';
	echo '<tr><td align="right">'.dica('Campo do PECM para Função', 'Defina qual campo da tabela do PECM representará a função d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Campo do PECM para função d'.$config['genero_usuario'].' '.$config['usuario'].':'.dicaF().'</td><td align="left"><input type="radio" class="std2" name="funcao" value="nickname" checked="checked" />Nickname&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="std2" name="funcao" value="funcao" />Funcao&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="std2" name="funcao" value="nenhum" />Nenhum</td></tr>';
	echo '</table></td></tr>';

	echo '<tr><td align="right">'.dica('Tipo de Caracteres', 'Defina o tipo de caracter da base de dados da qual serão importados os dados.').'Tipo de caracteres:'.dicaF().'</td><td align="left"><input type="radio" name="formatacao" value="utf">UTF-8<input type="radio" name="formatacao" value="8859" checked="checked">ISO-8859-1</td></tr>';
	
	echo '<tr><td align="left" colspan=2>'.botao('importar', 'Importar', 'Importar os dados do aplicativo escolhido acima.','','if(confirm(\'Tem certeza que deseja importar? Todos as mensagens, projetos e usuários que por ventura estejam inseridos no '.$config['gpweb'].' serão excluídos.\')){env.submit()}').'</td></tr>';
	echo '</table>';	
	echo estiloFundoCaixa();
	}
else {
	echo '<input type="hidden" name="banco" value="'.$banco.'" />';
	echo '<input type="hidden" name="hospedadoBd" value="'.$hospedadoBd.'" />';
	echo '<input type="hidden" name="nomeBd" value="'.$nomeBd.'" />';
	echo '<input type="hidden" name="usuarioBd" value="'.$usuarioBd.'" />';
	echo '<input type="hidden" name="senhaBd" value="'.$senhaBd.'" />';
	echo '<input type="hidden" name="prefixo" value="'.$prefixo.'" />';
	
	$ok=1;
	try {
	  $db = new PDO('mysql:dbname='.$nomeBd.';host='.$hospedadoBd, $usuarioBd, $senhaBd);
		} 
	catch (PDOException $db) {
		echo estiloTopoCaixa();
		echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="2">';	
		echo '<tr><td align="left">Conexão falhou: '.$db->getMessage().'</td></tr>';
		echo '<tr><td align="left">'.botao('retornar', 'Retornar', 'Clique neste botão para retornar aos parâmetros do PECM.','','env.nomeBd.value=\'\'; env.submit();').'</td></tr>';
		
		if ($hospedadoBd!='127.0.0.1' && $hospedadoBd!='localhost'){
			echo '<tr><td align="left">';
			include_once BASE_DIR.'/modulos/sistema/como_conectar.php';
			echo '</td></tr>';
			}
		
		echo '</table>';	
		echo estiloFundoCaixa();
		$ok=0;
		}

//importar dotproject
	if ($tabela=='companies' && $ok){
		$resultado = $db->query('SELECT count(company_id) AS quantidade FROM '.$prefixo.'companies WHERE company_id!=1');

		if (!is_object($resultado)) erro_leitura('companies');
		$quantidade=$resultado->fetchColumn();
				
		if (!$feito){
			$sql->setExcluir('cias');
			$sql->adOnde('cia_id != 1');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela cias!');
			$sql->limpar();
			
			$sql->setExcluir('tarefa_h_custos');
			if (!$sql->exec()) echo ('Não foi possível os excluir dados da tarefa_h_custos!');
			$sql->limpar();
			
			$sql->setExcluir('tarefa_h_gastos');
			if (!$sql->exec()) echo ('Não foi possível os excluir dados da tarefa_h_custos!');
			$sql->limpar();
			
			$sql->setExcluir('tarefa_gastos');
			if (!$sql->exec()) echo ('Não foi possível os excluir dados da tarefa_gastos!');
			$sql->limpar();
			
			$sql->setExcluir('tarefa_custos');
			if (!$sql->exec()) echo ('Não foi possível os excluir dados da tarefa_custos!');
			$sql->limpar();
		
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="departments"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo '.$config['organizacoes'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';

		$resultado = $db->query('SELECT '.$prefixo.'companies.* FROM '.$prefixo.'companies LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		
		foreach ($lista as $linha) {
			
			if ($linha['company_id']==1){
				$sql->adTabela('cias');
				$sql->adAtualizar('cia_id', $linha['company_id']);
				$sql->adAtualizar('cia_nome_completo', formatacao($linha['company_name']));
				$sql->adAtualizar('cia_nome', formatacao($linha['company_name']));
				$sql->adAtualizar('cia_tel1', formatacao($linha['company_phone1']));
				$sql->adAtualizar('cia_tel2', formatacao($linha['company_phone2']));
				$sql->adAtualizar('cia_fax', formatacao($linha['company_fax']));
				$sql->adAtualizar('cia_endereco1', formatacao($linha['company_address1']));
				$sql->adAtualizar('cia_endereco2', formatacao($linha['company_address2']));
				$sql->adAtualizar('cia_cidade', formatacao($linha['company_city']));
				$sql->adAtualizar('cia_estado', formatacao($linha['company_state']));
				$sql->adAtualizar('cia_cep', formatacao($linha['company_zip']));
				$sql->adAtualizar('cia_url', formatacao($linha['company_primary_url']));
				$sql->adAtualizar('cia_responsavel', $linha['company_owner']);
				$sql->adAtualizar('cia_descricao', formatacao($linha['company_description']));
				$sql->adAtualizar('cia_tipo', formatacao($linha['company_type']));
				$sql->adAtualizar('cia_email', formatacao($linha['company_email']));
				$sql->adAtualizar('cia_customizado', formatacao($linha['company_custom']));
				$sql->adOnde('cia_id = '.(int)$linha['company_id']);
				if (!$sql->exec()) echo ('Não foi possível inserir na tabela anexos!');
				$sql->Limpar();
				}
			else {
				$sql->adTabela('cias');
				$sql->adInserir('cia_id', $linha['company_id']);
				$sql->adInserir('cia_nome_completo', formatacao($linha['company_name']));
				$sql->adInserir('cia_nome', formatacao($linha['company_name']));
				$sql->adInserir('cia_tel1', formatacao($linha['company_phone1']));
				$sql->adInserir('cia_tel2', formatacao($linha['company_phone2']));
				$sql->adInserir('cia_fax', formatacao($linha['company_fax']));
				$sql->adInserir('cia_endereco1', formatacao($linha['company_address1']));
				$sql->adInserir('cia_endereco2', formatacao($linha['company_address2']));
				$sql->adInserir('cia_cidade', formatacao($linha['company_city']));
				$sql->adInserir('cia_estado', formatacao($linha['company_state']));
				$sql->adInserir('cia_cep', formatacao($linha['company_zip']));
				$sql->adInserir('cia_url', formatacao($linha['company_primary_url']));
				$sql->adInserir('cia_responsavel', $linha['company_owner']);
				$sql->adInserir('cia_descricao', formatacao($linha['company_description']));
				$sql->adInserir('cia_tipo', formatacao($linha['company_type']));
				$sql->adInserir('cia_email', formatacao($linha['company_email']));
				$sql->adInserir('cia_customizado', formatacao($linha['company_custom']));
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir na tabela anexos!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='departments' && $ok){
		$resultado = $db->query('SELECT count(dept_id) AS quantidade FROM '.$prefixo.'departments');
		if (!is_object($resultado)) erro_leitura('departments');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('depts');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela depts!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="events"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo seções de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		
		$resultado = $db->query('SELECT '.$prefixo.'departments.* FROM '.$prefixo.'departments LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('depts');
			$sql->adInserir('dept_id', $linha['dept_id']);
			if ($linha['dept_parent']) $sql->adInserir('dept_superior', $linha['dept_parent']);
			if ($linha['dept_company']) $sql->adInserir('dept_cia', $linha['dept_company']);
			$sql->adInserir('dept_nome', formatacao($linha['dept_name']));
			$sql->adInserir('dept_tel', formatacao($linha['dept_phone']));
			$sql->adInserir('dept_fax', formatacao($linha['dept_fax']));
			$sql->adInserir('dept_endereco1', formatacao($linha['dept_address1']));
			$sql->adInserir('dept_endereco2', formatacao($linha['dept_address2']));
			$sql->adInserir('dept_cidade', formatacao($linha['dept_city']));
			$sql->adInserir('dept_estado', formatacao($linha['dept_state']));
			$sql->adInserir('dept_cep', formatacao($linha['dept_zip']));
			$sql->adInserir('dept_url', formatacao($linha['dept_url']));
			if ($linha['dept_owner']) $sql->adInserir('dept_responsavel', $linha['dept_owner']);
			$sql->adInserir('dept_descricao', formatacao($linha['dept_desc']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela depts!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='events' && $ok){
		$resultado = $db->query('SELECT count(event_id) AS quantidade FROM '.$prefixo.'events');
		if (!is_object($resultado)) erro_leitura('events');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('eventos');
			if (!$sql->exec()) echo ('Não foi possível excluir dados da tabela eventos!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="files"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo eventos de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'events.* FROM '.$prefixo.'events LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('events');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('eventos');
			$sql->adInserir('evento_id', $linha['event_id']);
			$sql->adInserir('evento_titulo', formatacao($linha['event_title']));
			$sql->adInserir('evento_inicio', $linha['event_start_date']);
			$sql->adInserir('evento_fim', $linha['event_end_date']);
			if ($linha['event_parent']) $sql->adInserir('evento_superior', $linha['event_parent']);
			$sql->adInserir('evento_descricao', formatacao($linha['event_description']));
			$sql->adInserir('evento_nr_recorrencias', $linha['event_times_recuring']);
			$sql->adInserir('evento_recorrencias', $linha['event_recurs']);
			$sql->adInserir('evento_lembrar', $linha['event_remind']);
			if ($linha['event_owner']) $sql->adInserir('evento_dono', $linha['event_owner']);
			if ($linha['event_project']) $sql->adInserir('evento_projeto', $linha['event_project']);
			$sql->adInserir('evento_tipo', formatacao($linha['event_type']));
			$sql->adInserir('evento_notificar', $linha['event_notify']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela eventos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='files' && $ok){
		$resultado = $db->query('SELECT count(file_id) AS quantidade FROM '.$prefixo.'files');
		if (!is_object($resultado)) erro_leitura('files');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('arquivos');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela arquivos!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="file_folders"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo arquivos de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		
		$resultado = $db->query('SELECT '.$prefixo.'files.* FROM '.$prefixo.'files LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('files');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('arquivos');
			$sql->adInserir('arquivo_id', $linha['file_id']);
			$sql->adInserir('arquivo_nome_real', formatacao($linha['file_real_filename']));
			if ($linha['file_folder']) $sql->adInserir('arquivo_pasta', $linha['file_folder']);
			if ($linha['file_project']) $sql->adInserir('arquivo_projeto', $linha['file_project']);
			if ($linha['file_task']) $sql->adInserir('arquivo_tarefa', $linha['file_task']);
			$sql->adInserir('arquivo_nome', formatacao($linha['file_name']));
			if ($linha['file_parent']) $sql->adInserir('arquivo_superior', $linha['file_parent']);
			$sql->adInserir('arquivo_descricao', formatacao($linha['file_description']));
			$sql->adInserir('arquivo_tipo', formatacao($linha['file_type']));
			if ($linha['file_owner']) $sql->adInserir('arquivo_dono', $linha['file_owner']);
			$sql->adInserir('arquivo_data', $linha['file_date']);
			$sql->adInserir('arquivo_tamanho', $linha['file_size']);
			$sql->adInserir('arquivo_versao', $linha['file_version']);
			$sql->adInserir('arquivo_categoria', formatacao($linha['file_category']));
			$sql->adInserir('arquivo_saida', formatacao($linha['file_checkout']));
			$sql->adInserir('arquivo_motivo_saida', formatacao($linha['file_co_reason']));
			$sql->adInserir('arquivo_versao_id', $linha['file_version_id']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela arquivos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='file_folders' && $ok){
		$resultado = $db->query('SELECT count(file_folder_id) AS quantidade FROM '.$prefixo.'file_folders');
		if (!is_object($resultado)) erro_leitura('file_folders');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('arquivo_pasta');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela arquivo_pasta!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="files_index"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}


		$saida='<tr><td>Inserindo pastas de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		
		
		$resultado = $db->query('SELECT '.$prefixo.'file_folders.* FROM '.$prefixo.'file_folders LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('file_folders');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('arquivo_pasta');
			$sql->adInserir('arquivo_pasta_id', $linha['file_folder_id']);
			if ($linha['file_folder_parent']) $sql->adInserir('arquivo_pasta_superior', $linha['file_folder_parent']);
			$sql->adInserir('arquivo_pasta_nome', formatacao($linha['file_folder_name']));
			$sql->adInserir('arquivo_pasta_descricao', formatacao($linha['file_folder_description']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela arquivo_pasta!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='files_index' && $ok){
		echo '<script>env.feito.value=0; env.tabela.value="forum_messages"; env.historico.value="'.$historico.'"; env.submit();</script>';
		exit();
		}


	if ($tabela=='forum_messages' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'forum_messages');
		if (!is_object($resultado)) erro_leitura('forum_messages');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('forum_mensagens');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela forum_mensagens!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="forum_visits"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo mensagens de fóruns  de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'forum_messages.* FROM '.$prefixo.'forum_messages LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('forum_messages');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('forum_mensagens');
			$sql->adInserir('mensagem_id', $linha['message_id']);
			if ($linha['message_forum']) $sql->adInserir('mensagem_forum', $linha['message_forum']);
			if ($linha['message_parent']) $sql->adInserir('mensagem_superior', $linha['message_parent']);
			if ($linha['message_author']) $sql->adInserir('mensagem_autor', $linha['message_author']);
			if ($linha['message_editor']) $sql->adInserir('mensagem_editor', $linha['message_editor']);
			$sql->adInserir('mensagem_titulo', formatacao($linha['message_title']));
			$sql->adInserir('mensagem_data', $linha['message_date']);
			$sql->adInserir('mensagem_texto', formatacao($linha['message_body']));
			$sql->adInserir('mensagem_publicada', $linha['message_published']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela forum_mensagens!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='forum_visits' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'forum_visits');
		if (!is_object($resultado)) erro_leitura('forum_visits');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('forum_visitas');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela forum_visitas!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="forum_watch"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo visitas nos fóruns de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'forum_visits.* FROM '.$prefixo.'forum_visits LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('forum_visits');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('forum_visitas');
			if ($linha['visit_user']) $sql->adInserir('visita_usuario', $linha['visit_user']);
			if ($linha['visit_forum']) $sql->adInserir('visita_forum', $linha['visit_forum']);
			if ($linha['visit_message']) $sql->adInserir('visita_mensagem', $linha['visit_message']);
			$sql->adInserir('visita_data', $linha['visit_date']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela forum_mensagens!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='forum_watch' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'forum_watch');
		if (!is_object($resultado)) erro_leitura('forum_watch');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('forum_acompanhar');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela forum_acompanhar!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="forums"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
	
		$saida='<tr><td>Inserindo visitas nos fóruns de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'forum_watch.* FROM '.$prefixo.'forum_watch LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('forum_watch');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('forum_acompanhar');
			if ($linha['watch_user']) $sql->adInserir('acompanhar_usuario', $linha['watch_user']);
			if ($linha['watch_forum']) $sql->adInserir('acompanhar_forum', $linha['watch_forum']);
			if ($linha['watch_topic']) $sql->adInserir('acompanhar_topico', $linha['watch_topic']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela forum_acompanhar!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='forums' && $ok){
		$resultado = $db->query('SELECT count(forum_id) AS quantidade FROM '.$prefixo.'forums');
		if (!is_object($resultado)) erro_leitura('forums');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('foruns');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela foruns!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="project_contacts"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
			
		$saida='<tr><td>Inserindo fóruns de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'forums.* FROM '.$prefixo.'forums LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('forums');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('foruns');
			$sql->adInserir('forum_id', $linha['forum_id']);
			if ($linha['forum_project']) $sql->adInserir('forum_projeto', $linha['forum_project']);
			$sql->adInserir('forum_status', formatacao($linha['forum_status']));
			if ($linha['forum_owner']) $sql->adInserir('forum_dono', $linha['forum_owner']);
			$sql->adInserir('forum_nome', formatacao($linha['forum_name']));
			$sql->adInserir('forum_data_criacao', $linha['forum_create_date']);
			$sql->adInserir('forum_ultima_data', $linha['forum_last_date']);
			$sql->adInserir('forum_ultimo_id', $linha['forum_last_id']);
			$sql->adInserir('forum_contagem_msg', $linha['forum_message_count']);
			$sql->adInserir('forum_descricao', formatacao($linha['forum_description']));
			if ($linha['forum_moderated']) $sql->adInserir('forum_moderador', $linha['forum_moderated']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela foruns!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}



	if ($tabela=='project_contacts' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'project_contacts');
		if (!is_object($resultado)) erro_leitura('project_contacts');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('projeto_contatos');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela projeto_contatos!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="project_departments"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo contatos do '.$config['projeto'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'project_contacts.* FROM '.$prefixo.'project_contacts LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('project_contacts');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['project_id'] && $linha['contact_id']){
				$sql->adTabela('projeto_contatos');
				$sql->adInserir('projeto_id', $linha['project_id']);
				$sql->adInserir('contato_id', $linha['contact_id']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela projeto_contatos!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='project_departments' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'project_departments');
		if (!is_object($resultado)) erro_leitura('project_departments');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('projeto_depts');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela projeto_depts!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="projects"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo seções dos '.$config['projetos'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'project_departments.* FROM '.$prefixo.'project_departments LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('project_departments');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['project_id'] && $linha['department_id']){
				$sql->adTabela('projeto_depts');
				$sql->adInserir('projeto_id', $linha['project_id']);
				$sql->adInserir('departamento_id', $linha['department_id']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela projeto_depts!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	if ($tabela=='projects' && $ok){
		$resultado = $db->query('SELECT count(project_id) AS quantidade FROM '.$prefixo.'projects');
		if (!is_object($resultado)) erro_leitura('projects');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('projetos');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela projeto_depts!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="task_contacts"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo os '.$config['projetos'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'projects.* FROM '.$prefixo.'projects LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('projects');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('projetos');
			$sql->adInserir('projeto_id', $linha['project_id']);
			if ($linha['project_company']) $sql->adInserir('projeto_cia', $linha['project_company']);
			$sql->adInserir('projeto_nome', formatacao($linha['project_name']));
			$sql->adInserir('projeto_nome_curto', formatacao($linha['project_short_name']));
			if ($linha['project_owner']) $sql->adInserir('projeto_responsavel', $linha['project_owner']);
			$sql->adInserir('projeto_url', formatacao($linha['project_url']));
			$sql->adInserir('projeto_url_externa', formatacao($linha['project_demo_url']));
			$sql->adInserir('projeto_data_inicio', $linha['project_start_date']);
			$sql->adInserir('projeto_data_fim', $linha['project_end_date']);
			if (isset($linha['project_actual_end_date']) && $linha['project_actual_end_date'])$sql->adInserir('projeto_fim_atualizado', $linha['project_actual_end_date']);
			$sql->adInserir('projeto_status', (int)$linha['project_status']);
			$sql->adInserir('projeto_percentagem', $linha['project_percent_complete']);
			$sql->adInserir('projeto_cor', formatacao($linha['project_color_identifier']));
			$sql->adInserir('projeto_superior', $linha['project_id']);
			$sql->adInserir('projeto_superior_original', $linha['project_id']);
			$sql->adInserir('projeto_descricao', formatacao($linha['project_description']));
			$sql->adInserir('projeto_meta_custo', formatacao($linha['project_target_budget']));
			$sql->adInserir('projeto_custo_atual', formatacao($linha['project_actual_budget']));
			if ($linha['project_creator']) $sql->adInserir('projeto_criador', $linha['project_creator']);
			$sql->adInserir('projeto_privativo', $linha['project_private']);
			$sql->adInserir('projeto_prioridade', formatacao($linha['project_priority']));
			$sql->adInserir('projeto_tipo', formatacao($linha['project_type']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela projetos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}



	if ($tabela=='task_contacts' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'task_contacts');
		if (!is_object($resultado)) erro_leitura('task_contacts');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefa_contatos');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela projeto_depts!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="task_departments"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo os contatos das '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'task_contacts.* FROM '.$prefixo.'task_contacts LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('task_contacts');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['task_id'] && $linha['contact_id']){
				$sql->adTabela('tarefa_contatos');
				$sql->adInserir('tarefa_id', $linha['task_id']);
				$sql->adInserir('contato_id', $linha['contact_id']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela tarefa_contatos!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
		
	if ($tabela=='task_departments' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'task_departments');
		if (!is_object($resultado)) erro_leitura('task_departments');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefa_depts');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela tarefa_depts!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="task_dependencies"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo '.$config['genero_dept'].'s '.$config['departamentos'].' d'.$config['genero_tarefa'].'s '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'task_departments.* FROM '.$prefixo.'task_departments LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('task_departments');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['task_id'] && $linha['department_id']){
				$sql->adTabela('tarefa_depts');
				$sql->adInserir('tarefa_id', $linha['task_id']);
				$sql->adInserir('departamento_id', $linha['department_id']);
				$sql->sem_chave_estrangeira();
				$sql->exec();
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
		
	if ($tabela=='task_dependencies' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'task_dependencies');
		if (!is_object($resultado)) erro_leitura('task_dependencies');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefa_dependencias');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela tarefa_dependencias!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="task_log"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo as predecessoras das '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'task_dependencies.* FROM '.$prefixo.'task_dependencies LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('task_dependencies');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['dependencies_task_id'] && $linha['dependencies_req_task_id']){
				$sql->adTabela('tarefa_dependencias');
				$sql->adInserir('dependencias_tarefa_id', $linha['dependencies_task_id']);
				$sql->adInserir('dependencias_req_tarefa_id', $linha['dependencies_req_task_id']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela tarefa_dependencias!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		

	
	if ($tabela=='task_log' && $ok){
		$resultado = $db->query('SELECT count(task_log_id) AS quantidade FROM '.$prefixo.'task_log');
		if (!is_object($resultado)) erro_leitura('task_log');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefa_log');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela tarefa_log!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="tasks"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo os Logs das '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'task_log.* FROM '.$prefixo.'task_log LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('task_log');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('tarefa_log');
			$sql->adInserir('tarefa_log_id', $linha['task_log_id']);
			if ($linha['task_log_task']) $sql->adInserir('tarefa_log_tarefa', $linha['task_log_task']);
			$sql->adInserir('tarefa_log_nome', formatacao($linha['task_log_name']));
			$sql->adInserir('tarefa_log_descricao', formatacao($linha['task_log_description']));
			if ($linha['task_log_creator']) $sql->adInserir('tarefa_log_criador', $linha['task_log_creator']);
			$sql->adInserir('tarefa_log_horas', $linha['task_log_hours']);
			$sql->adInserir('tarefa_log_data', $linha['task_log_date']);
			$sql->adInserir('tarefa_log_problema', formatacao($linha['task_log_problem']));
			$sql->adInserir('tarefa_log_referencia', formatacao($linha['task_log_reference']));
			$sql->adInserir('tarefa_log_url_relacionada', formatacao($linha['task_log_related_url']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela tarefa_log!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		
	
	if ($tabela=='tasks' && $ok){
		$resultado = $db->query('SELECT count(task_id) AS quantidade FROM '.$prefixo.'tasks');
		if (!is_object($resultado)) erro_leitura('tasks');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefas');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela tarefas!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="contacts"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo as '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'tasks.* FROM '.$prefixo.'tasks LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('tasks');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('tarefas');
			$sql->adInserir('tarefa_id', $linha['task_id']);
			$sql->adInserir('tarefa_nome', formatacao($linha['task_name']));
			if ($linha['task_parent']) $sql->adInserir('tarefa_superior', $linha['task_parent']);
			if ($linha['task_duration']==0) $sql->adInserir('tarefa_marco', 1);
			else $sql->adInserir('tarefa_marco', $linha['task_milestone']);
			if ($linha['task_project']) $sql->adInserir('tarefa_projeto', $linha['task_project']);
			if ($linha['task_owner']) $sql->adInserir('tarefa_dono', $linha['task_owner']);
			$sql->adInserir('tarefa_inicio', $linha['task_start_date']);
			$sql->adInserir('tarefa_duracao', $linha['task_duration']);
			$sql->adInserir('tarefa_duracao_tipo', $linha['task_duration_type']);
			$sql->adInserir('tarefa_horas_trabalhadas', $linha['task_hours_worked']);
			$sql->adInserir('tarefa_fim', $linha['task_end_date']);
			$sql->adInserir('tarefa_prioridade', formatacao($linha['task_priority']));
			$sql->adInserir('tarefa_percentagem', $linha['task_percent_complete']);
			$sql->adInserir('tarefa_descricao', formatacao($linha['task_description']));
			$sql->adInserir('tarefa_custo_almejado', $linha['task_target_budget']);
			$sql->adInserir('tarefa_url_relacionada', formatacao($linha['task_related_url']));
			if ($linha['task_creator']) $sql->adInserir('tarefa_criador', $linha['task_creator']);
			$sql->adInserir('tarefa_ordem', $linha['task_order']);
			$sql->adInserir('tarefa_cliente_publicada', $linha['task_client_publish']);
			$sql->adInserir('tarefa_dinamica', $linha['task_dynamic']);
			$sql->adInserir('tarefa_acesso', $linha['task_access']);
			$sql->adInserir('tarefa_notificar', $linha['task_notify']);
			$sql->adInserir('tarefa_tipo', formatacao($linha['task_type']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela tarefas!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		
	
	


	if ($tabela=='contacts' && $ok){
		$resultado = $db->query('SELECT count(contact_id) AS quantidade FROM '.$prefixo.'contacts WHERE contact_id!=1');
		if (!is_object($resultado)) erro_leitura('contacts');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('contatos');
			$sql->adOnde('contato_id != 1');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela contatos!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="user_events"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
	
		
		$saida='<tr><td>Inserindo os contatos de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'contacts.* FROM '.$prefixo.'contacts WHERE contact_id>1 LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('contacts');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('contatos');
			$sql->adInserir('contato_id', $linha['contact_id']);
			$sql->adInserir('contato_nomeguerra', formatacao($linha['contact_first_name'].($linha['contact_first_name'] && $linha['contact_last_name'] ? ' ':'').$linha['contact_last_name']));
			$sql->adInserir('contato_nomecompleto', formatacao($linha['contact_first_name'].($linha['contact_first_name'] && $linha['contact_last_name'] ? ' ':'').$linha['contact_last_name']));
			$sql->adInserir('contato_ordem', $linha['contact_order_by']);
			$sql->adInserir('contato_posto', formatacao($linha['contact_title']));
			$sql->adInserir('contato_nascimento', $linha['contact_birthday']);
			$sql->adInserir('contato_funcao', formatacao($linha['contact_job']));
			if ($linha['contact_company']) $sql->adInserir('contato_cia', $linha['contact_company']);
			if ($linha['contact_department']) $sql->adInserir('contato_dept', $linha['contact_department']);
			$sql->adInserir('contato_tipo', formatacao($linha['contact_type']));
			$sql->adInserir('contato_email', formatacao($linha['contact_email']));
			$sql->adInserir('contato_email2', formatacao($linha['contact_email2']));
			$sql->adInserir('contato_url', formatacao($linha['contact_url']));
			$sql->adInserir('contato_tel', formatacao($linha['contact_phone']));
			$sql->adInserir('contato_tel2', formatacao($linha['contact_phone2']));
			$sql->adInserir('contato_fax', formatacao($linha['contact_fax']));
			$sql->adInserir('contato_cel', formatacao($linha['contact_mobile']));
			$sql->adInserir('contato_endereco1', formatacao($linha['contact_address1']));
			$sql->adInserir('contato_endereco2', formatacao($linha['contact_address2']));
			$sql->adInserir('contato_cidade', formatacao($linha['contact_city']));
			$sql->adInserir('contato_estado', formatacao($linha['contact_state']));
			$sql->adInserir('contato_cep', formatacao($linha['contact_zip']));
			$sql->adInserir('contato_pais', formatacao($linha['contact_country']));
			$sql->adInserir('contato_jabber', formatacao($linha['contact_jabber']));
			$sql->adInserir('contato_icq', formatacao($linha['contact_icq']));
			$sql->adInserir('contato_msn', formatacao($linha['contact_msn']));
			$sql->adInserir('contato_yahoo', formatacao($linha['contact_yahoo']));
			$sql->adInserir('contato_notas', formatacao($linha['contact_notes']));
			if ($linha['contact_owner']) $sql->adInserir('contato_dono', $linha['contact_owner']);
			$sql->adInserir('contato_privado', $linha['contact_private']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela contatos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		

	
	
	if ($tabela=='user_events' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'user_events');
		if (!is_object($resultado)) erro_leitura('user_events');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('evento_usuarios');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela evento_usuarios!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="user_task_pin"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo os designados para eventos de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'user_events.* FROM '.$prefixo.'user_events LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('user_events');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['user_id'] && $linha['event_id']) {
				$sql->adTabela('evento_usuarios');
				$sql->adInserir('usuario_id', $linha['user_id']);
				$sql->adInserir('evento_id', $linha['event_id']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela evento_usuarios!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		
	
	
	if ($tabela=='user_task_pin' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'user_task_pin');
		if (!is_object($resultado)) erro_leitura('user_task_pin');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('usuario_tarefa_marcada');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela usuario_tarefa_marcada!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="user_tasks"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo os eventos marcados dos usuários de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'user_task_pin.* FROM '.$prefixo.'user_task_pin LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('user_task_pin');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['user_id'] && $linha['task_id']) {
				$sql->adTabela('usuario_tarefa_marcada');
				$sql->adInserir('usuario_id', $linha['user_id']);
				$sql->adInserir('tarefa_id', $linha['task_id']);
				$sql->adInserir('tarefa_marcada', $linha['task_pinned']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela usuario_tarefa_marcada!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}		
	


	if ($tabela=='user_tasks' && $ok){
		$resultado = $db->query('SELECT count(*) AS quantidade FROM '.$prefixo.'user_tasks');
		if (!is_object($resultado)) erro_leitura('user_tasks');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('tarefa_designados');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela tarefa_designados!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="users"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo os designados das '.$config['tarefas'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'user_tasks.* FROM '.$prefixo.'user_tasks LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('user_tasks');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			if ($linha['user_id'] && $linha['task_id']) {
				$sql->adTabela('tarefa_designados');
				$sql->adInserir('usuario_id', $linha['user_id']);
				if ($linha['user_type']) $sql->adInserir('usuario_admin', $linha['user_type']);
				$sql->adInserir('tarefa_id', $linha['task_id']);
				$sql->adInserir('perc_designado', $linha['perc_assignment']);
				$sql->adInserir('usuario_tarefa_prioridade', $linha['user_task_priority']);
				$sql->sem_chave_estrangeira();
				if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela tarefa_designados!');
				$sql->Limpar();
				}
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}



	if ($tabela=='users' && $ok){
		$resultado = $db->query('SELECT count(user_id) AS quantidade FROM '.$prefixo.'users WHERE user_id!=1');
		if (!is_object($resultado)) erro_leitura('users');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('usuarios');
			$sql->adOnde('usuario_id != 1');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela usuarios!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="permissoes"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo os usuários de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT '.$prefixo.'users.* FROM '.$prefixo.'users WHERE user_id!=1 LIMIT '.$feito.','.$linhas);
		if (!is_object($resultado)) erro_leitura('users');
		$lista=$resultado->fetchAll();	
		foreach ($lista as $linha) {
			$sql->adTabela('usuarios');
			$sql->adInserir('usuario_id', $linha['user_id']);
			$sql->adInserir('usuario_contato', $linha['user_contact']);
			$sql->adInserir('usuario_login', formatacao($linha['user_username']));
			$sql->adInserir('usuario_senha', formatacao($linha['user_password']));
			$sql->adInserir('usuario_superior', $linha['user_parent']);
			$sql->adInserir('usuario_rodape', formatacao($linha['user_type']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir dados na tabela usuarios!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


	
		
//importar PECM

	if ($tabela=='anexos' && $ok){
		$resultado = $db->query('SELECT count(id_anexo) AS quantidade FROM anexos');
		if (!is_object($resultado)) erro_leitura('anexos');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('anexos');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela anexos!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="usuario"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo anexos de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT anexos.*, Usuario.Nickname, Usuario.funcao, mensagens.data_envio FROM anexos LEFT JOIN Usuario ON (Usuario.UsuarioID=anexos.usuarioID) LEFT JOIN mensagens ON (mensagens.msgID=anexos.id_msg) LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();	


		foreach ($lista as $linha) {
			$sql->adTabela('anexos');
			$sql->adInserir('msg_id', $linha['id_msg']);
			$sql->adInserir('nome', formatacao($linha['nome']));
			$sql->adInserir('caminho', $linha['caminho']);
			$sql->adInserir('usuario_id', $linha['usuarioID']);
			$sql->adInserir('tipo_doc', $linha['tipo_doc']);
			$sql->adInserir('doc_nr', $linha['doc_nr']);
			$sql->adInserir('nome_de', formatacao(escolhenome($linha['Nickname'], $linha['funcao'])));
			$sql->adInserir('funcao_de', formatacao(escolhefuncao($linha['Nickname'], $linha['funcao'])));
			$sql->adInserir('data_envio', date('Y-m-d H:i:s'));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela anexos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	
	if ($tabela=='usuario' && $ok){
		$resultado = $db->query('SELECT count(UsuarioID) AS quantidade FROM Usuario WHERE UsuarioID!=1');
		if (!is_object($resultado)) erro_leitura('Usuario');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('usuarios');
			$sql->adOnde('usuario_id!=1');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela usuarios!');
			$sql->limpar();
			$sql->setExcluir('contatos');
			$sql->adOnde('contato_id!=1');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela contatos!');
			$sql->limpar();
			}

		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="mensagens"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo '.$config['usuarios'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT Usuario.* FROM Usuario WHERE UsuarioID>1');
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('usuarios');
			$sql->adInserir('usuario_id', $linha['UsuarioID']);
			$sql->adInserir('usuario_contato', $linha['UsuarioID']);
			$sql->adInserir('usuario_login', formatacao($linha['Nickname']));
			$sql->adInserir('usuario_senha', formatacao($linha['senha']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela anexos!');
			$sql->Limpar();
			$sql->adTabela('contatos');
			$sql->adInserir('contato_id', $linha['UsuarioID']);
			$sql->adInserir('contato_nomeguerra', formatacao(escolhenome($linha['Nickname'], $linha['funcao'])));
			$sql->adInserir('contato_funcao',formatacao(escolhefuncao($linha['Nickname'], $linha['funcao'])));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela anexos!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='mensagens' && $ok){
		$resultado = $db->query('SELECT count(msgID) AS quantidade FROM mensagens');
		if (!is_object($resultado)) erro_leitura('mensagens');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('msg');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela msg!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="usuario_msg"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo mensagens de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$class_sigilosa=array(0 => 5, 1 => 4, 2 => 3,  3=> 2, 4 => 1, 5 => 0);
		$precedencia=array(0 => 4, 1 => 3, 2 => 2,  3=> 1, 4 => 0);
		$resultado = $db->query('SELECT mensagens.*, Usuario.Nickname, Usuario.funcao FROM mensagens LEFT JOIN Usuario ON (Usuario.UsuarioID=mensagens.deID) LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('msg');
			$sql->adInserir('msg_id', $linha['msgID']);
			$sql->adInserir('precedencia', $precedencia[(int)$linha['precedencia']]);
			$sql->adInserir('class_sigilosa', $class_sigilosa[(int)$linha['class_sigilosa']]);
			$sql->adInserir('referencia', formatacao($linha['referencia']));
			$sql->adInserir('de_id', $linha['deID']);
			$sql->adInserir('texto', formatacao($linha['texto']));
			$sql->adInserir('data_envio', $linha['data_envio']);
			$sql->adInserir('nome_de', formatacao(escolhenome($linha['Nickname'], $linha['funcao'])));
			$sql->adInserir('funcao_de', formatacao(escolhefuncao($linha['Nickname'], $linha['funcao'])));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela msg!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
	
	if ($tabela=='usuario_msg' && $ok){
		if (!is_object($resultado)) erro_leitura('usuario_msg');
		$resultado = $db->query('SELECT count(*) AS quantidade FROM usuario_msg');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('msg_usuario');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela msg_usuario!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="anotacao"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		$saida='<tr><td>Inserindo usuários das mensagens de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT usuario_msg.*, de.Nickname AS de_nickname, de.funcao AS de_funcao, para.Nickname AS para_nickname, para.funcao AS para_funcao, data_envio FROM usuario_msg LEFT JOIN Usuario AS de ON (de.UsuarioID=usuario_msg.deID) LEFT JOIN Usuario AS para ON (para.UsuarioID=usuario_msg.paraID) LEFT JOIN mensagens ON (mensagens.msgID=usuario_msg.msgID) LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $linha['deID']);
			$sql->adInserir('para_id', $linha['paraID']);
			$sql->adInserir('msg_id', $linha['msgID']);
			$sql->adInserir('status', formatacao($linha['status']));
			$sql->adInserir('tipo', $linha['tipo']);
			$sql->adInserir('datahora', ($linha['datahora'] ? $linha['datahora'] : $linha['data_envio']));
			$sql->adInserir('datahora_leitura', $linha['datahora_leitura']);
			$sql->adInserir('nome_de', formatacao(escolhenome($linha['de_nickname'], $linha['de_funcao'])));
			$sql->adInserir('funcao_de', formatacao(escolhefuncao($linha['de_nickname'], $linha['de_funcao'])));
			$sql->adInserir('nome_para', formatacao(escolhenome($linha['para_nickname'], $linha['para_funcao'])));
			$sql->adInserir('funcao_para', formatacao(escolhefuncao($linha['para_nickname'], $linha['para_funcao'])));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela msg_usuario!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
	
	if ($tabela=='anotacao' && $ok){
		$resultado = $db->query('SELECT count(anotID) AS quantidade FROM anotacao');
		if (!is_object($resultado)) erro_leitura('anotacao');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('anotacao');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela anotacao!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="UsuarioGrupo"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo anotações das mensagens de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT * FROM anotacao LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('anotacao');
			$sql->adInserir('msg_id', $linha['msgID']);
			$sql->adInserir('msg_usuario_id', $linha['usuarioID']);
			$sql->adInserir('datahora', $linha['datahora']);
			$sql->adInserir('texto', formatacao($linha['texto']));
			$sql->adInserir('tipo', formatacao($linha['tipo']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela msg_usuario!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='UsuarioGrupo' && $ok){	
		$resultado = $db->query('SELECT count(*) AS quantidade FROM UsuarioGrupo');
		if (!is_object($resultado)) erro_leitura('UsuarioGrupo');
		$quantidade=$resultado->fetchColumn();
		


		if (!$feito){
			$sql->setExcluir('usuariogrupo');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela UsuarioGrupo!');
			$sql->limpar();
			}

		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="grupo"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo usuários nos grupos de mensagens de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		

		$resultado = $db->query('SELECT * FROM UsuarioGrupo LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();

		foreach ($lista as $linha) {
			$sql->adTabela('usuariogrupo');
			$sql->adInserir('usuario_id', $linha['UsuarioID']);
			$sql->adInserir('grupo_id', $linha['GrupoID']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela msg_usuario!');
			$sql->Limpar();
			}
		
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
	
	if ($tabela=='grupo' && $ok){		
		$resultado = $db->query('SELECT count(GrupoID) AS quantidade FROM Grupo');
		if (!is_object($resultado)) erro_leitura('Grupo');
		$quantidade=$resultado->fetchColumn();
		
		if (!$feito){
			$sql->setExcluir('grupo');
			$sql->adOnde('grupo_id > 1');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela grupo!');
			$sql->limpar();
			}
	
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="unidade"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo usuários nos grupos de mensagens de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT * FROM Grupo WHERE GrupoID>1 LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('grupo');
			$sql->adInserir('grupo_id', $linha['GrupoID']);
			$sql->adInserir('grupo_descricao', formatacao($linha['Descricao']));
			$sql->adInserir('grupo_cia', $linha['unidade']);
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela grupo!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}
	
	if ($tabela=='unidade' && $ok){
		$resultado = $db->query('SELECT count(cod) AS quantidade FROM unidade WHERE cod!=1');
		if (!is_object($resultado)) erro_leitura('unidade');
		$quantidade=$resultado->fetchColumn();
		if (!$feito){
			$sql->setExcluir('cias');
			$sql->adOnde('cia_id !=1');
			if (!$sql->exec()) echo ('Não foi possível excluir da tabela cias!');
			$sql->limpar();
			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="permissoes"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}
		
		$saida='<tr><td>Inserindo '.$config['organizacoes'].' de '.($feito+1).' até '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		$resultado = $db->query('SELECT * FROM unidade LIMIT '.$feito.','.$linhas);
		$lista=$resultado->fetchAll();
		foreach ($lista as $linha) {
			$sql->adTabela('cias');
			$sql->adInserir('cia_id', $linha['cod']);
			$sql->adInserir('cia_nome', formatacao($linha['descricao']));
			$sql->sem_chave_estrangeira();
			if (!$sql->exec()) echo ('Não foi possível inserir na tabela cias!');
			$sql->Limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}


////comum ao dotproject e PECM
if ($tabela=='permissoes' && $ok){
		$sql->adTabela('usuarios');
		$sql->adCampo('count(usuario_id)');
		$sql->adOnde('usuario_id>1');
		$quantidade=$sql->Resultado();
		$sql->Limpar();
		if (!$feito){
			$sql->setExcluir('perfil_usuario');
			$sql->adOnde('perfil_usuario_usuario!=1');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela perfil_usuario!');
			$sql->limpar();
			
			$sql->setExcluir('preferencia');
			$sql->adOnde('usuario>1');
			if (!$sql->exec()) echo ('Não foi possível excluir os dados da tabela perfil_usuario!');
			$sql->limpar();

			}
		if ($quantidade <= $feito){
			echo '<script>env.feito.value=0; env.tabela.value="fim"; env.historico.value="'.$historico.'"; env.submit();</script>';
			exit();
			}

		
		$saida='<tr><td>Inserindo permissões do '.($feito+1).'° até o '.($quantidade >=($feito+$linhas)? ($feito+$linhas+1) : $quantidade).'° '.$config['usuario'].'</td></tr>';
		$historico=$saida.$historico;
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		
		
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_id');
		$sql->adOnde('usuario_id>1');
		$sql->setLimite($feito, $linhas);
		$lista=$sql->carregarColuna();
		$sql->Limpar();
		
		foreach ($lista as $usuario_id) {
			$sql->adTabela('perfil_usuario');
			$sql->adInserir('perfil_usuario_usuario', $usuario_id);
			$sql->adInserir('perfil_usuario_perfil', $usuario_perfil);
			$sql->exec();
			$sql->limpar();	
			
			$sql->adTabela('preferencia');
			$sql->adInserir('usuario', $usuario_id);
			$sql->exec();
			$sql->limpar();
			}
		echo '<script>env.feito.value='.($feito+$linhas).'; env.historico.value="'.$historico.'"; env.submit();</script>';
		}

	if ($tabela=='fim' && $ok){
		
		if ($banco=='dotproject'){
			include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_id');
			$projetos = $sql->carregarColuna();
			$sql->limpar();
			foreach($projetos as $projeto_id) atualizar_percentagem($projeto_id);
			}
		
		echo estiloTopoCaixa();
		echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="2">';	
		if ($banco=='pecm') echo '<tr><td>Copie e cole as pastas dentro de anexo no PECM (pecm/anexos) para a pasta anexos no '.$config['gpweb'].' (server/anexos)</td></tr>';
		elseif ($banco=='dotproject') echo '<tr><td>Copie e cole as pastas dentro de files no dotproject (dotproject/files) para a pasta arquivos/projetos no '.$config['gpweb'].' (server/arquivos/projetos)</td></tr>';
		echo '</table>';	
		echo '<table align=center cellpadding=0 cellspacing=0 class="tbl1" width="100%"><tr><td>Histórico</td></tr>'.$historico.'</table>';
		echo estiloFundoCaixa();
		}

	}

echo '</form>';

function formatacao($texto){
	global $formatacao;
	return ($formatacao=='utf' ? utf8_decode($texto) : $texto);
	}


function escolhenome($nomedado='', $funcaodado=''){
	GLOBAL $nome;
	if ($nome=='nickname') return $nomedado;
	elseif ($nome=='funcao') return $funcaodado;
	else return '';
	}

function escolhefuncao($nomedado='', $funcaodado=''){
	GLOBAL $funcao;
	if ($funcao=='nickname') return $nomedado;
	elseif ($funcao=='funcao') return $funcaodado;
	else return '';
	}	


function erro_leitura($tabela){
	echo estiloTopoCaixa();
	echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="2">';	
	echo '<tr><td>Não foi possível ler os dados da tabela '.$tabela.'</td></tr>';
	echo '<tr><td>O processo de importação foi interrompido.</td></tr>';
	echo '</table>';	
	echo estiloFundoCaixa();
	exit();
	}

	
?>

<script language="JavaScript">
function testar(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Teste', 500, 500, 'm=sistema&a=testar&dialogo=1&hospedadoBd='+env.hospedadoBd.value+'&nomeBd='+env.nomeBd.value+'&usuarioBd='+env.usuarioBd.value+'&senhaBd='+env.senhaBd.value, null, window);
	else window.open('./index.php?m=sistema&a=testar&dialogo=1&hospedadoBd='+env.hospedadoBd.value+'&nomeBd='+env.nomeBd.value+'&usuarioBd='+env.usuarioBd.value+'&senhaBd='+env.senhaBd.value,'','height=500,width=700,resizable,scrollbars=yes');
	}
	
function opcao_importar(){	
	var opacao=env.banco.value;
	
	if (opacao=="pecm") {
		document.getElementById('pecm').style.display="";
		env.nomeBd.value="pecm";
		}
	else {
		document.getElementById('pecm').style.display="none";
		env.nomeBd.value="dotproject";
		}
	}
</script>	
		
