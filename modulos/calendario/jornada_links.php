<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

function getExpedienteLinks($inicioPeriodo, $fimPeriodo, &$links, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0, $jornada_id=0) {
	global $a, $Aplic, $config;
 
	$sql = new BDConsulta;
	$campos=array();
	if($cia_id) $campos[]='jornada_pertence_cia='.(int)$cia_id;
	if($usuario_id) $campos[]='jornada_pertence_usuario='.(int)$usuario_id;
	if($projeto_id) $campos[]='jornada_pertence_projeto='.(int)$projeto_id;
	if($tarefa_id) $campos[]='jornada_pertence_tarefa='.(int)$tarefa_id;
	if($recurso_id) $campos[]='jornada_pertence_recurso='.(int)$recurso_id;
		
	if ($jornada_id){
		$sql->adTabela('jornada', 'jornada');
		$sql->adCampo('jornada.*, null AS jornada_pertence_cia, null AS jornada_pertence_usuario, null AS jornada_pertence_projeto, null AS jornada_pertence_tarefa, null AS jornada_pertence_recurso');
		$sql->adOnde('jornada_id='.(int)$jornada_id);
		$calendarios = $sql->Lista();
		$sql->limpar();
		}	
	else{
		$sql->adTabela('jornada_pertence');
		$sql->esqUnir('jornada', 'jornada', 'jornada_pertence_jornada=jornada_id');
		$sql->adCampo('jornada.*, jornada_pertence_cia, jornada_pertence_usuario, jornada_pertence_projeto, jornada_pertence_tarefa, jornada_pertence_recurso');
		$sql->adOnde(implode(' OR ', $campos));
		$sql->adOrdem('jornada_pertence_usuario DESC, jornada_pertence_recurso DESC, jornada_pertence_tarefa DESC, jornada_pertence_projeto DESC, jornada_pertence_cia DESC'); 
		$sql->setLimite(1);
		$calendarios = $sql->Lista();
		$sql->limpar();
		}
	if(!count($calendarios)){
		$sql->adTabela('jornada');
		$sql->adCampo('jornada.*, null AS jornada_pertence_cia, null AS jornada_pertence_usuario, null AS jornada_pertence_projeto, null AS jornada_pertence_tarefa, null AS jornada_pertence_recurso');
		$sql->adOnde('jornada_id='.(int)$config['calendario_padrao']);
		$sql->adOrdem('jornada_pertence_usuario DESC, jornada_pertence_recurso DESC, jornada_pertence_tarefa DESC, jornada_pertence_projeto DESC, jornada_pertence_cia DESC'); 
		$calendarios = $sql->Lista();
		$sql->limpar();
		}

	$calendario=array_shift($calendarios);



	$sql->adTabela('jornada_excessao');
	$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual');
	$sql->adOnde('jornada_excessao_jornada='.(int)$calendario['jornada_id']);
	$sql->adOnde('jornada_excessao_anual!=1');
	$sql->adOrdem('jornada_excessao_data');
	$excessoes = $sql->ListaChaveSimples('jornada_excessao_data');
	$sql->limpar();
	
	$excessoes2=array();
	if ($usuario_id || $recurso_id || $projeto_id || $tarefa_id || $cia_id){
		$sql->adTabela('jornada_excessao');
		$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual');
		if ($usuario_id) $sql->adOnde('jornada_excessao_usuario='.(int)$usuario_id);
		else if ($recurso_id) $sql->adOnde('jornada_excessao_recurso='.(int)$recurso_id);
		else if ($tarefa_id) $sql->adOnde('jornada_excessao_tarefa='.(int)$tarefa_id);
		else if ($projeto_id) $sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
		else if ($cia_id) $sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
		$sql->adOnde('jornada_excessao_anual!=1');
		$sql->adOrdem('jornada_excessao_data');
		$excessoes2 = $sql->ListaChaveSimples('jornada_excessao_data');
		$sql->limpar();
		//if (count($excessoes2)) $excessoes=array_merge($excessoes, $excessoes2);
		}
	

	$sql->adTabela('jornada_excessao');
	$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual, formatar_data(jornada_excessao_data, "%m-%d") AS indice');
	$sql->adOnde('jornada_excessao_jornada='.(int)$calendario['jornada_id']);
	$sql->adOnde('jornada_excessao_anual=1');
	$sql->adOrdem('indice');
	$excessoes_anuais = $sql->ListaChaveSimples('indice');
	$sql->limpar();
	
	$excessoes_anuais2=array();
	if ($usuario_id || $recurso_id || $projeto_id || $tarefa_id || $cia_id){
		$sql->adTabela('jornada_excessao');
		$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual, formatar_data(jornada_excessao_data, "%m-%d") AS indice');
		if ($usuario_id) $sql->adOnde('jornada_excessao_usuario='.(int)$usuario_id);
		else if ($recurso_id) $sql->adOnde('jornada_excessao_recurso='.(int)$recurso_id);
		else if ($tarefa_id) $sql->adOnde('jornada_excessao_tarefa='.(int)$tarefa_id);
		else if ($projeto_id) $sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
		else if ($cia_id) $sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
		$sql->adOnde('jornada_excessao_anual=1');
		$sql->adOrdem('indice');
		$excessoes_anuais2 = $sql->ListaChaveSimples('indice');
		$sql->limpar();
		//if (count($excessoes_anuais2)) $excessoes_anuais=array_merge($excessoes_anuais, $excessoes_anuais2);
		}

	$data_inicio = $inicioPeriodo->format('%Y-%m-%d');
	$data_final = $fimPeriodo->format('%Y-%m-%d');

	while (strtotime($data_inicio) <= strtotime($data_final)) {
		$indice=$data_inicio;
		$indice2=substr($data_inicio, 5, 5);
		$horas=0;
		//checar horas
		
		if (isset($excessoes2[$indice])) {
			$horas=($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
			$inicio=$excessoes2[$indice]['jornada_excessao_inicio'];
			$fim=$excessoes2[$indice]['jornada_excessao_fim'];
			}
		else if (isset($excessoes_anuais2[$indice2])) {
			$horas=($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
			$inicio=$excessoes_anuais2[$indice2]['jornada_excessao_inicio'];
			$fim=$excessoes_anuais2[$indice2]['jornada_excessao_fim'];
			}	
		elseif (isset($excessoes[$indice])) {
			$horas=($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
			$inicio=$excessoes[$indice]['jornada_excessao_inicio'];
			$fim=$excessoes[$indice]['jornada_excessao_fim'];
			}
		else if (isset($excessoes_anuais[$indice2])) {
			$horas=($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
			$inicio=$excessoes_anuais[$indice2]['jornada_excessao_inicio'];
			$fim=$excessoes_anuais[$indice2]['jornada_excessao_fim'];
			}	
		else {
			$dia_semana=date("w", strtotime($indice))+1;
			$horas=$calendario['jornada_'.$dia_semana.'_duracao'];
			$inicio=$calendario['jornada_'.$dia_semana.'_inicio'];
			$fim=$calendario['jornada_'.$dia_semana.'_fim'];
			}	
		
		if ($horas > 0){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Total de Horas</b></td><td>'.(int)$horas.'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Início</b></td><td>'.substr($inicio,0,5).' hs</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Término</b></td><td>'.substr($fim,0,5).' hs</td></tr>';
			$dentro .= '</table>';
			$temp =  array('expediente' => true, 'texto_mini' => '<tr><td>'.$dentro.'</td></tr>', 'horas' => $horas);
			}
		else {
			$temp =  array('expediente' => true, 'texto_mini' => '<tr><td><b>Sem Expediente</b></td></tr>', 'horas' => 0);	
			}	
		$links[str_replace('-', '', $data_inicio)][] = $temp;

		$data_inicio = date("Y-m-d", strtotime("+1 day", strtotime($data_inicio)));
		}	
	}


?>