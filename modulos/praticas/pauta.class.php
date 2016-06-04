<?php

class Cpauta {
	
	public $pratica_modelo_id=null;
	public $ano=null;
	public $cia_id=null;
	public $criterios=array();
	public $itens=array();
	public $praticas=array();
	public $resultados=array();
	public $pontuacao=array();
	public $pontuacao_subitem=array();
	public $porcentagem_item=array();
	public $pontuacao_item=array();
	public $pontuacao_criterio=array();
	public $porcentagem_criterio=array();
	public $pontuacao_final=0;
	public $pontuacao_maxima=0;
	public $tipo_pauta=null;
	public $campos=array();
	
	public function __construct($cia_id=0, $pratica_modelo_id=0, $ano=0) {
		$this->cia_id=$cia_id;
		$this->pratica_modelo_id=$pratica_modelo_id;
		$this->ano=$ano;

   	$sql = new BDConsulta();

		$sql->adTabela('pratica_modelo');
		$sql->adCampo('pratica_modelo_pontos, pratica_modelo_tipo');
		$sql->adOnde('pratica_modelo_id='.(int)$this->pratica_modelo_id);
		$linha=$sql->linha();
		$sql->limpar();
		$this->pontuacao_maxima=$linha['pratica_modelo_pontos'];
		$this->tipo_pauta=$linha['pratica_modelo_tipo'];

   	$sql->adTabela('pratica_regra');
		$sql->adCampo('pratica_regra_campo, pratica_regra_percentagem, pratica_regra_valor, subitem');
		$sql->adOnde('pratica_modelo_id='.(int)$this->pratica_modelo_id);
		$regras_lista=$sql->Lista();
		$sql->limpar();
		$regras=array();
		foreach($regras_lista as $linha) $regras[$linha['pratica_regra_campo']][$linha['pratica_regra_valor']]=(int)$linha['pratica_regra_percentagem'];

		$sql->adTabela('pratica_regra');
		$sql->adCampo('DISTINCT pratica_regra_campo, pratica_regra_ordem, subitem, pratica_regra_resultado');
		$sql->adOnde('pratica_modelo_id='.(int)$this->pratica_modelo_id);
		$sql->adOrdem('subitem ASC, pratica_regra_ordem');
		$regras_lista=$sql->lista();
		$sql->limpar();
		$campos=array();
		foreach($regras_lista as $linha) $campos[$linha['pratica_regra_campo']]=array('subitem' => $linha['subitem'], 'ordem' => $linha['pratica_regra_ordem'], 'resultado' => $linha['pratica_regra_resultado']);
		$this->campos=$campos;
		
		
		if (isset($this->campos['pratica_indicador_complemento'])){
			$sql->adTabela('pratica_indicador_nos_marcadores');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_nos_marcadores.pratica_indicador_id');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=1');
			$sql->adOnde('pratica_marcador.pratica_marcador_id=1079 OR pratica_marcador.pratica_marcador_id=1081');
			$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id');
			$atende_8c_8e=$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_id');
			$sql->limpar();
			}
   			
   	$indicador=array();
   	//lista de indicadores sem repetição
   	$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_agrupar, pratica_indicador_sentido, 
		pratica_indicador_requisito_relevante AS pratica_indicador_relevante, 
		pratica_indicador_requisito_favoravel AS pratica_indicador_favoravel, 
		pratica_indicador_requisito_tendencia AS pratica_indicador_tendencia,
		pratica_indicador_requisito_superior AS pratica_indicador_superior,
		pratica_indicador_requisito_atendimento AS pratica_indicador_atendimento, 
		pratica_indicador_requisito_lider AS pratica_indicador_lider, 
		pratica_indicador_requisito_excelencia AS pratica_indicador_excelencia, 
		pratica_indicador_requisito_referencial AS pratica_indicador_referencial,
		pratica_indicador_requisito_estrategico AS pratica_indicador_estrategico
		');
		if ($this->tipo_pauta=='fnq_2015'){
			$sql->adCampo('
				0 AS pratica_indicador_complemento,
				0 AS pratica_indicador_8c_8e,
				0 AS pratica_indicador_estrategico_favoravel,
				0 AS pratica_indicador_8c_8e2,
				0 AS pratica_indicador_estrategico_superior,
				0 AS pratica_indicador_8c_8e3,
				0 AS pratica_indicador_8c_8e4,
				0 AS pratica_indicador_estrategico_atendimento,
				0 AS pratica_indicador_8c_8e5,
				0 AS pratica_indicador_estrategico_lider,
				0 AS pratica_indicador_estrategico_excelencia
				');
			}
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
		$sql->adOnde('pratica_criterio_resultado=1');
		$sql->adOnde('pratica_indicador_requisito_relevante=1');
		$lista_indicadores=$sql->ListaChaveSimples('pratica_indicador_id');
		$sql->limpar();
		$indicadores=array();
		
		foreach($lista_indicadores as $indicador){
			$indicador['pratica_indicador_referencial']=($indicador['pratica_indicador_referencial'] ? 1 : 0);
			$indicadores[$indicador['pratica_indicador_id']]=$indicador;
			}
		
			
		//lista de indicadores podendo ter repetição
   	$sql->adTabela('pratica_indicador_nos_marcadores');
   	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
   	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_item_id, pratica_marcador.pratica_marcador_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
		$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$this->ano);
		$sql->adOnde('pratica_criterio_resultado=1');
		$sql->adOnde('pratica_indicador_requisito_relevante=1');
		$lista_indicadores=$sql->Lista();
		$sql->limpar();


   	//zerar vetor
		$sql->adTabela('pratica_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_item_id');
		$sql->adOnde('pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_criterio_resultado=1');
		$itens=$sql->carregarColuna();
		$sql->limpar();
   
   
   	$ignorar=array(
			'pratica_indicador_complemento',
			'pratica_indicador_8c_8e',
			'pratica_indicador_estrategico_favoravel',
			'pratica_indicador_8c_8e2',
			'pratica_indicador_estrategico_superior',
			'pratica_indicador_8c_8e3',
			'pratica_indicador_8c_8e4',
			'pratica_indicador_estrategico_atendimento',
			'pratica_indicador_8c_8e5');
   
 		$campo_resultado=array();
 		foreach ($this->campos as $chave => $vetor) {
			if ($vetor['resultado']==1) $campo_resultado[$chave]=0;
			}
		$campo_resultado['qnt']=0;

   	foreach($itens as $item_id) $resultados[$item_id]=$campo_resultado;
   	
  	foreach($lista_indicadores as $linha){
  		foreach($campo_resultado as $chave => $valor) if ($chave!='qnt' && !in_array($chave, $ignorar)) $resultados[$linha['pratica_item_id']][$chave]+=$indicadores[$linha['pratica_indicador_id']][$chave];
			
			if (isset($this->campos['pratica_indicador_8c_8e']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_favoravel'] && isset($atende_8c_8e[$linha['pratica_indicador_id']])) $resultados[$linha['pratica_item_id']]['pratica_indicador_8c_8e']+=1;
			if (isset($this->campos['pratica_indicador_8c_8e2']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_superior'] && isset($atende_8c_8e[$linha['pratica_indicador_id']])) $resultados[$linha['pratica_item_id']]['pratica_indicador_8c_8e2']+=1;
			if (isset($this->campos['pratica_indicador_8c_8e3']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_superior'] && isset($atende_8c_8e[$linha['pratica_indicador_id']])) $resultados[$linha['pratica_item_id']]['pratica_indicador_8c_8e3']+=1;
			if (isset($this->campos['pratica_indicador_8c_8e4']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_atendimento'] && isset($atende_8c_8e[$linha['pratica_indicador_id']])) $resultados[$linha['pratica_item_id']]['pratica_indicador_8c_8e4']+=1;
			if (isset($this->campos['pratica_indicador_8c_8e5']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_atendimento'] && isset($atende_8c_8e[$linha['pratica_indicador_id']])) $resultados[$linha['pratica_item_id']]['pratica_indicador_8c_8e5']+=1;

			if (isset($this->campos['pratica_indicador_estrategico_favoravel']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_favoravel']) $resultados[$linha['pratica_item_id']]['pratica_indicador_estrategico_favoravel']+=1;
			if (isset($this->campos['pratica_indicador_estrategico_superior']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_superior']) $resultados[$linha['pratica_item_id']]['pratica_indicador_estrategico_superior']+=1;
			if (isset($this->campos['pratica_indicador_estrategico_lider']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_lider']) $resultados[$linha['pratica_item_id']]['pratica_indicador_estrategico_lider']+=1;
			if (isset($this->campos['pratica_indicador_estrategico_excelencia']) && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_estrategico'] && $indicadores[$linha['pratica_indicador_id']]['pratica_indicador_excelencia']) $resultados[$linha['pratica_item_id']]['pratica_indicador_estrategico_excelencia']+=1;
			$resultados[$linha['pratica_item_id']]['pratica_indicador_relevante']+=1;
			$resultados[$linha['pratica_item_id']]['qnt']+=1;
	 		}


		//total de relevantes são os encontrados mais os das lacunas
		$sql->adTabela('indicador_lacuna_nos_marcadores');
		$sql->esqUnir('indicador_lacuna', 'indicador_lacuna', 'indicador_lacuna.indicador_lacuna_id=indicador_lacuna_nos_marcadores.indicador_lacuna_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'indicador_lacuna_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('count(DISTINCT indicador_lacuna.indicador_lacuna_id) as qnt, pratica_item_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('indicador_lacuna_cia='.(int)$this->cia_id);
		$sql->adOnde('indicador_lacuna_nos_marcadores.ano='.(int)$this->ano);
		$lacunas=$sql->listaVetorChave('pratica_item_id','qnt');
		$sql->limpar();
   
   	

		if (isset($this->campos['pratica_indicador_complemento'])){
			$sql->adTabela('pratica_indicador_nos_marcadores');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_nos_marcadores.pratica_marcador_id=pratica_marcador.pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_nos_marcadores.pratica_indicador_id');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=1');
			$sql->adOnde('pratica_marcador_texto IS NOT NULL');
			$sql->adCampo('count(pratica_indicador_nos_marcadores.pratica_marcador_id) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$complementos_indicador_total=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
		
			$sql->adTabela('pratica_indicador_complemento');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_indicador_complemento_marcador=pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador.pratica_indicador_id=pratica_indicador_complemento_indicador');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_indicador_complemento_ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=1');
			$sql->adOnde('pratica_marcador_texto IS NOT NULL');
			$sql->adCampo('count(DISTINCT pratica_indicador_complemento_id) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$complementos_indicador_marcados=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
			}

		$ignorar=array(
			'pratica_indicador_complemento',
			'pratica_indicador_estrategico_favoravel',
			'pratica_indicador_estrategico_superior',
			'pratica_indicador_estrategico_atendimento');

		foreach($resultados as $item_id => $linha){			
			
			foreach($campo_resultado as $chave=> $valor) if ($chave!='qnt' && !in_array($chave, $ignorar)) $resultados[$item_id][$chave]=($resultados[$item_id]['qnt']>0 ? $this->arredondamento($resultados[$item_id][$chave]/$resultados[$item_id]['qnt'], $resultados[$item_id][$chave]) : 0);
				
			if (isset($this->campos['pratica_indicador_complemento'])) $resultados[$item_id]['pratica_indicador_complemento']=(isset($complementos_indicador_marcados[$item_id]) && isset($complementos_indicador_total[$item_id]) > 0 ? $this->arredondamento(($complementos_indicador_marcados[$item_id]/$complementos_indicador_total[$item_id]), $complementos_indicador_marcados[$item_id]) : 0);
			
			if (isset($this->campos['pratica_indicador_estrategico_favoravel'])) $resultados[$item_id]['pratica_indicador_estrategico_favoravel']=($resultados[$item_id]['pratica_indicador_estrategico'] > 0 ? $this->arredondamento($resultados[$item_id]['pratica_indicador_estrategico_favoravel']/$resultados[$item_id]['pratica_indicador_estrategico'], $resultados[$item_id][$chave]) : 0);
			if (isset($this->campos['pratica_indicador_estrategico_superior'])) $resultados[$item_id]['pratica_indicador_estrategico_superior']=($resultados[$item_id]['pratica_indicador_estrategico'] > 0 ? $this->arredondamento($resultados[$item_id]['pratica_indicador_estrategico_superior']/$resultados[$item_id]['pratica_indicador_estrategico'], $resultados[$item_id][$chave]) : 0);
			if (isset($this->campos['pratica_indicador_estrategico_atendimento'])) $resultados[$item_id]['pratica_indicador_estrategico_atendimento']=($resultados[$item_id]['pratica_indicador_estrategico'] > 0 ? $this->arredondamento($resultados[$item_id]['pratica_indicador_estrategico_atendimento']/$resultados[$item_id]['pratica_indicador_estrategico'], $resultados[$item_id][$chave]) : 0);

			$resultados[$item_id]['pratica_indicador_relevante']=($resultados[$item_id]['qnt']>0 ? 	$this->arredondamento($resultados[$item_id]['pratica_indicador_relevante']/((isset($lacunas[$item_id]) ? $lacunas[$item_id] : 0)+$resultados[$item_id]['pratica_indicador_relevante']), $resultados[$item_id]['pratica_indicador_relevante']) : 0);
			array_pop($resultados[$item_id]);
			}	

		$pontuacao1=array();
		foreach($resultados as $item_id => $linha) {
			foreach($linha as $campo => $valor) $pontuacao1[$item_id][$campo]=(isset($regras[$campo][$valor]) ? $regras[$campo][$valor] : 0);			
			}
		$this->resultados=$pontuacao1;
		
			
		$sql->adTabela('pratica_nos_verbos');
		$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_verbo_marcador=pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_nos_verbos.pratica');
		$sql->adCampo('count(DISTINCT verbo) AS qnt, pratica_item_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_cia='.(int)$this->cia_id);
		$sql->adOnde('pratica_nos_verbos.ano='.(int)$this->ano);
		$sql->adOnde('pratica_criterio_resultado=0');
		$sql->adGrupo('pratica_item_id');
		$verbos_marcados=$sql->listaVetorChave('pratica_item_id','qnt');
		$sql->limpar();

				

		$sql->adTabela('pratica_verbo');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_verbo_marcador=pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('count(DISTINCT pratica_verbo_id) AS qnt, pratica_item_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_criterio_resultado=0');
		$sql->adGrupo('pratica_item_id');
		$verbos_total=$sql->listaVetorChave('pratica_item_id','qnt');
		$sql->limpar();
		
		
		if ($this->tipo_pauta=='fnq_2015'){
			$sql->adTabela('pratica_nos_marcadores');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador=pratica_marcador.pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_nos_marcadores.pratica');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_nos_marcadores.ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=0');
			$sql->adOnde('pratica_marcador_texto IS NOT NULL');
			$sql->adCampo('count(pratica_nos_marcadores.marcador) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$complementos_total=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
		
			$sql->adTabela('pratica_complemento');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_complemento_marcador=pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_complemento_pratica');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_complemento_ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=0');
			$sql->adOnde('pratica_marcador_texto IS NOT NULL');
			$sql->adCampo('count(DISTINCT pratica_complemento_id) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$complementos_marcados=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
			
			
			
			
			
			
			
			
			
			$sql->adTabela('pratica_nos_marcadores');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador=pratica_marcador.pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_nos_marcadores.pratica');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_nos_marcadores.ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=0');
			$sql->adOnde('pratica_marcador_evidencia IS NOT NULL');
			$sql->adCampo('count(pratica_nos_marcadores.marcador) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$evidencias_total=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
		
			$sql->adTabela('pratica_evidencia');
			$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_evidencia_marcador=pratica_marcador_id');
			$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
			$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
			$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_evidencia_pratica');
			$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
			$sql->adOnde('pratica_cia='.(int)$this->cia_id);
			$sql->adOnde('pratica_evidencia_ano='.(int)$this->ano);
			$sql->adOnde('pratica_criterio_resultado=0');
			$sql->adOnde('pratica_marcador_evidencia IS NOT NULL');
			$sql->adCampo('count(DISTINCT pratica_evidencia_id) AS qnt, pratica_item_id');
			$sql->adGrupo('pratica_item_id');
			$evidencias_marcadas=$sql->listaVetorChave('pratica_item_id','qnt');
			$sql->limpar();
			}

		
		$sql->adTabela('pratica_requisito');
		$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_requisito.pratica_id');
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'praticas.pratica_id=pratica_nos_marcadores.pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador=pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('
			SUM(pratica_proativa) AS pratica_proativa, 
			SUM(pratica_abrange_pertinentes) AS pratica_abrange_pertinentes, 
			SUM(pratica_continuada) AS pratica_continuada, 
			SUM(pratica_refinada_implantacao) AS pratica_refinada_implantacao, 
			SUM(pratica_refinada) AS pratica_refinada, 
			SUM(pratica_arte) AS pratica_arte, 
			SUM(pratica_inovacao) AS pratica_inovacao, 
			SUM(pratica_coerente) AS pratica_coerente, 
			SUM(pratica_interrelacionada) AS pratica_interrelacionada, 
			SUM(pratica_cooperacao) AS pratica_cooperacao, 
			SUM(pratica_cooperacao_partes) AS pratica_cooperacao_partes, 
			SUM(pratica_controlada) AS pratica_controlada, 
			SUM(pratica_melhoria_aprendizado) AS pratica_melhoria_aprendizado, 
			SUM(pratica_gerencial) AS pratica_gerencial, 
			SUM(pratica_agil) AS pratica_agil, 
			SUM(pratica_incoerente) AS pratica_incoerente, 
			pratica_item_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_cia='.(int)$this->cia_id);
		$sql->adOnde('pratica_requisito.ano='.(int)$this->ano);
		$sql->adOnde('pratica_criterio_resultado=0');
		$sql->adGrupo('pratica_item_id');
		$requisitos_marcados=$sql->ListaChave('pratica_item_id');
		$sql->limpar();
		
		
		
		$sql->adTabela('pratica_requisito');
		$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_requisito.pratica_id');
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'praticas.pratica_id=pratica_nos_marcadores.pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_nos_marcadores.marcador=pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('
			COUNT(pratica_proativa) AS pratica_proativa, 
			COUNT(pratica_abrange_pertinentes) AS pratica_abrange_pertinentes, 
			COUNT(pratica_continuada) AS pratica_continuada, 
			COUNT(pratica_refinada_implantacao) AS pratica_refinada_implantacao, 
			COUNT(pratica_refinada) AS pratica_refinada, 
			COUNT(pratica_arte) AS pratica_arte, 
			COUNT(pratica_inovacao) AS pratica_inovacao, 
			COUNT(pratica_coerente) AS pratica_coerente, 
			COUNT(pratica_interrelacionada) AS pratica_interrelacionada, 
			COUNT(pratica_cooperacao) AS pratica_cooperacao, 
			COUNT(pratica_cooperacao_partes) AS pratica_cooperacao_partes, 
			COUNT(pratica_controlada) AS pratica_controlada, 
			COUNT(pratica_melhoria_aprendizado) AS pratica_melhoria_aprendizado, 
			COUNT(pratica_gerencial) AS pratica_gerencial, 
			COUNT(pratica_agil) AS pratica_agil, 
			COUNT(pratica_incoerente) AS pratica_incoerente, 
			pratica_item_id');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_cia='.(int)$this->cia_id);
		$sql->adOnde('pratica_requisito.ano='.(int)$this->ano);
		$sql->adOnde('pratica_criterio_resultado=0');
		$sql->adGrupo('pratica_item_id');
		$requisitos_total=$sql->ListaChave('pratica_item_id');
		$sql->limpar();
		
		
		//zerar vetor
		$sql->adTabela('pratica_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_item_id');
		$sql->adOnde('pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOnde('pratica_criterio_resultado=0');
		$itens=$sql->carregarColuna();
		$sql->limpar();
		
		$campo_pratica=array();
 		foreach ($this->campos as $chave => $vetor) {
			if ($vetor['resultado']==0) $campo_pratica[$chave]=0;
			}
 
   	foreach($itens as $item_id) $praticas[$item_id]=$campo_pratica;

		foreach($verbos_total as $item_id => $total) {
			foreach($campo_pratica as $chave=> $valor) if ($chave!='pratica_adequada' && $chave!='pratica_complemento'  && $chave!='pratica_evidencia') $praticas[$item_id][$chave]=(isset($requisitos_total[$item_id][$chave]) && $requisitos_total[$item_id][$chave] > 0 ? $this->arredondamento(($requisitos_marcados[$item_id][$chave]/$requisitos_total[$item_id][$chave]), $requisitos_marcados[$item_id][$chave]) : 0);
			
			if (isset($campo_pratica['pratica_complemento'])) $praticas[$item_id]['pratica_complemento']=(isset($complementos_marcados[$item_id]) && isset($complementos_total[$item_id]) > 0 ? $this->arredondamento(($complementos_marcados[$item_id]/$complementos_total[$item_id]), $complementos_marcados[$item_id]) : 0);
			if (isset($campo_pratica['pratica_evidencia'])) $praticas[$item_id]['pratica_evidencia']=(isset($evidencias_marcadas[$item_id]) && isset($evidencias_total[$item_id]) > 0 ? $this->arredondamento(($evidencias_marcadas[$item_id]/$evidencias_total[$item_id]), $evidencias_marcadas[$item_id]) : 0);
			$praticas[$item_id]['pratica_adequada']=(isset($verbos_marcados[$item_id]) && $total > 0 ? $this->arredondamento(($verbos_marcados[$item_id]/$total), $verbos_marcados[$item_id]) : 0);
			
			//PQGF tem OU em vez de E para pratica_refinada_implantacao
			
			if (isset($campo_pratica['pratica_refinada_implantacao']) && isset($campo_pratica['pratica_refinada']) && ($praticas[$item_id]['pratica_refinada'] > $praticas[$item_id]['pratica_refinada_implantacao'])) $praticas[$item_id]['pratica_refinada_implantacao']=$praticas[$item_id]['pratica_refinada'];
			}
   
		
		

		$pontuacao2=array();
		foreach($praticas as $item_id => $linha) {
			foreach($linha as $campo => $valor) $pontuacao2[$item_id][$campo]=(isset($regras[$campo][$valor]) ? $regras[$campo][$valor] : 0);			
			}
   	
   	$this->pontuacao=$pontuacao2;
  
   	$this->praticas=$pontuacao2;
   	
   	//após ter a pontuação precisa colocar o vetor por subitem
   	
   	//$subitem
   	$sql->adTabela('pratica_regra');
		$sql->adCampo('pratica_regra_campo, subitem');
		$sql->adOnde('pratica_modelo_id='.(int)$this->pratica_modelo_id);
		$sql->adOrdem('subitem');
		$sql->adGrupo('pratica_regra_campo');
		$subitem=$sql->listaVetorChave('pratica_regra_campo','subitem');
		$sql->limpar();


		$pontuacao_subitem=array();
   	foreach($pontuacao2 as $item_id => $linha){
   		foreach($subitem as $chave => $valor) {
   			if (isset($linha[$chave]) && ((isset($pontuacao_subitem[$item_id][$valor]) && ($linha[$chave] < $pontuacao_subitem[$item_id][$valor])) || !isset($pontuacao_subitem[$item_id][$valor]))) $pontuacao_subitem[$item_id][$valor]=$linha[$chave];
   			}
   		} 
   	

   		 
   	foreach($pontuacao1 as $item_id => $linha){
   		foreach($subitem as $chave => $valor) {
   			if (isset($linha[$chave]) && ((isset($pontuacao_subitem[$item_id][$valor]) && ($linha[$chave] < $pontuacao_subitem[$item_id][$valor])) || !isset($pontuacao_subitem[$item_id][$valor]))) $pontuacao_subitem[$item_id][$valor]=$linha[$chave];
   			}
   		}    
   	
   	
   	ksort($pontuacao_subitem);   
   	$this->pontuacao_subitem=$pontuacao_subitem;  
   	
   	foreach ($pontuacao_subitem as $item_id => $linha){
   		sort($linha);
   		$qnt_maior=0;
   		$menor=$linha[0];
   		foreach ($linha as $valor) if ($valor > $menor) $qnt_maior++;
   		if ($qnt_maior >1 && $menor!=100) $menor+=10;
   		$porcentagem_item[$item_id]=$menor;   		
  		}
  		
  		
   $this->porcentagem_item=$porcentagem_item; 
		
		$sql->adTabela('pratica_criterio');
		$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero, pratica_criterio_resultado');
		$sql->adOnde('pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOrdem('pratica_criterio_numero');
		$this->criterios=$sql->ListaChaveSimples('pratica_criterio_id');
		$sql->limpar();
		
		$sql->adTabela('pratica_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_item_criterio=pratica_criterio_id');
		$sql->adCampo('pratica_item.*');
		$sql->adOnde('pratica_criterio_modelo='.(int)$this->pratica_modelo_id);
		$sql->adOrdem('pratica_criterio_numero');

		$this->itens=$sql->ListaChaveSimples('pratica_item_id');
		$sql->limpar();
		
		//pontuação por critério
		$pontuacao_final=0;
		$pontuacao_criterio=array();
		$pontuacao_item=array();
		foreach($this->itens as $item) {
			$pontos=$item['pratica_item_pontos']*$this->porcentagem_item[$item['pratica_item_id']]/100;
			
			$pontuacao_item[$item['pratica_item_id']]=$pontos;
			$pontuacao_criterio[$item['pratica_item_criterio']]=(isset($pontuacao_criterio[$item['pratica_item_criterio']]) ? $pontuacao_criterio[$item['pratica_item_criterio']]+$pontos : $pontos); 
			$pontuacao_final+=$pontuacao_criterio[$item['pratica_item_criterio']];
			}
		$this->pontuacao_criterio=$pontuacao_criterio;
		$this->pontuacao_item=$pontuacao_item;
		$this->pontuacao_final=$pontuacao_final;
		
		$porcentagem_criterio=array();
		foreach($this->pontuacao_criterio as $criterio_id => $valor) {
			$porcentagem_criterio[$criterio_id]=($this->criterios[$criterio_id]['pratica_criterio_pontos'] > 0 ? ($valor/$this->criterios[$criterio_id]['pratica_criterio_pontos'])*100 : 0);
			}
			
		$this->porcentagem_criterio=$porcentagem_criterio;	
   	}
	
	
	public function arredondamento($campo, $quantidade){
		
		if ($this->tipo_pauta=='fnq_2015'){
			if ($campo>0.9999) $saida=100;//todos
			elseif (($campo>=0.9) && ($campo < 1)) $saida=90; //quase todos
			elseif (($campo>=0.75) && ($campo < 1)) $saida=70; //quase todos
			elseif (($campo >= 0.5) && ($campo < 0.75)) $saida=50; //maioria
			elseif (($campo>=0.25) && ($campo < 0.5)) $saida=30;  //muitos
			elseif ($quantidade>1) $saida=2; //alguma(s)
			elseif ($quantidade==1) $saida=1; //ao menos um - alguma(s)
			else $saida=0;
			}
		else {
			if ($campo>0.999) $saida=100;//todos
			elseif (($campo>=0.75) && ($campo < 1)) $saida=75; //quase todos
			elseif (($campo >= 0.5) && ($campo < 0.75)) $saida=50; //maioria
			elseif (($campo>=0.25) && ($campo < 0.5)) $saida=25;  //muitos
			elseif (($campo>0) && ($campo < 0.25)) $saida=1; //ao menos um - alguma(s)
			else $saida=0;
			}
		return $saida;
		}
	

	}

?>