<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class Indicador {
	var $pratica_indicador_id = null;
	var $pratica_indicador_agrupar = null;
	var $pratica_indicador_acumulacao = null;
	var $pratica_indicador_sentido = null;
	var $pratica_indicador_composicao = null;
	var $pratica_indicador_formula = null;
	var $pratica_indicador_formula_simples = null;
	var $pratica_indicador_calculo = null;
	var $pratica_indicador_valor_meta= null;
	var $pratica_indicador_checklist= null;
	var $pratica_indicador_campo_projeto = null;
	var $pratica_indicador_parametro_projeto = null;
	var $pratica_indicador_projeto = null;
	var $pratica_indicador_campo_tarefa = null;
	var $pratica_indicador_parametro_tarefa = null;
	var $pratica_indicador_tarefa = null;
	var $pratica_indicador_campo_acao = null;
	var $pratica_indicador_parametro_acao = null;
	var $pratica_indicador_acao = null;
	var $pratica_indicador_checklist_valor = null;
	var $pratica_indicador_valor_referencial = null;
	var $pratica_indicador_data_meta = null;
	var $pratica_indicador_meta_proporcao = null;
	var $pratica_indicador_valor_meta_boa = null;
	var $pratica_indicador_valor_meta_regular = null;
	var $pratica_indicador_valor_meta_ruim = null;
	var $pratica_indicador_periodo_anterior = null;
	var $pratica_indicador_tolerancia = null;
	
	var $ja_usado=array();
	
	
	function __construct($pratica_indicador_id, $ano=null, $inicio=null, $fim=null, $ja_usado=array(), $pratica_indicador_acumulacao=null, $pratica_indicador_agrupar=null, $pratica_indicador_periodo_anterior=null){
		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_acumulacao, pratica_indicador_agrupar, pratica_indicador_sentido, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_externo, pratica_indicador_calculo, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_parametro_projeto, pratica_indicador_projeto, pratica_indicador_campo_tarefa, pratica_indicador_parametro_tarefa, pratica_indicador_campo_acao, pratica_indicador_parametro_acao, pratica_indicador_acao, pratica_indicador_tarefa, pratica_indicador_checklist_valor, pratica_indicador_periodo_anterior, pratica_indicador_tolerancia');
		$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
		$pratica_indicador=$sql->Linha();
		$sql->limpar();
		$this->pratica_indicador_id=$pratica_indicador_id;
		
		$this->pratica_indicador_agrupar=($pratica_indicador_agrupar ? $pratica_indicador_agrupar : $pratica_indicador['pratica_indicador_agrupar']);
		$this->pratica_indicador_acumulacao=($pratica_indicador_acumulacao ? $pratica_indicador_acumulacao : $pratica_indicador['pratica_indicador_acumulacao']);
		$this->pratica_indicador_periodo_anterior=($pratica_indicador_periodo_anterior ? $pratica_indicador_periodo_anterior : $pratica_indicador['pratica_indicador_periodo_anterior']);
		
		$this->pratica_indicador_sentido=$pratica_indicador['pratica_indicador_sentido'];
		$this->pratica_indicador_composicao=$pratica_indicador['pratica_indicador_composicao'];
		$this->pratica_indicador_calculo=$pratica_indicador['pratica_indicador_calculo'];
		$this->pratica_indicador_formula=$pratica_indicador['pratica_indicador_formula'];
		$this->pratica_indicador_formula_simples=$pratica_indicador['pratica_indicador_formula_simples'];
		$this->pratica_indicador_externo=$pratica_indicador['pratica_indicador_externo'];
		$this->pratica_indicador_checklist=$pratica_indicador['pratica_indicador_checklist'];
		$this->pratica_indicador_campo_projeto=$pratica_indicador['pratica_indicador_campo_projeto'];
		$this->pratica_indicador_campo_tarefa=$pratica_indicador['pratica_indicador_campo_tarefa'];
		$this->pratica_indicador_campo_acao=$pratica_indicador['pratica_indicador_campo_acao'];
		$this->pratica_indicador_checklist_valor=$pratica_indicador['pratica_indicador_checklist_valor'];
		$this->pratica_indicador_parametro_projeto=$pratica_indicador['pratica_indicador_parametro_projeto'];
		$this->pratica_indicador_parametro_tarefa=$pratica_indicador['pratica_indicador_parametro_tarefa'];
		$this->pratica_indicador_parametro_acao=$pratica_indicador['pratica_indicador_parametro_acao'];
		$this->pratica_indicador_projeto=$pratica_indicador['pratica_indicador_projeto'];
		$this->pratica_indicador_tarefa=$pratica_indicador['pratica_indicador_tarefa'];
		$this->pratica_indicador_acao=$pratica_indicador['pratica_indicador_acao'];
		
		$this->pratica_indicador_tolerancia=$pratica_indicador['pratica_indicador_tolerancia'];
		$this->inicio=$inicio;
		$this->fim=$fim;
		$this->ano=(int)($ano ? $ano : ($fim ? substr($fim, 0, 4) : date('Y')));
		$this->ja_usado=$ja_usado;


		$sql->adTabela('pratica_indicador_meta');
		$sql->adCampo('pratica_indicador_meta_proporcao, pratica_indicador_meta_valor_meta, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_data_meta, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim');
		if ($this->fim) $sql->adOnde('pratica_indicador_meta_data <=\''.($this->fim <= date('Y-m-d') ? $this->fim : date('Y-m-d')).'\' AND pratica_indicador_meta_data_meta >=\''.($this->fim <= date('Y-m-d') ? $this->fim : date('Y-m-d')).'\'');
		elseif ($this->inicio) $sql->adOnde('pratica_indicador_meta_data <=\''.$this->inicio.'\' AND pratica_indicador_meta_data_meta >=\''.$this->inicio.'\'');
		elseif ($this->ano && (int)$this->ano==(int)date('Y')) $sql->adOnde('pratica_indicador_meta_data <=\''.date('Y-m-d').'\' AND pratica_indicador_meta_data_meta >=\''.date('Y-m-d').'\'');
		elseif ($this->ano) $sql->adOnde('pratica_indicador_meta_data <=\''.$this->ano.'-12-31'.'\' AND pratica_indicador_meta_data_meta >=\''.$this->ano.'-12-31'.'\'');
		else $sql->adOnde('pratica_indicador_meta_data <=\''.date('Y-m-d').'\' AND pratica_indicador_meta_data_meta>= \''.date('Y-m-d').'\'');
		$sql->adOnde('pratica_indicador_meta_indicador='.(int)$pratica_indicador_id);
		$sql->adOrdem('pratica_indicador_meta_data_meta ASC');
		$meta=$sql->linha();
		$sql->limpar();
		
		$this->pratica_indicador_valor_meta=$meta['pratica_indicador_meta_valor_meta'];
		$this->pratica_indicador_valor_referencial=$meta['pratica_indicador_meta_valor_referencial'];
		$this->pratica_indicador_data_meta=$meta['pratica_indicador_meta_data_meta'];
		$this->pratica_indicador_meta_proporcao=$meta['pratica_indicador_meta_proporcao'];
		$this->pratica_indicador_valor_meta_boa=$meta['pratica_indicador_meta_valor_meta_boa'];
		$this->pratica_indicador_valor_meta_regular=$meta['pratica_indicador_meta_valor_meta_regular'];
		$this->pratica_indicador_valor_meta_ruim=$meta['pratica_indicador_meta_valor_meta_ruim'];
		
		
		}
	
	
	function Tendencia($ano=null, $inicio=null, $fim=null){
		
		$tendencia='';
		$vetor=$this->Pontos(3, $this->pratica_indicador_agrupar, $ano, $inicio, $fim, false, null, $this->pratica_indicador_periodo_anterior);

		if (count($vetor)< ($this->pratica_indicador_periodo_anterior ? 4 : 3)) return 'sem tendência';
		$valores=array();
		foreach($vetor as $valor) $valores[]=$valor['valor'];
		if ($this->pratica_indicador_periodo_anterior && is_array($valores)) array_shift($valores);
		
		
		if(($valores[0] > $valores[1]) && ($valores[1] > $valores[2])) $tendencia='positiva';
		elseif(($valores[0] == $valores[1]) && $valores[1] == 100) $tendencia='positiva';
		elseif(($valores[0] < $valores[1]) && ($valores[1] < $valores[2])) $tendencia='negativa';
		else $tendencia='sem tendência';	

		return $tendencia;
		}
	
	
	function Pontuacao($ano=null, $inicio=null, $fim=null, $formatar=false){
		global $config;
		
		if ($this->pratica_indicador_meta_proporcao){
			$pontos=$this->Pontos(1,$this->pratica_indicador_agrupar, $ano, $inicio, $fim, true, null, $this->pratica_indicador_periodo_anterior);
			if ((!$this->pratica_indicador_periodo_anterior || $this->pratica_indicador_campo_projeto) && is_array($pontos)) $pontos = array_shift($pontos);
			elseif(is_array($pontos)) {
				array_shift($pontos);
				$pontos=array_shift($pontos);
				}
			if (!isset($pontos['valor']))$pontos['valor']=0;
			$meta=$this->meta_periodo_anterior($this->pratica_indicador_id, (float)$this->pratica_indicador_valor_meta, $this->pratica_indicador_data_meta);
			
			
			if ($this->pratica_indicador_sentido==2){
				if ($meta!=0 && $pontos['valor']!=0) {
					if ($pontos['valor']>=$meta){
						$calculo_valor=($meta/$pontos['valor'])*100;
						}
					else{
						$calculo_valor=($pontos['valor']/$meta)*100;
						if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];;
						}
					}
				else $calculo_valor=($pontos['valor']==$meta ? 100 : 0);
				}
			else if (($meta!=0 && $this->pratica_indicador_sentido==1) || ($pontos['valor']!=0 && !$this->pratica_indicador_sentido)) $calculo_valor=(($this->pratica_indicador_sentido==1 ? (($pontos['valor']/$meta) > ($config['porcentagem_maxima']/100) ? ($config['porcentagem_maxima']/100) : ($pontos['valor']/$meta)): (($meta/$pontos['valor'])> ($config['porcentagem_maxima']/100) ? ($config['porcentagem_maxima']/100) : ($meta/$pontos['valor'])))*100);
			else $calculo_valor=($pontos['valor']==0 ? 100 : 0);
			if ($this->pratica_indicador_tolerancia > 0) $calculo_valor+=$this->pratica_indicador_tolerancia;
			if ($calculo_valor < 0) $calculo_valor=0;
			return ($formatar ? number_format($calculo_valor, $config['casas_decimais'], ',', '.') : $calculo_valor);
			}
		else {
			$pontos=$this->Pontos(1,$this->pratica_indicador_agrupar,$ano, $inicio, $fim, false, null, $this->pratica_indicador_periodo_anterior);

			if (count($pontos)){
				if ((!$this->pratica_indicador_periodo_anterior || $this->pratica_indicador_campo_projeto) && is_array($pontos)) $pontos = array_shift($pontos);
				elseif(is_array($pontos)) {
					array_shift($pontos);
					$pontos=array_shift($pontos);
					}
				}	
			if (!isset($pontos['valor']))$pontos['valor']=null;
			if ($this->pratica_indicador_tolerancia > 0) $pontos['valor']+=$this->pratica_indicador_tolerancia;
			return ($formatar ? number_format($pontos['valor'], $config['casas_decimais'], ',', '.') : $pontos['valor']);
			}
		}
	
	
	function meta_periodo_anterior($pratica_indicador_meta_indicador, $multiplo, $data){
		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador_meta');
		$sql->adCampo('pratica_indicador_meta_valor_meta, pratica_indicador_meta_data_meta, pratica_indicador_meta_proporcao');
		$sql->adOnde('pratica_indicador_meta_indicador='.(int)$pratica_indicador_meta_indicador);
		$sql->adOnde('pratica_indicador_meta_data_meta <\''.$data.'\'');
		$sql->adOrdem('pratica_indicador_meta_data_meta DESC');
		$meta_achada=$sql->linha();
		$sql->limpar();
		if ($meta_achada && !(int)$meta_achada['pratica_indicador_meta_proporcao']) $saida=(float)$meta_achada['pratica_indicador_meta_valor_meta']*(float)$multiplo;
		else if ($meta_achada) $saida=$this->meta_periodo_anterior((int)$pratica_indicador_meta_indicador, (float)$multiplo*(float)$meta_achada['pratica_indicador_meta_valor_meta'], $meta_achada['pratica_indicador_meta_data_meta']);
		else $saida=0;
		return $saida;	
		}
	
	function Valor_atual($agrupar='', $ano=null, $inicio=null, $fim=null){
		if (!$agrupar) $agrupar=$this->pratica_indicador_agrupar;
		$valor=$this->Pontos(1, $agrupar, $ano, $inicio, $fim, true, null, $this->pratica_indicador_periodo_anterior);
		if ($valor) {
			if (!$this->pratica_indicador_periodo_anterior && is_array($valor)) $valor = array_shift($valor);
			elseif(is_array($valor)) {
				$descarta_atual = array_shift($valor);
				$valor = array_shift($valor);
				}
			}
		if ((isset($valor['valor']) && $valor['valor']===null) || !isset($valor['valor'])) return null;
		else return (float)$valor['valor'];
		}
	
	
	function calcular_string($texto){
    $texto = trim($texto); 
    $texto=previnirXSS($texto);
    if (!$texto) return 0;
		
    for($i = 0; $i < strlen($texto); $i++){
    	if (isset($texto[$i]) && $texto[$i]=='I' && isset($texto[$i+1]) && is_int($texto[$i+1]) && isset($texto[$i+2]) &&is_int($texto[$i+2])) { 		  		
    		$texto[$i]='0';
    		$texto[$i+1]='.';
    		$texto[$i+2]='0';
    		}
    	}	
    $valor=0;

    $e='';
    try{
			eval('@$valor = '.$texto.';');
			}
		catch(Exception $e){
			return 0;
			}
		if($valor === false){
			return 0;
			}
		else return 0 + $valor;
    
    
		}

	function Pontos($qnt_pontos=0, $agrupar='ano', $ano=null, $inicio=null, $fim=null, $valor_bruto=false, $data_final=null, $periodo_anterior=null){
		global $Aplic, $config;
		
		if ($agrupar=='nenhum'){
			$ano=null;
			$inicio=null;
			$fim=null;
			}
		
		if ($periodo_anterior) $qnt_pontos++;
		
		$sql = new BDConsulta;
		
		if(!$this->pratica_indicador_composicao && !$this->pratica_indicador_formula && !$this->pratica_indicador_campo_projeto && !$this->pratica_indicador_campo_tarefa && !$this->pratica_indicador_campo_acao){
			$nome_tabela=($this->pratica_indicador_checklist ? 'checklist_dados' : 'pratica_indicador_valor');
			//valores antigos, por isso o indicador_valor estaria em branco
				if ($agrupar=='dia' || $agrupar=='semana'){
					if ($this->pratica_indicador_acumulacao=='soma') {
						$sql->adTabela($nome_tabela);
						$sql->adCampo('SUM(pratica_indicador_valor_valor) AS valor, MAX(`pratica_indicador_valor_valor`) AS max, MIN(`pratica_indicador_valor_valor`) AS min, pratica_indicador_valor_data');
						}
					elseif ($this->pratica_indicador_acumulacao=='saldo') {
						$sql->adTabela('(SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, pratica_indicador_valor_valor FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador='.(int)$this->pratica_indicador_id.' ORDER BY pratica_indicador_valor_data DESC)','tabela');
						$sql->adCampo('pratica_indicador_valor_data, pratica_indicador_valor_valor AS valor, pratica_indicador_valor_valor AS max, pratica_indicador_valor_valor AS min');
						}
					else{	
						//considerar media simples para demais casos
						$sql->adTabela($nome_tabela);
						$sql->adCampo('AVG(pratica_indicador_valor_valor) AS valor, MAX(`pratica_indicador_valor_valor`) AS max, MIN(`pratica_indicador_valor_valor`) AS min, pratica_indicador_valor_data');
						}	

					$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id);
					$sql->adGrupo('ano(pratica_indicador_valor_data)');
					$sql->adGrupo('mes(pratica_indicador_valor_data)');
					if ($agrupar=='semana') $sql->adGrupo('semana_ano(pratica_indicador_valor_data)');
					else $sql->adGrupo('dia(pratica_indicador_valor_data)');
					}	
				elseif ($agrupar=='mes' || $agrupar=='bimestre' || $agrupar=='trimestre' || $agrupar=='quadrimestre' || $agrupar=='semestre'){
					if ($this->pratica_indicador_acumulacao=='soma') {
						$sql->adTabela('(SELECT pratica_indicador_valor_data, SUM(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data))', 'grupo_dia');
						$sql->adCampo('pratica_indicador_valor_data, SUM(`valor_mes`) AS valor, MAX(`valor_mes`) AS max, MIN(`valor_mes`) AS min');
						}
					elseif ($this->pratica_indicador_acumulacao=='saldo') {
						$sql->adTabela('(SELECT pratica_indicador_valor_data, pratica_indicador_valor_valor FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador='.(int)$this->pratica_indicador_id.' ORDER BY pratica_indicador_valor_data DESC)','tabela');
						$sql->adCampo('pratica_indicador_valor_data, pratica_indicador_valor_valor AS valor, pratica_indicador_valor_valor AS max, pratica_indicador_valor_valor AS min');
						}	
					else{	
						//considerar media simples para demais casos
						$sql->adTabela('(SELECT pratica_indicador_valor_data, AVG(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data))', 'grupo_dia');
						$sql->adCampo('pratica_indicador_valor_data, AVG(`valor_mes`) AS valor, MAX(`valor_mes`) AS max, MIN(`valor_mes`) AS min');
						}	
						
					if ($agrupar=='bimestre')	$sql->adCampo('CASE mes(pratica_indicador_valor_data)  WHEN 1 THEN 1 WHEN 2 THEN 1 WHEN 3 THEN 2 WHEN 4 THEN 2 WHEN 5 THEN 3 WHEN 6 THEN 3 WHEN 7 THEN 4 WHEN 8 THEN 4 WHEN 9 THEN 5 WHEN 10 THEN 5 WHEN 11 THEN 6 WHEN 12 THEN 6 END as bloco_ano');
					elseif ($agrupar=='trimestre') $sql->adCampo('CASE mes(pratica_indicador_valor_data)  WHEN 1 THEN 1 WHEN 2 THEN 1 WHEN 3 THEN 1 WHEN 4 THEN 2 WHEN 5 THEN 2 WHEN 6 THEN 2 WHEN 7 THEN 3 WHEN 8 THEN 3 WHEN 9 THEN 3 WHEN 10 THEN 4 WHEN 11 THEN 4 WHEN 12 THEN 4 END as bloco_ano');
					elseif ($agrupar=='quadrimestre') $sql->adCampo('CASE mes(pratica_indicador_valor_data)  WHEN 1 THEN 1 WHEN 2 THEN 1 WHEN 3 THEN 1 WHEN 4 THEN 1 WHEN 5 THEN 2 WHEN 6 THEN 2 WHEN 7 THEN 2 WHEN 8 THEN 2 WHEN 9 THEN 3 WHEN 10 THEN 3 WHEN 11 THEN 3 WHEN 12 THEN 3 END as bloco_ano');
					elseif ($agrupar=='semestre') $sql->adCampo('CASE mes(pratica_indicador_valor_data)  WHEN 1 THEN 1 WHEN 2 THEN 1 WHEN 3 THEN 1 WHEN 4 THEN 1 WHEN 5 THEN 1 WHEN 6 THEN 1 WHEN 7 THEN 2 WHEN 8 THEN 2 WHEN 9 THEN 2 WHEN 10 THEN 2 WHEN 11 THEN 2 WHEN 12 THEN 2 END as bloco_ano');
				
					$sql->adGrupo('ano(pratica_indicador_valor_data)');
					if ($agrupar=='mes') $sql->adGrupo('mes(pratica_indicador_valor_data)');
					else $sql->adGrupo('bloco_ano');
					}
				elseif ($agrupar=='ano'){
					if ($this->pratica_indicador_acumulacao=='soma') {
						$sql->adTabela('(SELECT pratica_indicador_valor_data, SUM(`valor_mes`) AS valor_ano FROM (SELECT pratica_indicador_valor_data, SUM(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data)) AS grupo_dia GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data))','grupo_mes');
						$sql->adCampo('pratica_indicador_valor_data, SUM(`valor_ano`) AS valor, MAX(`valor_ano`) AS max, MIN(`valor_ano`) AS min, pratica_indicador_valor_data');
						}
					elseif ($this->pratica_indicador_acumulacao=='saldo') {
						$sql->adTabela('(SELECT pratica_indicador_valor_data, pratica_indicador_valor_valor FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador='.(int)$this->pratica_indicador_id.' ORDER BY pratica_indicador_valor_data DESC)','tabela');
						$sql->adCampo('pratica_indicador_valor_data, pratica_indicador_valor_valor AS valor, pratica_indicador_valor_valor AS max, pratica_indicador_valor_valor AS min');
						}		
					else {
						//considerar media simples para demais casos
						$sql->adTabela('(SELECT pratica_indicador_valor_data, AVG(`valor_mes`) AS valor_ano FROM (SELECT pratica_indicador_valor_data, AVG(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data)) AS grupo_dia GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data))','grupo_mes');
						$sql->adCampo('pratica_indicador_valor_data, AVG(`valor_ano`) AS valor, MAX(`valor_ano`) AS max, MIN(`valor_ano`) AS min, pratica_indicador_valor_data');
						}	
					$sql->adGrupo('ano(pratica_indicador_valor_data)');
					}
				else{
					if ($this->pratica_indicador_acumulacao=='soma') {
						$sql->adTabela('(SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, SUM(`valor_mes`) AS valor_ano FROM (SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, SUM(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data)) AS grupo_dia GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data))','grupo_mes');
						$sql->adCampo('pratica_indicador_valor_indicador, pratica_indicador_valor_data, SUM(`valor_ano`) AS valor, MAX(`valor_ano`) AS max, MIN(`valor_ano`) AS min, pratica_indicador_valor_data');
						$sql->adGrupo('pratica_indicador_valor_indicador');
						}
					elseif ($this->pratica_indicador_acumulacao=='saldo') {
						$sql->adTabela('(SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, pratica_indicador_valor_valor FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador='.(int)$this->pratica_indicador_id.' ORDER BY pratica_indicador_valor_data DESC)','tabela');
						$sql->adCampo('pratica_indicador_valor_indicador, pratica_indicador_valor_data, pratica_indicador_valor_valor AS valor, pratica_indicador_valor_valor AS max, pratica_indicador_valor_valor AS min');
						$sql->adGrupo('pratica_indicador_valor_indicador');
						}		
					else {
						//considerar media simples para demais casos
						$sql->adTabela('(SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, AVG(`valor_mes`) AS valor_ano FROM (SELECT pratica_indicador_valor_indicador, pratica_indicador_valor_data, AVG(`pratica_indicador_valor_valor`) AS valor_mes FROM '.$nome_tabela.' WHERE pratica_indicador_valor_indicador = '.(int)$this->pratica_indicador_id.' GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data), dia(pratica_indicador_valor_data)) AS grupo_dia GROUP BY ano(pratica_indicador_valor_data), mes(pratica_indicador_valor_data))','grupo_mes');
						$sql->adCampo('pratica_indicador_valor_indicador, pratica_indicador_valor_data, AVG(`valor_ano`) AS valor, MAX(`valor_ano`) AS max, MIN(`valor_ano`) AS min, pratica_indicador_valor_data');
						$sql->adGrupo('pratica_indicador_valor_indicador');
						}		
					}	
				$sql->adOrdem('pratica_indicador_valor_data DESC');
				if ($data_final) $sql->adOnde('pratica_indicador_valor_data <=\''.$data_final.'\'');
				elseif ($inicio && $fim) $sql->adOnde('pratica_indicador_valor_data <=\''.($fim <= date('Y-m-d') ? $fim : date('Y-m-d')).'\' AND pratica_indicador_valor_data >=\''.$inicio.'\'');
				elseif ($ano) $sql->adOnde('ano(pratica_indicador_valor_data) <=\''.$ano.'\'');	
				$sql->setLimite(0,$qnt_pontos);
				$pontos_achados = $sql->Lista();
				$sql->limpar();		
				//data a ser considerada como referencia
				if ($fim) $data_final=$fim;
				elseif ($ano && $ano!=date('Y')) $data_final=$ano.'-12-31';
				elseif ($ano || !$data_final) $data_final=date('Y-m-d');
				
				$pontos_corrigidos=array();
				//Preparar vetor com 0 quando não houver valor num deterinado mes, etc.

				if ($agrupar=='ano'){
					$max=(int)substr($data_final, 0, 4);
					$min=(int)($max-($qnt_pontos-1));
					$ano_atual=(int)$max;

					for ($i=0; $i < $qnt_pontos; $i++) $pontos_corrigidos[$ano_atual-$i]=array('pratica_indicador_valor_data' => ($ano_atual-$i).'-01-01', 'valor' => null, 'max' => null, 'min' => null);
					
					foreach($pontos_achados as $cada_valor){
						$ano_verificado=substr($cada_valor['pratica_indicador_valor_data'], 0, 4);
						if ($ano_verificado <=$max && $ano_verificado >=$min) {
							$cada_valor['pratica_indicador_valor_data']=$ano_verificado.'-01-01';
							$pontos_corrigidos[$ano_verificado]=$cada_valor;
							}
						}
					}
				
				elseif ($agrupar=='semestre'){	
					$bloco_ano=array(1 => '1', 2 => '1', 3 => '1', 4 => '1', 5 => '1', 6 => '1', 7 => '2', 8 => '2', 9 => '2', 10 => '2', 11 => '2', 12 => '2');
					$mudanca_mes=array(1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 7, 8 => 7, 9 => 7, 10 => 7, 11 => 7, 12 => 7);	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$indice_permitido=array();
					$mes_atual=$mudanca_mes[$mes_atual];

					for ($i=1; $i <= $qnt_pontos; $i++){
						$indice_permitido[$ano_atual.'-'.($mes_atual+5 < 10 ? '0' : '').($mes_atual+5)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+4 < 10 ? '0' : '').($mes_atual+4)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+3 < 10 ? '0' : '').($mes_atual+3)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+2 < 10 ? '0' : '').($mes_atual+2)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+1 < 10 ? '0' : '').($mes_atual+1)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$mes_atual=$mes_atual-6;
						if ($mes_atual < 1) {$mes_atual=7; $ano_atual--;}
						}	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$mes_atual=$mudanca_mes[$mes_atual];
					
					foreach($pontos_achados as $cada_valor){
						$indice=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						if (isset($indice_permitido[$indice]) && !isset($pontos_corrigidos[$indice_permitido[$indice]])) {
							$pontos_corrigidos[$indice]=array('pratica_indicador_valor_data' => $indice_permitido[$indice].'-01', 'valor' => null, 'max' => null, 'min' => null, 'bloco_ano' => $bloco_ano[$mes_atual]);
							}
						if (isset($indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)])) {
							$cada_valor['pratica_indicador_valor_data']=$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)].'-01';
							$pontos_corrigidos[$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)]]=$cada_valor;
							}
						$mes_atual=$mes_atual-6;
						if ($mes_atual < 1) {$mes_atual=7; $ano_atual--;}
						}
					}
				
				elseif ($agrupar=='quadrimestre'){		
					$bloco_ano=array(1 => '1', 2 => '1', 3 => '1', 4 => '1', 5 => '2', 6 => '2', 7 => '2', 8 => '2', 9 => '3', 10 => '3', 11 => '3', 12 => '3');
					$mudanca_mes=array(1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 5, 6 => 5, 7 => 5, 8 => 5, 9 => 9, 10 => 9, 11 => 9, 12 => 9);	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$indice_permitido=array();
					$mes_atual=$mudanca_mes[$mes_atual];
					for ($i=1; $i <= $qnt_pontos; $i++){
						$indice_permitido[$ano_atual.'-'.($mes_atual+3 < 10 ? '0' : '').($mes_atual+3)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+2 < 10 ? '0' : '').($mes_atual+2)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+1 < 10 ? '0' : '').($mes_atual+1)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$mes_atual=$mes_atual-4;
						if ($mes_atual < 1) {$mes_atual=9; $ano_atual--;}
						}	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$mes_atual=$mudanca_mes[$mes_atual];
					foreach($pontos_achados as $cada_valor){
						$indice=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						if (isset($indice_permitido[$indice]) && !isset($pontos_corrigidos[$indice_permitido[$indice]])) {
							$pontos_corrigidos[$indice]=array('pratica_indicador_valor_data' => $indice_permitido[$indice].'-01', 'valor' => null, 'max' => null, 'min' => null, 'bloco_ano' => $bloco_ano[$mes_atual]);
							}
						if (isset($indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)])) {
							$cada_valor['pratica_indicador_valor_data']=$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)].'-01';
							$pontos_corrigidos[$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)]]=$cada_valor;
							}
						$mes_atual=$mes_atual-4;
						if ($mes_atual < 1) {$mes_atual=9; $ano_atual--;}
						}
					}
				
				elseif ($agrupar=='trimestre'){	
					$bloco_ano=array(1 => '1', 2 => '1', 3 => '1', 4 => '2', 5 => '2', 6 => '2', 7 => '3', 8 => '3', 9 => '3', 10 => '4', 11 => '4', 12 => '4');
					$mudanca_mes=array(1 => 1, 2 => 1, 3 => 1, 4 => 4, 5 => 4, 6 => 4, 7 => 7, 8 => 7, 9 => 7, 10 => 10, 11 => 10, 12 => 10);	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$indice_permitido=array();
					$mes_atual=$mudanca_mes[$mes_atual];
					for ($i=1; $i <= $qnt_pontos; $i++){
						$indice_permitido[$ano_atual.'-'.($mes_atual+2 < 10 ? '0' : '').($mes_atual+2)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual+1 < 10 ? '0' : '').($mes_atual+1)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$mes_atual=$mes_atual-3;
						if ($mes_atual < 1) {$mes_atual=10; $ano_atual--;}
						}				
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$mes_atual=$mudanca_mes[$mes_atual];
					foreach($pontos_achados as $cada_valor){
						$indice=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						if (isset($indice_permitido[$indice]) && !isset($pontos_corrigidos[$indice_permitido[$indice]])) {
							$pontos_corrigidos[$indice]=array('pratica_indicador_valor_data' => $indice_permitido[$indice].'-01', 'valor' => null, 'max' => null, 'min' => null, 'bloco_ano' => $bloco_ano[$mes_atual]);
							}
						if (isset($indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)])) {
							$cada_valor['pratica_indicador_valor_data']=$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)].'-01';
							$pontos_corrigidos[$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)]]=$cada_valor;
							}
						$mes_atual=$mes_atual-3;
						if ($mes_atual < 1) {$mes_atual=10; $ano_atual--;}
						}
					}
				
				elseif ($agrupar=='bimestre'){
					$bloco_ano=array(1 => '1', 2 => '1', 3 => '2', 4 => '2', 5 => '3', 6 => '3', 7 => '4', 8 => '4', 9 => '5', 10 => '5', 11 => '6', 12 => '6');	
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$indice_permitido=array();
					$mes_atual=($mes_atual % 2 ? $mes_atual : $mes_atual-1);
					for ($i=1; $i <= $qnt_pontos; $i++){
						$indice_permitido[$ano_atual.'-'.($mes_atual+1 < 10 ? '0' : '').($mes_atual+1)]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$indice_permitido[$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual]=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						$mes_atual=$mes_atual-2;
						if ($mes_atual < 1) {$mes_atual=11; $ano_atual--;}
						}
					$mes_atual=(int)substr($data_final, 5, 2);
					$ano_atual=(int)substr($data_final, 0, 4);
					$mes_atual=($mes_atual % 2 ? $mes_atual : $mes_atual-1);
					foreach($pontos_achados as $cada_valor){
						$indice=$ano_atual.'-'.($mes_atual < 10 ? '0' : '').$mes_atual;
						if (isset($indice_permitido[$indice]) && !isset($pontos_corrigidos[$indice_permitido[$indice]])) {
							$pontos_corrigidos[$indice]=array('pratica_indicador_valor_data' => $indice_permitido[$indice].'-01', 'valor' => null, 'max' => null, 'min' => null, 'bloco_ano' => $bloco_ano[$mes_atual]);
							}
						if (isset($indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)])) {
							$cada_valor['pratica_indicador_valor_data']=$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)].'-01';
							$pontos_corrigidos[$indice_permitido[substr($cada_valor['pratica_indicador_valor_data'], 0, 7)]]=$cada_valor;
							}
						$mes_atual=$mes_atual-2;
						if ($mes_atual < 1) {$mes_atual=11; $ano_atual--;}
						}
					}

				elseif ($agrupar=='mes'){
					$min=strtotime('-'.($qnt_pontos-1).' month', strtotime($data_final));
					$min=date('Y-m-d', $min);
					$max=substr($data_final, 0, 7);
					$min=substr($min, 0, 7);
					$mes_atual=$data_final;
					for ($i=0; $i < $qnt_pontos; $i++) {
						if ($i) {
							$data_atual=strtotime('-'.$i.' month', strtotime($mes_atual));
							$data_atual=date('Y-m-d', $data_atual);
							}
						else $data_atual=$mes_atual;
						$pontos_corrigidos[substr($data_atual, 0, 7)]=array('pratica_indicador_valor_data' => substr($data_atual, 0, 7).'-01', 'valor' => null, 'max' => null, 'min' => null);
						}

					foreach($pontos_achados as $cada_valor){
						$data_verificada=substr($cada_valor['pratica_indicador_valor_data'], 0, 7);
						if ($data_verificada <=$max && $data_verificada >=$min) {
							$cada_valor['pratica_indicador_valor_data']=substr($cada_valor['pratica_indicador_valor_data'], 0, 7).'-01';
							$pontos_corrigidos[$data_verificada]=$cada_valor;
							}
						}
					}

				elseif ($agrupar=='semana'){
					//checar se a data atual já é um domingo
					$ser_domingo = (int)date('w',strtotime($data_final));
					
					if ($ser_domingo==0) $data_domingo=$data_final;
					else {
						$data_domingo=strtotime('last Sunday', strtotime($data_final));
						$data_domingo=date('Y-m-d', $data_domingo);
						}
					$max=$data_domingo;
					for ($i=0; $i < $qnt_pontos; $i++) {
						if ($i) {
							$data_atual=strtotime('-'.$i.' week', strtotime($data_domingo));
							$data_atual=date('Y-m-d', $data_atual);
							}
						else $data_atual=$data_domingo;
						$pontos_corrigidos[$data_atual]=array('pratica_indicador_valor_data' => $data_atual, 'valor' => null, 'max' => null, 'min' => null);
						}
					$min=$data_atual;
					foreach($pontos_achados as $cada_valor){
						$data_verificada=$cada_valor['pratica_indicador_valor_data'];
						$ser_domingo = (int)date('w',strtotime($data_verificada));
						if ($ser_domingo==0) $data_domingo=$data_verificada;
						else {
							$data_domingo=strtotime('last Sunday', strtotime($data_verificada));
							$data_domingo=date('Y-m-d', $data_domingo);
							}
						if ($data_domingo <=$max && $data_domingo >=$min) {
							$cada_valor['pratica_indicador_valor_data']=$data_domingo;
							$pontos_corrigidos[$data_domingo]=$cada_valor;
							}
						}
					}
				
				elseif ($agrupar=='dia'){
					$min=strtotime('-'.($qnt_pontos-1).' day', strtotime($data_final));
					$min=date('Y-m-d', $min);
					$max=$data_final;
					$dia_atual=$data_final;
					
					for ($i=0; $i < $qnt_pontos; $i++) {
						if ($i) {
							$data_atual=strtotime('-'.$i.' day', strtotime($dia_atual));
							$data_atual=date('Y-m-d', $data_atual);
							}
						else $data_atual=$dia_atual;
						$pontos_corrigidos[$data_atual]=array('pratica_indicador_valor_data' => $data_atual, 'valor' => null, 'max' => null, 'min' => null);
						}
					
					foreach($pontos_achados as $cada_valor){
						$data_verificada=$cada_valor['pratica_indicador_valor_data'];
						if ($data_verificada <=$max && $data_verificada >=$min) {
							$pontos_corrigidos[$data_verificada]=$cada_valor;
							}
						}
					}
				
				elseif ($agrupar=='nenhum'){
					foreach($pontos_achados as $linha) $pontos_corrigidos[$linha['pratica_indicador_valor_data']]=array('pratica_indicador_valor_data' => $linha['pratica_indicador_valor_data'], 'valor' => $linha['valor'], 'max' => $linha['max'], 'min' => $linha['min']);
					}	
					
				/************************************************
				Checar como fazer fórmula por nenhum agrupamento
				
				*************************************************/					
				krsort($pontos_corrigidos);
				foreach($pontos_corrigidos as $cada_valor){			
					$data=str_replace('-','', $cada_valor['pratica_indicador_valor_data']);
					if ($agrupar=='ano') $data=substr($data, 0, 4);
					elseif($agrupar=='mes') $data=substr($data, 0, 6);
					
					if ($valor_bruto) $calculo_valor=$cada_valor['valor'];
					else{
						if ($this->pratica_indicador_sentido==2){
							if ($this->pratica_indicador_valor_meta!=0 && $cada_valor['valor']!=0) {
								if ($cada_valor['valor'] >= $this->pratica_indicador_valor_meta){
									$calculo_valor=($this->pratica_indicador_valor_meta/$cada_valor['valor'])*100;
									if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
									else if ($calculo_valor < 0) $calculo_valor=0;
									}
								else{
									$calculo_valor=($cada_valor['valor']/$this->pratica_indicador_valor_meta)*100;
									if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
									else if ($calculo_valor < 0) $calculo_valor=0;
									}
								}
							else $calculo_valor=($cada_valor['valor']==$this->pratica_indicador_valor_meta ? 100 : 0);

							}
						else{	
							if (($this->pratica_indicador_valor_meta !=0 && $this->pratica_indicador_sentido) || ($cada_valor['valor'] !=0 && !$this->pratica_indicador_sentido)) {
								if ($this->pratica_indicador_sentido){
									$calculo_valor=($cada_valor['valor']/$this->pratica_indicador_valor_meta)*100;
									if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
									else if ($calculo_valor < 0) $calculo_valor=0;
									}
								else{
									$calculo_valor=($this->pratica_indicador_valor_meta/$cada_valor['valor'])*100;
									if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
									else if ($calculo_valor < 0) $calculo_valor=0;
									}
								}
							else {

								if ($cada_valor['valor']===null) $calculo_valor=null;
								else $calculo_valor=($cada_valor['valor']==0 ? 100 :0);
								}
							}					
						}
					$pontos[$data]['valor']=$calculo_valor;
					$pontos[$data]['max']=$cada_valor['max'];
					$pontos[$data]['min']=$cada_valor['min'];
					if (!$valor_bruto){
						//não faz sentido checar máximos e mínimos se estou usando pontuação
						$pontos[$data]['max']=$calculo_valor;
						$pontos[$data]['min']=$calculo_valor;
						}
					
					}
	
			}
		elseif ($this->pratica_indicador_composicao){
			//composicao
			$sql->adTabela('pratica_indicador_composicao');
			$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_composicao_filho');
			$sql->adCampo('pratica_indicador_composicao_filho, pratica_indicador_composicao_peso, pratica_indicador_sentido');
			$sql->adOnde('pratica_indicador_composicao_pai = '.(int)$this->pratica_indicador_id);
			$linhas = $sql->Lista();
			$sql->limpar();
			$valores=0;

			if ($qnt_pontos)$valores=array();
			else $valores=0;
			$pesos=0;
			
			$pontos=array();
			
			//evitar loop infinito
			$this->ja_usado[$this->pratica_indicador_id]=$this->pratica_indicador_id;
			
			foreach($linhas as $linha){
				//teste de loop infinito
				if (!isset($this->ja_usado[$linha['pratica_indicador_composicao_filho']])){
				
					$obj_indicador=new Indicador($linha['pratica_indicador_composicao_filho'], $this->ano, $this->inicio, $this->fim, $this->ja_usado);
				
		
					$vetor_pontos=$obj_indicador->Pontos($qnt_pontos, $agrupar, $ano, $inicio, $fim);
					
					//somente um ponto a ser plotado
					if ($this->pratica_indicador_periodo_anterior && is_array($vetor_pontos)) array_shift($vetor_pontos);

					foreach((array)$vetor_pontos as $data => $cada_valor){
						if(isset($pontos[$data]['valor'])) $pontos[$data]['valor']+=($cada_valor['valor']*$linha['pratica_indicador_composicao_peso']);
						else $pontos[$data]['valor']=($cada_valor['valor']*$linha['pratica_indicador_composicao_peso']);
						if (!isset($pontos[$data]['max'])) $pontos[$data]['max']=$cada_valor['valor'];
						elseif($cada_valor['valor'] > $pontos[$data]['max']) $pontos[$data]['max']=$cada_valor['valor'];
						if (!isset($pontos[$data]['min'])) $pontos[$data]['min']=$cada_valor['valor'];
						elseif($cada_valor['valor'] < $pontos[$data]['min']) $pontos[$data]['min']=$cada_valor['valor'];
						}
						
					$pesos+=$linha['pratica_indicador_composicao_peso'];
					}
				}
			foreach($pontos as $chave => $valores){
				if ($pesos)	$pontos[$chave]['valor']=$pontos[$chave]['valor']/$pesos;
				else $pontos[$chave]['valor']=0;
				}
			//acrescentar vetor nulo no inicio, pois sera descardado na outra party
			if ($this->pratica_indicador_periodo_anterior){
				array_unshift($pontos, 0);
				}	
			}

		elseif ($this->pratica_indicador_formula){
			//formula
			$sql->adTabela('pratica_indicador_formula');
			$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_formula_filho');
			$sql->adCampo('pratica_indicador_formula_filho, pratica_indicador_formula_ordem, pratica_indicador_sentido, pratica_indicador_formula_rocado');
			$sql->adOnde('pratica_indicador_formula_pai = '.(int)$this->pratica_indicador_id);
			$linhas = $sql->Lista();
			$sql->limpar();

			$valores=0;
			if ($qnt_pontos)$valores=array();
			else $valores=0;
			
			$pesos=0;
			$pontos=array();
			$valor_indicador=array();
			$novos_pontos=array();
			foreach($linhas as $linha){
				$obj_indicador= new Indicador($linha['pratica_indicador_formula_filho'], ($agrupar!='nenhum' ? $this->ano : null), ($agrupar!='nenhum' ? $this->inicio : null), ($agrupar!='nenhum' ? $this->fim : null));
				//valor bruto de cada indicador da formula
				$vetor_pontos=$obj_indicador->Pontos(($qnt_pontos+$linha['pratica_indicador_formula_rocado']), $agrupar, $ano, $inicio, $fim, true);
				if (!$linha['pratica_indicador_formula_rocado']){
					foreach((array)$vetor_pontos as $data => $cada_valor){
						
						if ($agrupar=='nenhum') $data=date('Ymd');
				
						$pontos[$data][$linha['pratica_indicador_formula_ordem']]=$cada_valor['valor'];
						}
					}	
				else{
					//rocado X posições
					$vetor_auxiliar=array();
					foreach((array)$vetor_pontos as $data => $cada_valor) $vetor_auxiliar[]=$cada_valor['valor'];
					$qnt=0;
					$avanco=$linha['pratica_indicador_formula_rocado'];
					foreach((array)$vetor_pontos as $data => $cada_valor){
						if (isset($vetor_auxiliar[$qnt+$avanco])) $pontos[$data][$linha['pratica_indicador_formula_ordem']]=$vetor_auxiliar[$qnt+$avanco];
						$qnt++;
						}
					}	
				}

			foreach((array)$pontos as $data => $valores){
				$formula_final=$this->pratica_indicador_calculo;
				foreach($valores as $chave => $valor){
					$formula_final=str_replace('I'.($chave<10 ? '0' : '' ).$chave , '('.($valor ? $valor : 0).')', $formula_final);
					}
				$novos_pontos[$data]['valor']=$this->calcular_string($formula_final);
				$novos_pontos[$data]['max']=$novos_pontos[$data]['valor'];
				$novos_pontos[$data]['min']=$novos_pontos[$data]['valor'];
				$novos_pontos[$data]['meta']=0;
				}

			//calcular a pontuação
			if (!$valor_bruto){
				foreach($novos_pontos as $chave => $valor){
					if ($this->pratica_indicador_sentido==2){
						if ($this->pratica_indicador_valor_meta!=0 && $valor['valor']!=0) {
							if ($valor['valor']>=$this->pratica_indicador_valor_meta){
								$calculo_valor=($this->pratica_indicador_valor_meta/$valor['valor'])*100;
								if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
								else if ($calculo_valor < 0) $calculo_valor=0;
								}
							else{
								$calculo_valor=($valor['valor']/$this->pratica_indicador_valor_meta)*100;
								if ($calculo_valor > $config['porcentagem_maxima']) $calculo_valor=$config['porcentagem_maxima'];
								else if ($calculo_valor < 0) $calculo_valor=0;
								}
							}
						else $calculo_valor=($valor['valor']==$this->pratica_indicador_valor_meta ? 100 : 0);
						}
					else if (($this->pratica_indicador_valor_meta!=0 && $this->pratica_indicador_sentido==1) || ($valor['valor']!=0 && !$this->pratica_indicador_sentido)) $calculo_valor=(($this->pratica_indicador_sentido ? (($valor['valor']/$this->pratica_indicador_valor_meta)> ($config['porcentagem_maxima']/100) ? ($config['porcentagem_maxima']/100) : ($valor['valor']/$this->pratica_indicador_valor_meta)): (($this->pratica_indicador_valor_meta/$valor['valor'])> ($config['porcentagem_maxima']/100) ? ($config['porcentagem_maxima']/100) : ($this->pratica_indicador_valor_meta/$valor['valor'])))*100);
					else $calculo_valor=($valor['valor']==0 ? 100 : 0);
					
					if ($calculo_valor < 0) $calculo_valor=0;
					
					$novos_pontos[$chave]['valor']=$calculo_valor;
					}
				}
			$pontos=$novos_pontos;
			}

		elseif($this->pratica_indicador_campo_projeto && $Aplic->profissional){
			include_once BASE_DIR.'/modulos/praticas/indicador_simples.class_pro.php';
			$pontos=valor_projeto($this, $agrupar, $valor_bruto);
			}	
			
		elseif($this->pratica_indicador_campo_tarefa && $Aplic->profissional){
			include_once BASE_DIR.'/modulos/praticas/indicador_simples.class_pro.php';
			$pontos=valor_tarefa($this, $agrupar, $valor_bruto);
			}	
			
		elseif($this->pratica_indicador_campo_acao && $Aplic->profissional){
			include_once BASE_DIR.'/modulos/praticas/indicador_simples.class_pro.php';
			$pontos=valor_acao($this, $agrupar, $valor_bruto);
			}	
					
		if (isset($pontos))return $pontos;
    else return array();
		}
	}
?>