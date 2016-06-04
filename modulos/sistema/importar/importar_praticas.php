<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

//importar projetos e praticas de gestao

/*falta implementar Juliana
estrategias_composicao
objetivos_estrategicos_composicao
objetivos_estrategicos_log
plano_gestao_principios
pratica_indicador_composicao
pratica_indicador_formula
pratica_log
*/
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
if (!$dialogo) $Aplic->salvarPosicao();
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : ($Aplic->usuario_pode_todos_depts ? null : $Aplic->usuario_dept);

include_once BASE_DIR.'/modulos/sistema/importar/tabela_txt.class.php';

$exportar = getParam($_REQUEST, 'exportar', 0);
$importar = getParam($_REQUEST, 'importar', 0);

$importar_parte1 = getParam($_REQUEST, 'importar_parte1', 0);
$importar_parte2 = getParam($_REQUEST, 'importar_parte2', 0);
$importar_parte3 = getParam($_REQUEST, 'importar_parte3', 0);
$importar_parte4 = getParam($_REQUEST, 'importar_parte4', 0);
$nome_arquivo = getParam($_REQUEST, 'nome_arquivo', 0);

$saida_obj_estrategico = getParam($_REQUEST, 'saida_obj_estrategico', '');
$saida_nova_estrategia = getParam($_REQUEST, 'saida_nova_estrategia', '');
$saida_nova_meta = getParam($_REQUEST, 'saida_nova_meta', '');
$saida_novo_indicador = getParam($_REQUEST, 'saida_novo_indicador', '');
$buf = ''; 
if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


echo '<form name="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="importar_praticas" />';
echo '<input type="hidden" name="u" value="importar" />';
echo '<input type="hidden" name="exportar" value="0" />';
echo '<input type="hidden" name="importar" value="0" />';

echo '<input type="hidden" name="importar_parte1" value="0" />';
echo '<input type="hidden" name="importar_parte2" value="0" />';
echo '<input type="hidden" name="importar_parte3" value="0" />';
echo '<input type="hidden" name="importar_parte4" value="0" />';
echo '<input type="hidden" name="nome_arquivo" value="'.$nome_arquivo.'" />';

echo '<input type="hidden" name="saida_obj_estrategico" value="'.$saida_obj_estrategico.'" />';
echo '<input type="hidden" name="saida_nova_estrategia" value="'.$saida_nova_estrategia.'" />';
echo '<input type="hidden" name="saida_nova_meta" value="'.$saida_nova_meta.'" />';
echo '<input type="hidden" name="saida_novo_indicador" value="'.$saida_novo_indicador.'" />';

$sql = new BDConsulta();
$abortar = false;  
if ($importar){
	$arquivo = getParam($_REQUEST, 'arquivo', '');
	if (isset($_FILES['arquivo'])) {	
		$upload = $_FILES['arquivo'];
		if ($upload['size'] < 1){
            echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
            $abortar = true;
            }
		else {
            $extensao = substr($_FILES['arquivo']['name'], -3, 3);
            if ($extensao=='txt') $nome=str_replace('.txt', '', $_FILES['arquivo']['name']);
            else $nome=str_replace('.txt.gz', '', $_FILES['arquivo']['name']);
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
            if ($extensao!='txt'){
                $arquivo = @gzopen($base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name'], 'rb');
                if ($arquivo) {
                    $dados = '';
                    while (!gzeof($arquivo)) $dados .= gzread($arquivo, 1024);
                    gzclose($arquivo);
                    }
                $fp = fopen($base_dir.'/arquivos/temp/'.$nome.'.txt', 'w');
                fwrite($fp, $dados);
                }
            echo '<script>env.nome_arquivo.value="'.$nome.'"</script>';
			}
		}	
	else{
        $abortar = true;
        echo '<script>alert("Não foi enviado nenhum arquivo.")</script>';
        }
    if(!$abortar){
        $handle = @fopen($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt', 'r');
        if(!$handle){
            $abortar = true;
            echo '<script>alert("Houve um erro com o arquivo, verifique se o arquivo é valido.")</script>';
            }
        else fclose($handle);
    }
    
    if(!$abortar){
        $sql->adTabela('plano_gestao');
        $sql->adCampo('pg_id');
        $sql->adOnde('pg_cia='.(int)$cia_id);
        if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
				else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
        $lista = $sql->ListaChaveSimples();
        $sql->limpar();
        $lista_plano_gestao=array();
        foreach($lista as $linha) $lista_plano_gestao[]=$linha['pg_id'];

        $lista_plano_gestao=implode(',',$lista_plano_gestao); 

        echo 'Excluindo os dados antigos';


        if ($lista_plano_gestao){		
            $sql->setExcluir('plano_gestao');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar();

            $sql->setExcluir('plano_gestao2');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar();

            $sql->setExcluir('plano_gestao_ameacas');
            $sql->adOnde('pg_ameaca_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar();  

            $sql->setExcluir('plano_gestao_diretrizes');
            $sql->adOnde('pg_diretriz_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_diretrizes_superiores');
            $sql->adOnde('pg_diretriz_superior_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_estrategias');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 


            $sql->setExcluir('plano_gestao_fatores_criticos');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 


            $sql->setExcluir('plano_gestao_fornecedores');
            $sql->adOnde('pg_fornecedor_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_metas');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_objetivos_estrategicos');
            $sql->adOnde('pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_oportunidade');
            $sql->adOnde('pg_oportunidade_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_oportunidade_melhorias');
            $sql->adOnde('pg_oportunidade_melhoria_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_pessoal');
            $sql->adOnde('pg_pessoal_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_pontosfortes');
            $sql->adOnde('pg_ponto_forte_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('plano_gestao_premiacoes');
            $sql->adOnde('pg_premiacao_pg_id IN ('.$lista_plano_gestao.')');
            $sql->exec();
            $sql->limpar(); 
        }

        $sql->adTabela('praticas');
        $sql->adCampo('pratica_id');
        $sql->adOnde('pratica_cia='.(int)$cia_id);
        $lista = $sql->ListaChaveSimples();
        $sql->limpar();
        $lista_praticas=array();
        foreach($lista as $linha) $lista_praticas[]=$linha['pratica_id'];
        $lista_praticas=implode(',',$lista_praticas); 	

        $sql->adTabela('pratica_indicador');
        $sql->adCampo('pratica_indicador_id');
        $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
        $lista = $sql->ListaChaveSimples();
        $sql->limpar();
        $lista_indicadores=array();
        foreach($lista as $linha) $lista_indicadores[]=$linha['pratica_indicador_id'];
        $lista_indicadores=implode(',',$lista_indicadores); 		


        $sql->setExcluir('praticas');
        $sql->adOnde('pratica_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();	

        $sql->setExcluir('pratica_indicador');
        $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();


        if ($lista_indicadores){
            $sql->setExcluir('pratica_indicador_nos_marcadores');
            $sql->adOnde('pratica_indicador_id IN ('.$lista_indicadores.')');
            $sql->exec();
            $sql->limpar(); 	


            $sql->setExcluir('pratica_indicador_valor');
            $sql->adOnde('pratica_indicador_valor_indicador IN ('.$lista_indicadores.')');
            $sql->exec();
            $sql->limpar(); 

            $sql->setExcluir('pratica_indicador_depts');
            $sql->adOnde('pratica_indicador_id IN ('.$lista_indicadores.')');
            $sql->exec();
            $sql->limpar(); 


            $sql->setExcluir('pratica_indicador_usuarios');
            $sql->adOnde('pratica_indicador_id IN ('.$lista_indicadores.')');
            $sql->exec();
            $sql->limpar(); 
        }

        if ($lista_praticas){	
            $sql->setExcluir('pratica_nos_marcadores');
            $sql->adOnde('pratica IN ('.$lista_praticas.')');
            $sql->exec();
            $sql->limpar(); 
            $sql->setExcluir('pratica_depts');
            $sql->adOnde('pratica_id IN ('.$lista_praticas.')');
            $sql->exec();
            $sql->limpar(); 
            $sql->setExcluir('pratica_usuarios');
            $sql->adOnde('pratica_id IN ('.$lista_praticas.')');
            $sql->exec();
            $sql->limpar(); 
        }

        $sql->adTabela('projetos');
        $sql->adCampo('projeto_id');
        $sql->adOnde('projeto_cia='.(int)$cia_id);
        $lista = $sql->ListaChaveSimples();
        $sql->limpar();
        $lista_projetos=array();
        foreach($lista as $linha) $lista_projetos[]=$linha['projeto_id'];
        $lista_projetos=implode(',',$lista_projetos); 

        $sql->setExcluir('projetos');
        $sql->adOnde('projeto_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();	

       

        $sql->setExcluir('recursos');
        $sql->adOnde('recurso_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();	

        $lista_tarefas='';
        if($lista_projetos){
            $sql->adTabela('tarefas');
            $sql->adCampo('tarefa_id');
            $sql->adOnde('tarefa_projeto IN ('.$lista_projetos.')');
            $lista = $sql->ListaChaveSimples();
            $sql->limpar();
            $lista_tarefas=array();
            foreach($lista as $linha) $lista_tarefas[]=$linha['tarefa_id'];
            $lista_tarefas=implode(',',$lista_tarefas); 
        }

        if($lista_tarefas){
            $sql->setExcluir('tarefa_custos');
            $sql->adOnde('tarefa_custos_tarefa IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	

            $sql->setExcluir('tarefa_dependencias');
            $sql->adOnde('dependencias_tarefa_id IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	

            $sql->setExcluir('tarefa_gastos');
            $sql->adOnde('tarefa_gastos_tarefa IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	

            $sql->setExcluir('tarefa_h_custos');
            $sql->adOnde('h_custos_tarefa IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	

            $sql->setExcluir('tarefa_h_gastos');
            $sql->adOnde('h_gastos_tarefa IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	

            $sql->setExcluir('tarefa_log');
            $sql->adOnde('tarefa_log_tarefa IN ('.$lista_tarefas.')');
            $sql->exec();
            $sql->limpar();	
        }
        $sql->setExcluir('links');
        $sql->adOnde('link_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();	

        $sql->setExcluir('eventos');
        $sql->adOnde('evento_cia='.(int)$cia_id);
        $sql->exec();
        $sql->limpar();	

        echo '<script>env.importar_parte1.value=1; env.submit();</script>';	
    }
}


if (!$abortar && $importar_parte1){

	echo 'Importando o plano de gestão';

	$lista_tabelas=array();
	$handle = @fopen($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt', 'r');
  $buffer = fgets($handle);
  while ($buffer !=FALSE) {
		if (substr($buffer, 0, 6)=='TABELA'){
			$temp=explode('|*|', $buffer);
			$nome_tabela=$temp[1];
			$buffer = fgets($handle);
			$campos=explode('|*|', $buffer);
			array_pop($campos);		
			$tabela=array();
			while ($buffer = fgets($handle)) {
				if (substr($buffer, 0, 6)=='TABELA') break;
				$temp=explode('|*|', $buffer);
				array_pop($temp);
				$linha=array();
				foreach($temp as $chave => $valor) $linha[$campos[$chave]]=$valor;
				$tabela[]=$linha;
				}
			$lista_tabelas[$nome_tabela]=$tabela;
			}
    }
	
	$novo_pg_id=array();
	if (isset($lista_tabelas['plano_gestao'])){
		foreach($lista_tabelas['plano_gestao'] as $linha_tabela){
			$sql->adTabela('plano_gestao');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pg_id' && $chave!='pg_cia') $sql->adInserir($chave, $valor);
			$sql->adInserir('pg_cia', $cia_id);
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pg_id=$bd->Insert_ID('plano_gestao','pg_id');
			$novo_pg_id[$linha_tabela['pg_id']]=$pg_id;
			$sql->Limpar();	
			}
			
		foreach($lista_tabelas['plano_gestao2'] as $linha_tabela){
			$sql->adTabela('plano_gestao2');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pg_id') $sql->adInserir($chave, $valor);
			$sql->adInserir('pg_id', $pg_id);
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$sql->Limpar();	
			}	
			
		}
	
	$novo_obj_estrategico=array();
	if (isset($lista_tabelas['objetivos_estrategicos'])){
		foreach($lista_tabelas['objetivos_estrategicos'] as $linha_tabela){
			$sql->adTabela('objetivos_estrategicos');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pg_objetivo_estrategico_id') $sql->adInserir($chave, $valor);
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pg_objetivo_estrategico_id=$bd->Insert_ID('objetivos_estrategicos','pg_objetivo_estrategico_id');
			$novo_obj_estrategico[$linha_tabela['pg_objetivo_estrategico_id']]=$pg_objetivo_estrategico_id;
			$sql->Limpar();	
			}
		}
		
	$nova_meta=array();
	if (isset($lista_tabelas['metas'])){
		foreach($lista_tabelas['metas'] as $linha_tabela){
			$sql->adTabela('metas');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pg_meta_id') $sql->adInserir($chave, $valor);
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pg_meta_id=$bd->Insert_ID('metas','pg_meta_id');
			$nova_meta[$linha_tabela['pg_meta_id']]=$pg_meta_id;
			$sql->Limpar();	
			}	
		}
		
	$nova_estrategia=array();
	if (isset($lista_tabelas['estrategias'])){
		foreach($lista_tabelas['estrategias'] as $linha_tabela){
			$sql->adTabela('estrategias');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pg_estrategia_id'  && $chave!='pg_estrategia_usuario') $sql->adInserir($chave, $valor);
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pg_estrategia_id=$bd->Insert_ID('estrategias','pg_estrategia_id');
			$nova_estrategia[$linha_tabela['pg_estrategia_id']]=$pg_estrategia_id;
			$sql->Limpar();	
			}	
		}

	importar_tabela('fatores_criticos', 'pg_fator_critico_id', 'pg_fator_critico_pg_id','pg_fator_critico_usuario');	
	importar_tabela('plano_gestao_ameacas','pg_ameaca_id', 'pg_ameaca_pg_id');	
	importar_tabela('plano_gestao_diretrizes', 'pg_diretriz_id', 'pg_diretriz_pg_id');	
	importar_tabela('plano_gestao_diretrizes_superiores', 'pg_diretriz_superior_id', 'pg_diretriz_superior_pg_id','pg_diretriz_superior_usuario');	
	importar_tabela('plano_gestao_fornecedores', 'pg_fornecedor_id', 'pg_fornecedor_pg_id','pg_fornecedor_usuario');	
	importar_tabela('plano_gestao_oportunidade', 'pg_oportunidade_id', 'pg_oportunidade_pg_id', 'pg_oportunidade_usuario');	
	importar_tabela('plano_gestao_oportunidade_melhorias', 'pg_oportunidade_melhoria_id', 'pg_oportunidade_melhoria_pg_id','pg_oportunidade_melhoria_usuario');	
	importar_tabela('plano_gestao_pessoal', 'pg_pessoal_id', 'pg_pessoal_pg_id','pg_pessoal_usuario');	
	importar_tabela('plano_gestao_pontosfortes', 'pg_ponto_forte_id', 'pg_ponto_forte_pg_id','pg_ponto_forte_usuario');	
	importar_tabela('plano_gestao_premiacoes', 'pg_premiacao_id', 'pg_premiacao_pg_id','pg_premiacao_usuario');	
	
	
	$saida_obj_estrategico='';
	foreach($novo_obj_estrategico as $chave => $valor) $saida_obj_estrategico.=($saida_obj_estrategico ? ',' : '').$chave.':'.$valor;	
	
	$saida_nova_estrategia='';
	foreach($nova_estrategia as $chave => $valor) $saida_nova_estrategia.=($saida_nova_estrategia ? ',' : '').$chave.':'.$valor;	
	
	$saida_nova_meta='';
	foreach($nova_meta as $chave => $valor) $saida_nova_meta.=($saida_nova_meta ? ',' : '').$chave.':'.$valor;	

	echo '<script>env.saida_obj_estrategico.value="'.$saida_obj_estrategico.'"; env.saida_nova_estrategia.value="'.$saida_nova_estrategia.'"; env.saida_nova_meta.value="'.$saida_nova_meta.'"; env.importar_parte2.value=1; env.submit();</script>';
	}
		

if (!$abortar && $importar_parte2){	
	
	echo 'Importando as práticas de gestão e indicadores';
	$novo_obj_estrategico=array();
	$saida_obj_estrategico =explode(',',$saida_obj_estrategico);
	foreach($saida_obj_estrategico as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $novo_obj_estrategico[$valor[0]]=$valor[1];
		}
	$nova_estrategia=array();
	$saida_nova_estrategia =explode(',',$saida_nova_estrategia);
	foreach($saida_nova_estrategia as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $nova_estrategia[$valor[0]]=$valor[1];
		}
	$nova_meta=array();
	$saida_nova_meta =explode(',',$saida_nova_meta);
	foreach($saida_nova_meta as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $nova_meta[$valor[0]]=$valor[1];
		}
	
	$lista_tabelas=array();
	$handle = @fopen($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt', 'r');
  $buffer = fgets($handle);
  while ($buffer !=FALSE) {
		if (substr($buffer, 0, 6)=='TABELA'){
			$temp=explode('|*|', $buffer);
			$nome_tabela=$temp[1];
			$buffer = fgets($handle);
			$campos=explode('|*|', $buffer);
			array_pop($campos);		
			$tabela=array();
			while ($buffer = fgets($handle)) {
				if (substr($buffer, 0, 6)=='TABELA') break;
				$temp=explode('|*|', $buffer);
				array_pop($temp);
				$linha=array();
				foreach($temp as $chave => $valor) $linha[$campos[$chave]]=$valor;
				$tabela[]=$linha;
				}
			$lista_tabelas[$nome_tabela]=$tabela;
			}
    }
	
	$nova_pratica=array();
	if (isset($lista_tabelas['praticas'])){
		foreach($lista_tabelas['praticas'] as $linha_tabela){
			$sql->adTabela('praticas');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pratica_id'  && $chave!='pratica_cia' && $chave!='pratica_superior') $sql->adInserir($chave, $valor);
			$sql->adInserir('pratica_cia', $cia_id); 
			if (isset($nova_pratica[$linha_tabela['pratica_superior']])) $sql->adInserir('pratica_superior', $nova_pratica[$linha_tabela['pratica_superior']]); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pratica_id=$bd->Insert_ID('praticas','pratica_id');
			$nova_pratica[$linha_tabela['pratica_id']]=$pratica_id;
			$sql->Limpar();	
			}
		}
	
	
	$novo_indicador=array();
	if (isset($lista_tabelas['pratica_indicador'])){
		foreach($lista_tabelas['pratica_indicador'] as $linha_tabela){
			$sql->adTabela('pratica_indicador');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pratica_indicador_id'  && $chave!='pratica_indicador_cia' && $chave!='pratica_indicador_objetivo_estrategico' && $chave!='pratica_indicador_estrategia' && $chave!='pratica_indicador_meta' && $chave!='pratica_indicador_responsavel' && $chave!='pratica_indicador_composicao') $sql->adInserir($chave, $valor);
			$sql->adInserir('pratica_indicador_cia', $cia_id); 
			if (isset($novo_obj_estrategico[$linha_tabela['pratica_indicador_objetivo_estrategico']])) $sql->adInserir('pratica_indicador_objetivo_estrategico', $novo_obj_estrategico[$linha_tabela['pratica_indicador_objetivo_estrategico']]); 
			if (isset($nova_estrategia[$linha_tabela['pratica_indicador_estrategia']])) $sql->adInserir('pratica_indicador_estrategia', $nova_estrategia[$linha_tabela['pratica_indicador_estrategia']]); 
			if (isset($nova_meta[$linha_tabela['pratica_indicador_meta']])) $sql->adInserir('pratica_indicador_meta', $nova_meta[$linha_tabela['pratica_indicador_meta']]); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$pratica_indicador_id=$bd->Insert_ID('pratica_indicador','pratica_indicador_id');
			$novo_indicador[$linha_tabela['pratica_indicador_id']]=$pratica_indicador_id;
			$sql->Limpar();	
			}
		}
	
	
	
	if (isset($lista_tabelas['pratica_indicador_composicao'])){
		foreach($lista_tabelas['pratica_indicador_composicao'] as $linha_tabela){
			$sql->adTabela('pratica_indicador_composicao');
			foreach($linha_tabela as $chave => $valor) if ($chave!='pratica_indicador_composicao_pai'  && $chave!='pratica_indicador_composicao_filho') $sql->adInserir($chave, $valor);
			if (isset($novo_indicador[$linha_tabela['pratica_indicador_composicao_pai']])) $sql->adInserir('pratica_indicador_composicao_pai', $novo_indicador[$linha_tabela['pratica_indicador_composicao_pai']]); 
			if (isset($novo_indicador[$linha_tabela['pratica_indicador_composicao_filho']])) $sql->adInserir('pratica_indicador_composicao_filho', $novo_indicador[$linha_tabela['pratica_indicador_composicao_filho']]); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$sql->Limpar();	
			}
		}
	

	importar_tabela('pratica_indicador_valor', 'pratica_indicador_valor_id', '', 'pratica_indicador_valor_responsavel', '', '', 'pratica_indicador_valor_indicador');
	importar_tabela('pratica_nos_marcadores', '', '', '', '', '', '', 'pratica');


	$saida_novo_indicador='';
	foreach($novo_indicador as $chave => $valor) $saida_novo_indicador.=($saida_novo_indicador ? ',' : '').$chave.':'.$valor;	

	echo '<script>env.saida_novo_indicador.value="'.$saida_novo_indicador.'"; env.importar_parte3.value=1; env.submit();</script>';
	

	}	

if (!$abortar && $importar_parte3){
	echo 'Importando projetos';
	$novo_obj_estrategico=array();
	$saida_obj_estrategico =explode(',',$saida_obj_estrategico);
	foreach($saida_obj_estrategico as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $novo_obj_estrategico[$valor[0]]=$valor[1];
		}
	$nova_estrategia=array();
	$saida_nova_estrategia =explode(',',$saida_nova_estrategia);
	foreach($saida_nova_estrategia as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $nova_estrategia[$valor[0]]=$valor[1];
		}
	$nova_meta=array();
	$saida_nova_meta =explode(',',$saida_nova_meta);
	foreach($saida_nova_meta as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $nova_meta[$valor[0]]=$valor[1];
		}
	
	$novo_indicador=array();
	$saida_novo_indicador =explode(',',$saida_novo_indicador);
	foreach($saida_novo_indicador as $chave => $valor){
		$valor=explode(':', $valor);
		if (isset($valor[1])) $novo_indicador[$valor[0]]=$valor[1];
		}
	
	$lista_tabelas=array();
	$handle = @fopen($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt', 'r');
  $buffer = fgets($handle);
  while ($buffer !=FALSE) {
		if (substr($buffer, 0, 6)=='TABELA'){
			$temp=explode('|*|', $buffer);
			$nome_tabela=$temp[1];
			$buffer = fgets($handle);
			$campos=explode('|*|', $buffer);
			array_pop($campos);		
			$tabela=array();
			while ($buffer = fgets($handle)) {
				if (substr($buffer, 0, 6)=='TABELA') break;
				$temp=explode('|*|', $buffer);
				array_pop($temp);
				$linha=array();
				foreach($temp as $chave => $valor) $linha[$campos[$chave]]=$valor;
				$tabela[]=$linha;
				}
			$lista_tabelas[$nome_tabela]=$tabela;
			}
    }
	
	
	$novo_projeto=array();
	if (isset($lista_tabelas['projetos'])){
		foreach($lista_tabelas['projetos'] as $linha_tabela){
			$sql->adTabela('projetos');
			foreach($linha_tabela as $chave => $valor) if ($chave!='projeto_id'  && $chave!='projeto_cia' && $chave!='projeto_objetivo_estrategico' && $chave!='projeto_estrategia' && $chave!='projeto_meta' && $chave!='projeto_indicador' && $chave!='projeto_responsavel' && $chave!='projeto_supervisor' && $chave!='projeto_autoridade' && $chave!='projeto_superior_original' && $chave!='projeto_superior') $sql->adInserir($chave, $valor);
			$sql->adInserir('projeto_cia', $cia_id); 
 			if (isset($novo_obj_estrategico[$linha_tabela['projeto_objetivo_estrategico']])) $sql->adInserir('projeto_objetivo_estrategico', $novo_obj_estrategico[$linha_tabela['projeto_objetivo_estrategico']]); 
			if (isset($nova_estrategia[$linha_tabela['projeto_estrategia']])) $sql->adInserir('projeto_estrategia', $nova_estrategia[$linha_tabela['projeto_estrategia']]); 
			if (isset($nova_meta[$linha_tabela['projeto_meta']])) $sql->adInserir('projeto_meta', $nova_meta[$linha_tabela['projeto_meta']]); 
			if (isset($novo_indicador[$linha_tabela['projeto_indicador']])) $sql->adInserir('projeto_indicador', $novo_indicador[$linha_tabela['projeto_indicador']]); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$projeto_id=$bd->Insert_ID('projetos','projeto_id');
			$novo_projeto[$linha_tabela['projeto_id']]=$projeto_id;
			$sql->Limpar();
			if (isset($novo_projeto[$linha_tabela['projeto_superior']]) || isset($novo_projeto[$linha_tabela['projeto_superior_original']])){
				$sql->adTabela('projetos');
				if (isset($novo_projeto[$linha_tabela['projeto_superior']])) $sql->adAtualizar('projeto_superior', $novo_projeto[$linha_tabela['projeto_superior']]); 
				if (isset($novo_projeto[$linha_tabela['projeto_superior_original']])) $sql->adAtualizar('projeto_superior_original', $novo_projeto[$linha_tabela['projeto_superior_original']]); 
				$sql->adOnde('projeto_id='.$projeto_id);
				$sql->sem_chave_estrangeira();
				$sql->exec();
				$sql->Limpar();
				}	
			}
		}
	
	$nova_atividade=array();
	
	
	$nova_tarefa=array();
	if (isset($lista_tabelas['tarefas'])){
		foreach($lista_tabelas['tarefas'] as $linha_tabela){
			$sql->adTabela('tarefas');
			foreach($linha_tabela as $chave => $valor) if ($chave!='tarefa_id'  && $chave!='tarefa_cia' && $chave!='tarefa_superior' && $chave!='tarefa_projeto' && $chave!='tarefa_dono' && $chave!='tarefa_criador') $sql->adInserir($chave, $valor);
			$sql->adInserir('tarefa_cia', $cia_id); 
			if (isset($novo_projeto[$linha_tabela['tarefa_projeto']])) $sql->adInserir('tarefa_projeto', $novo_projeto[$linha_tabela['tarefa_projeto']]); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$tarefa_id=$bd->Insert_ID('tarefas','tarefa_id');
			$nova_tarefa[$linha_tabela['tarefa_id']]=$tarefa_id;
			$sql->Limpar();	
			if (isset($nova_tarefa[$linha_tabela['tarefa_superior']])){
				$sql->adTabela('tarefas');
				$sql->adAtualizar('tarefa_superior', $nova_tarefa[$linha_tabela['tarefa_superior']]); 
				$sql->adOnde('tarefa_id='.$tarefa_id);
				$sql->sem_chave_estrangeira();
				$sql->exec();
				$sql->Limpar();
				}	
			}
		}
	
	
	
	$novo_recurso=array();
	if (isset($lista_tabelas['recursos'])){
		foreach($lista_tabelas['recursos'] as $linha_tabela){
			$sql->adTabela('recursos');
			foreach($linha_tabela as $chave => $valor) if ($chave!='recurso_id'  && $chave!='recurso_cia') $sql->adInserir($chave, $valor);
			$sql->adInserir('recurso_cia', $cia_id); 
			$sql->sem_chave_estrangeira();
			$sql->exec();
			$recurso_id=$bd->Insert_ID('recursos','recurso_id');
			$novo_recurso[$linha_tabela['recurso_id']]=$recurso_id;
			$sql->Limpar();	
			}
		}
	
	importar_tabela('recurso_tarefas', '', '', '', '', '', '', '', '', 'tarefa_id', 'recurso_id');
	importar_tabela('tarefa_custos', 'tarefa_custos_id', '', 'tarefa_custos_usuario', '', '', '', '', '', 'tarefa_custos_tarefa');
	importar_tabela('tarefa_dependencias', '', '', '', '', '', '', '', '', 'dependencias_tarefa_id','','dependencias_req_tarefa_id');
	importar_tabela('tarefa_gastos', 'tarefa_gastos_id', '', 'tarefa_gastos_usuario', '', '', '', '', '', 'tarefa_gastos_tarefa');
	importar_tabela('tarefa_h_custos', 'h_custos_id', '', 'h_custos_usuario1', 'h_custos_usuario2', '', '', '', '', 'h_custos_tarefa');
	importar_tabela('tarefa_h_gastos', 'h_gastos_id', '', 'h_gastos_usuario1', 'h_gastos_usuario2', '', '', '', '', 'h_gastos_tarefa');
	importar_tabela('tarefa_log', 'tarefa_log_id', '', 'tarefa_log_criador', '', '', '', '', '', 'tarefa_log_tarefa');
	importar_tabela('links', 'link_id', '', 'link_dono', '', '', 'link_indicador', 'link_pratica', 'link_projeto', 'link_tarefa','','','link_cia');
	importar_tabela('eventos', 'evento_id', '', 'evento_dono', '', '', 'evento_indicador', 'evento_pratica', 'evento_projeto', 'evento_tarefa','','','evento_cia');
	
	echo '<script>env.importar_parte4.value=1; env.submit();</script>';
	}

function importar_tabela($tabela, $chave_tabela, $chave_estrangeira, $ignorar='', $ignorar_2='', $chave_obj_estrategico='', $chave_indicador='', $chave_pratica='', $chave_projeto='', $chave_tarefa='', $chave_recurso='', $chave_tarefa2='', $chave_cia='', $chave_atividade=''){
	global $sql, $lista_tabelas, $novo_pg_id, $novo_obj_estrategico, $novo_indicador, $nova_pratica, $novo_projeto, $novo_recurso, $nova_tarefa, $cia_id, $nova_atividade;
	
	if (isset($lista_tabelas[$tabela])){
		foreach($lista_tabelas[$tabela] as $linha_tabela){
			$sql->adTabela($tabela);
			foreach($linha_tabela as $chave => $valor) {
				if($chave==$chave_estrangeira && isset($novo_pg_id[$valor])) $sql->adInserir($chave, $novo_pg_id[$valor]);
				elseif($chave==$chave_obj_estrategico && isset($novo_obj_estrategico[$valor])) $sql->adInserir($chave, $novo_obj_estrategico[$valor]);
				elseif($chave==$chave_indicador && isset($novo_indicador[$valor])) $sql->adInserir($chave, $novo_indicador[$valor]);
				elseif($chave==$chave_pratica && isset($nova_pratica[$valor])) $sql->adInserir($chave, $nova_pratica[$valor]);
				elseif($chave==$chave_projeto && isset($novo_projeto[$valor])) $sql->adInserir($chave, $novo_projeto[$valor]);
				elseif($chave==$chave_tarefa && isset($nova_tarefa[$valor])) $sql->adInserir($chave, $nova_tarefa[$valor]);
				elseif($chave==$chave_tarefa2 && isset($nova_tarefa[$valor])) $sql->adInserir($chave, $nova_tarefa[$valor]);
				elseif($chave==$chave_recurso && isset($novo_recurso[$valor])) $sql->adInserir($chave, $novo_recurso[$valor]);
				
				elseif($chave==$chave_atividade && isset($nova_atividade[$valor])) $sql->adInserir($chave, $nova_atividade[$valor]);
				
				elseif($chave==$chave_cia) $sql->adInserir($chave, $cia_id);
				elseif ($chave!=$chave_estrangeira && $chave!=$chave_tabela && $chave!=$ignorar && $chave!=$ignorar_2 && $chave!=$chave_obj_estrategico &&  $chave!=$chave_indicador &&  $chave!=$chave_pratica &&  $chave!=$chave_projeto &&  $chave!=$chave_tarefa &&  $chave!=$chave_recurso &&  $chave!=$chave_tarefa2 &&  $chave!=$chave_cia &&  $chave!=$chave_atividade) $sql->adInserir($chave, $valor);
				
				
				}
			$sql->sem_chave_estrangeira();	
			$sql->exec();
			$sql->Limpar();	
			}
		}	
	}


$botoesTitulo = new CBlocoTitulo('Importar e Exportar entre Servidores', 'importar.gif', $m, "$m.$a");
$procurar_om='<tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' da qual irá exportar ou importar os dados.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Selecione '.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' da qual irá exportar ou importar os dados.').'</a></td></tr>';
$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.'</table>');
$botoesTitulo->adicionaBotao('m=sistema&a=index', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();


if (!$abortar && $importar_parte4){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><h1>'.nome_cia($cia_id).'</h1></td></tr>';
	echo'<tr><td colspan=20 align=center><h2>Importação concluída</h2></td></tr>';
	echo estiloFundoCaixa();
	@unlink($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt');
	@unlink($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt.gz');
	}

if($importar && $abortar){
    @unlink($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt');
    @unlink($base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt.gz');
    }

if ($exportar){
	$teste=new CTabela_txt;
	$teste->setCia($cia_id);
	$data=new CData();
	$nome_cia=nome_cia($cia_id);
	$nome_arquivo=preg_replace("/[^\x9\xA\xD\x20-\x7F]/", "", $nome_cia);
	$nome_arquivo=str_replace(' ', '', $nome_arquivo);
	$nome_arquivo=$nome_arquivo.'_'.$data->format('%d-%m-%Y');
	
	$teste->criar_xml($nome_arquivo);
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><h1>'.$nome_cia.'</h1></td></tr>';
	echo'<tr><td colspan=20 align=center><table><tr><td align=right>Arquivos criados:</td><td><b><a href="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome_arquivo.'.txt.gz">'.$nome_arquivo.'.txt.gz</a></b><br><b><a href="'.$base_dir.'/arquivos/temp/'.$nome_arquivo.'.txt">'.$nome_arquivo.'.txt</a></b></td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	}

if($abortar || (!$exportar && !$importar && !$importar_parte4)){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><h1>'.nome_cia($cia_id).'</h1></td></tr>';
	if ($podeEditar) echo '<tr><td align=right><b>Importar dados:</b></td><td colspan=20><table><tr><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('importar', 'Importar', 'Clique neste botão para enviar arquivo selecionado à esquerda para o servidor e importar os dados contidos no mesmo para '.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.','','env.importar.value=1; env.submit()').'</td></tr></table></td></tr>';
	echo'<tr><td align=right><b>Exportar dados:</b></td><td colspan=20><table><tr><td>'.botao('exportar', 'Exportar', 'Clique neste botão para criar o arquivo d'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada a ser importado noutro servidor.','','env.exportar.value=1; env.submit()').'</td></tr></table></td></tr>';
    echo'<tr><td align=right style="padding-top:20px; padding-bottom:20px;"><b>Atenção:</b></td><td colspan=20 style="padding-left:10px; padding-top:20px;padding-bottom:20px;">Ao fazer uma importação os dados existentes no banco de dados serão apagados antes da importação iniciar.<br>É aconselhavel fazer um backup de segurança antes de iniciar o processo.</td></tr>';
	echo estiloFundoCaixa();
	}

echo '</form>';


?>
<script language="javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
</script>