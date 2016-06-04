<?php 

function ajusta_dia_util($ponto_inicio, $direcao = 1, $cia_id = 0, $usuario_id=0, $projeto_id = 0, $recurso_id=0, $tarefa_id = 0){
	$calendario=array();
	$excessoes=array();
	$excessoes2=array();
	$excessoes_anuais=array();
	$excessoes_anuais2=array();
	
	vetor_jornada($calendario, $excessoes, $excessoes2, $excessoes_anuais, $excessoes_anuais2, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);
	
	$horas=0;
	$indice=substr($ponto_inicio, 0, 10);	
	
	$direcao = ($direcao >=0 ? "+1 day" : "-1 day");
	while(true) {
		$indice2=substr($indice, 5, 5);
		if (isset($excessoes2[$indice]))	$horas=(float)($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
		else if (isset($excessoes_anuais2[$indice2])) $horas=(float)($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
		elseif (isset($excessoes[$indice]))	$horas=(float)($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
		else if (isset($excessoes_anuais[$indice2])) $horas=(float)($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
		else {
			$dia_semana=date("w", strtotime($indice))+1;
			$horas=(float)$calendario['jornada_'.$dia_semana.'_duracao'];
			}
		if($horas) break;
		
		$indice = date("Y-m-d", strtotime($direcao, strtotime($indice)));
		}
		
	return $indice.substr($ponto_inicio, 10);
	}

function horas_periodo($ponto_inicio, $ponto_final, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0, $tempo_corrido_ponto=false){
	global $config, $Aplic;
	if ($tempo_corrido_ponto){
		$sql = new BDConsulta;
		$sql->adTabela('versao');
		$sql->adCampo('tempo_em_segundos(diferenca_tempo(\''.$ponto_final.'\',\''.$ponto_inicio.'\'))/3600 AS horas, diferenca_data(\''.$ponto_final.'\',\''.$ponto_inicio.'\') AS dias');
		$data=$sql->linha();
		$sql->limpar();
		$horas=($data['dias']*24)+$data['horas'];
		return $horas;
		}

	$data_inicial=new CData($ponto_inicio);
	$data_final=new CData($ponto_final);
	$horas=0;
	$d="%Y-%m-%d";
	$t="%H:%M:%S";
	$hora_inicial=strtotime($data_inicial->format($t));
	$hora_final=strtotime($data_final->format($t));
	$q = new BDConsulta;

	$calendario=array();
	$excessoes=array();
	$excessoes_anuais=array();
	$excessoes2=array();
	$excessoes_anuais2=array();
	vetor_jornada($calendario, $excessoes, $excessoes2, $excessoes_anuais, $excessoes_anuais2, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);
	
	$data_inicio=substr($ponto_inicio, 0, 10);
	$data_final=substr($ponto_final, 0, 10);
	$horas=0;
	
	$indice=$data_inicio;
	$indice2=substr($data_inicio, 5, 5);
	
	while (strtotime($data_inicio) <= strtotime($data_final)) {
		$indice=$data_inicio;
		$indice2=substr($data_inicio, 5, 5);
		if (isset($excessoes2[$indice])) $horas+=($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
		else if (isset($excessoes_anuais2[$indice2])) $horas+=($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
		else if (isset($excessoes[$indice])) $horas+=($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
		else if (isset($excessoes_anuais[$indice2])) $horas+=($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
		else {
			$dia_semana=date("w", strtotime($indice))+1;
			$horas+=$calendario['jornada_'.$dia_semana.'_duracao'];
			}	
		$data_inicio = date("Y-m-d", strtotime("+1 day", strtotime($data_inicio)));
		}


	//Verificar primeiro dia
	$data_inicio=substr($ponto_inicio, 0, 10);
	$data_final=substr($ponto_final, 0, 10);
	$indice=$data_inicio;
	$indice2=substr($data_inicio, 5, 5);
	//checar horas
	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
		}	



	if ($horas_trabalho > 0){
		if ($hora_inicial <= $inicio) $subtrair=0;
		elseif (($hora_inicial >= $almoco_inicio) && ($hora_inicial <= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=($almoco_inicio-$inicio)/3600;
		elseif (($hora_inicial >= $fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($fim-$almoco_fim))/3600;
		elseif (($hora_inicial >= $inicio) && ( $hora_inicial <= $almoco_inicio)) $subtrair=($hora_inicial-$inicio)/3600; 
		elseif (($hora_inicial >= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($hora_inicial-$almoco_fim))/3600; 
		elseif ($almoco_inicio <= $inicio) $subtrair=($hora_inicial-$inicio)/3600;
		else $subtrair=0;
		}
	else $subtrair=0;
		
	$horas=$horas-$subtrair;
	//verificar o ultimo dia	
	$data_inicio=substr($ponto_inicio, 0, 10);
	$data_final=substr($ponto_final, 0, 10);
	$indice=$data_final;
	$indice2=substr($data_final, 5, 5);
	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
			
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
		}	
	if ($horas_trabalho > 0){
		if ($almoco_inicio == $almoco_fim) $subtrair=0;
		elseif (($hora_final <= $inicio) && ($fim > $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=(($fim-$almoco_fim)+($almoco_inicio-$inicio))/3600; 
		elseif (($hora_final >= $almoco_inicio) && ($hora_final <= $almoco_fim) && ($fim > $almoco_fim)) $subtrair=($fim-$almoco_fim)/3600;
		elseif (($hora_final >=$inicio) && ( $hora_final <= $almoco_inicio) && ($fim > $almoco_fim)) $subtrair=(($fim-$almoco_fim)+($almoco_inicio-$hora_final))/3600;
		elseif (($hora_final >= $almoco_fim) && ($hora_final <= $fim) && ($almoco_fim > $inicio)) $subtrair=($fim-$hora_final)/3600;
		else $subtrair=0;
		}
	else $subtrair=0;

	
	
	$horas=$horas-$subtrair;
				
	if ($horas < 0) $horas=0;	
	return $horas;
	}
  
function dias_periodo($ponto_inicio, $ponto_final, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0, $tempo_corrido_ponto=false){
  global $config, $Aplic;

  $data_inicial=new CData($ponto_inicio);
  $data_final=new CData($ponto_final);
  $horas=0;
  $dias_uteis = 0;
  $d="%Y-%m-%d";
  $t="%H:%M:%S";
  $hora_inicial=strtotime($data_inicial->format($t));
  $hora_final=strtotime($data_final->format($t));
  $q = new BDConsulta;
  
  $calendario=array();
  $excessoes=array();
  $excessoes2=array();
  $excessoes_anuais=array();
  $excessoes_anuais2=array();
  vetor_jornada($calendario, $excessoes, $excessoes2, $excessoes_anuais, $excessoes_anuais2, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);

  $data_inicio=substr($ponto_inicio, 0, 10);
  $data_final=substr($ponto_final, 0, 10);
  $horas=0;
  
  $indice=$data_inicio;
  $indice2=substr($data_inicio, 5, 5);
  
  while (strtotime($data_inicio) <= strtotime($data_final)) {
    $indice=$data_inicio;
    $indice2=substr($data_inicio, 5, 5);
    $work = 0;
    if (isset($excessoes2[$indice]))  $work=($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
    else if (isset($excessoes_anuais2[$indice2])) $work=($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
    else if (isset($excessoes[$indice]))  $work=($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
    else if (isset($excessoes_anuais[$indice2])) $work=($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
    else {
      $dia_semana=date("w", strtotime($indice))+1;
      $work=$calendario['jornada_'.$dia_semana.'_duracao'];
      }
    $horas += $work;
    if($work > 0.00) ++$dias_uteis;
    $data_inicio = date("Y-m-d", strtotime("+1 day", strtotime($data_inicio)));
    }
    
    //Verificar primeiro dia
  $data_inicio=substr($ponto_inicio, 0, 10);
  $data_final=substr($ponto_final, 0, 10);
  $indice=$data_inicio;
  $indice2=substr($data_inicio, 5, 5);
  //checar horas
  if (isset($excessoes2[$indice])) {
    $inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
    }
  else if (isset($excessoes_anuais2[$indice2])) {
    $inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
    }  
  else if (isset($excessoes[$indice])) {
    $inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
    }
  else if (isset($excessoes_anuais[$indice2])) {
    $inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
    }  
  else {
    $dia_semana=date("w", strtotime($indice))+1;
    $inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
    $fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
    $almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
    $almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
    $horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
    }  

  if ($horas_trabalho > 0){
    if ($hora_inicial <= $inicio) $subtrair=0;
    elseif (($hora_inicial >= $almoco_inicio) && ($hora_inicial <= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=($almoco_inicio-$inicio)/3600;
    elseif (($hora_inicial >= $fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($fim-$almoco_fim))/3600;
    elseif (($hora_inicial >= $inicio) && ( $hora_inicial <= $almoco_inicio)) $subtrair=($hora_inicial-$inicio)/3600; 
    elseif (($hora_inicial >= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($hora_inicial-$almoco_fim))/3600; 
    elseif ($almoco_inicio <= $inicio) $subtrair=($hora_inicial-$inicio)/3600;
    else $subtrair=0;
    }
  else $subtrair=0;
  
  if($subtrair){
    $dias_uteis -= ($subtrair/$horas_trabalho);
  }
    
  //verificar o ultimo dia  
  $data_inicio=substr($ponto_inicio, 0, 10);
  $data_final=substr($ponto_final, 0, 10);
  $indice=$data_final;
  $indice2=substr($data_final, 5, 5);
  if (isset($excessoes2[$indice])) {
    $inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
    }
  else if (isset($excessoes_anuais2[$indice2])) {
    $inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
    }  
  else if (isset($excessoes[$indice])) {
    $inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
    }
  else if (isset($excessoes_anuais[$indice2])) {
    $inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
    $fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
    $almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
    $almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
    $horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
    }  
  else {
    $dia_semana=date("w", strtotime($indice))+1;
      
    $inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
    $fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
    $almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
    $almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
    $horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
    }  
  if ($horas_trabalho > 0){
  	if ($almoco_inicio == $almoco_fim) $subtrair=0;
    elseif (($hora_final <= $inicio) && ($fim > $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=(($fim-$almoco_fim)+($almoco_inicio-$inicio))/3600; 
    elseif (($hora_final >= $almoco_inicio) && ( $hora_final <= $almoco_fim) && ($fim > $almoco_fim)) $subtrair=($fim-$almoco_fim)/3600;
    elseif (($hora_final >=$inicio) && ( $hora_final <= $almoco_inicio) && ($fim > $almoco_fim)) $subtrair=(($fim-$almoco_fim)+($almoco_inicio-$hora_final))/3600;
    elseif (($hora_final >= $almoco_fim) && ($hora_final <= $fim) && ($almoco_fim > $inicio)) $subtrair=($fim-$hora_final)/3600;
    elseif (($almoco_fim < $inicio) || ($almoco_fim > $fim)) $subtrair=($fim-$hora_final)/3600;
    else $subtrair=0;
    }
  else $subtrair=0;

  if($subtrair){
    $dias_uteis -= ($subtrair/$horas_trabalho);
    }
  
  
  return ceil($dias_uteis);
  }	

function calculo_data_final_periodo($ponto_inicio, $horas, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0, $tempo_corrido_ponto=null){
	global $config, $Aplic;
	
	if ($tempo_corrido_ponto){
		$data = strtotime($inicio) + ($horas * 3600);
		return date('Y-m-d H:i:s', $data);
		}
	
	
	$data_inicial=new CData($ponto_inicio);
	$d="%Y-%m-%d";
	$t="%H:%M:%S";
	$horario_final='';
	$hora_inicial=$data_inicial->format($t);
	
	$calendario=array();
	$excessoes=array();
	$excessoes2=array();
	$excessoes_anuais=array();
	$excessoes_anuais2=array();
	vetor_jornada($calendario, $excessoes, $excessoes2, $excessoes_anuais, $excessoes_anuais2, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);
	
	$sql = new BDConsulta;

	//subtrair as horas nao trab do 1o dia
	$data_inicio=substr($ponto_inicio, 0, 10);
	$indice=$data_inicio;
	$indice2=substr($data_inicio, 5, 5);
	//checar horas
	

	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
		}	

	if ($horas_trabalho > 0){
		$hora_inicial=strtotime($hora_inicial);

		if (($hora_inicial > $almoco_inicio) && ($hora_inicial <= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=($almoco_inicio-$inicio)/3600;
		elseif (($hora_inicial >= $fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($fim-$almoco_fim))/3600;
		elseif (($hora_inicial >= $inicio) && ( $hora_inicial <= $almoco_inicio)) $subtrair=($hora_inicial-$inicio)/3600; 
		elseif (($hora_inicial >= $almoco_fim) && ($almoco_inicio > $inicio)) $subtrair=(($almoco_inicio-$inicio)+($hora_inicial-$almoco_fim))/3600; 
		elseif ($almoco_inicio <= $inicio) $subtrair=($hora_inicial-$inicio)/3600;
		else $subtrair=0;
		}
	else $subtrair=0;

	$horas_achadas=$horas_trabalho-$subtrair;

	$terminado=false;
	if ($horas_achadas >= $horas) $terminado=true;

	$data=$data_inicial;
	
	$indice=$data->format($d);
	$indice2=substr($indice, 5, 5);
	while (!$terminado){
		$data=$data->getNextDay();
		$indice=$data->format($d);
		$indice2=substr($indice, 5, 5);
		if (isset($excessoes2[$indice])) {
			$horas_achadas+=($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
			}
		else if (isset($excessoes_anuais2[$indice2])) {
			$horas_achadas+=($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
			}	
		else if (isset($excessoes[$indice])) {
			$horas_achadas+=($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
			}
		else if (isset($excessoes_anuais[$indice2])) {
			$horas_achadas+=($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
			}	
		else {
			$dia_semana=date("w", strtotime($indice))+1;
			$horas_achadas+=$calendario['jornada_'.$dia_semana.'_duracao'];
			}	
		if ($horas_achadas >= $horas) $terminado=true;
		}
	$excesso_horas=$horas_achadas-$horas;


	//retirar as horas em excesso da data final
	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$hora_fim=$excessoes2[$indice]['jornada_excessao_fim'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$hora_fim=$excessoes_anuais2[$indice2]['jornada_excessao_fim'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$hora_fim=$excessoes[$indice]['jornada_excessao_fim'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$hora_fim=$excessoes_anuais[$indice2]['jornada_excessao_fim'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$hora_fim=$calendario['jornada_'.$dia_semana.'_fim'];
		}	
	
	if ($excesso_horas){
		$horas_ate_almoco=0;
		if (($fim > $almoco_fim) && ($almoco_fim > $inicio)) $horas_ate_almoco=($fim-$almoco_fim)/3600;
		if ($horas_ate_almoco < 0) $horas_ate_almoco=0;
		$intervalo_almoco=0;
		if (($fim > $almoco_fim) && ($almoco_fim > $inicio) && ($almoco_fim > $almoco_inicio)) $intervalo_almoco=($almoco_fim-$almoco_inicio)/3600;
		if ($intervalo_almoco < 0) $intervalo_almoco=0;
		
		if ($horas_ate_almoco && ($horas_ate_almoco <= $excesso_horas)) $excesso_horas+=$intervalo_almoco;	
		
		$data_final=strtotime($data->format($d).' '.$hora_fim)-($excesso_horas*3600);
		$data_final=date("Y-m-d H:i:s", $data_final);
		}
	else $data_final=$data->format($d).' '.$hora_fim;

	return $data_final;
	}

function calculo_data_inicial_periodo($ponto_final, $horas, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0){
	global $config, $Aplic;

	$data_final=new CData($ponto_final);
	$d="%Y-%m-%d";
	$t="%H:%M:%S";
	$horario_final='';
	$sql = new BDConsulta;
	$hora_final=$data_final->format($t);
	
	
	$calendario=array();
	$excessoes=array();
	$excessoes_anuais=array();
	$excessoes2=array();
	$excessoes_anuais2=array();
	vetor_jornada($calendario, $excessoes, $excessoes2, $excessoes_anuais, $excessoes_anuais2, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);
	
	
	//verificar o ultimo dia	
	$final=substr($ponto_final, 0, 10);
	$indice=$final;
	$indice2=substr($final, 5, 5);
	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes2[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais2[$indice2]['jornada_excessao_duracao'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes[$indice]['jornada_excessao_duracao'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$horas_trabalho=$excessoes_anuais[$indice2]['jornada_excessao_duracao'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$horas_trabalho=$calendario['jornada_'.$dia_semana.'_duracao'];
		}	
	if ($horas_trabalho > 0){
		$hora_final=strtotime($hora_final);
		if ($almoco_inicio == $almoco_fim) $subtrair=0;
		else if (($hora_final >= $almoco_inicio) && ( $hora_final <= $almoco_fim) && ($fim > $almoco_fim)) $subtrair=($almoco_inicio-$inicio)/3600; 
		else if (($hora_final >= $inicio) && ( $hora_final <= $almoco_inicio)) $subtrair=($hora_final-$inicio)/3600;
		else if (($hora_final >= $almoco_fim) && ($hora_final < $fim) && ($fim > $hora_final)) $subtrair=(($hora_final-$almoco_fim)+($almoco_inicio-$inicio))/3600; 
		else if ($hora_final >= $fim) $subtrair=(($fim-$almoco_fim)+($almoco_inicio-$inicio))/3600; 
		else $subtrair=0;
		}
	else $subtrair=0;
	
	$horas_achadas=$subtrair;
	
	$terminado=false;
	
	if ($horas_achadas >= $horas) $terminado=true;
	$data=$data_final;
	$indice=$data->format($d);
	$indice2=substr($indice, 5, 5);
	while (!$terminado){
		$data=$data->getPrevDay();
		$indice=$data->format($d);
		$indice2=substr($indice, 5, 5);
		if (isset($excessoes2[$indice])) {
			$horas_achadas+=($excessoes2[$indice]['jornada_excessao_trabalha'] ? $excessoes2[$indice]['jornada_excessao_duracao'] : 0);
			}
		else if (isset($excessoes_anuais2[$indice2])) {
			$horas_achadas+=($excessoes_anuais2[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais2[$indice2]['jornada_excessao_duracao'] : 0);
			}	
		else if (isset($excessoes[$indice])) {
			$horas_achadas+=($excessoes[$indice]['jornada_excessao_trabalha'] ? $excessoes[$indice]['jornada_excessao_duracao'] : 0);
			}
		else if (isset($excessoes_anuais[$indice2])) {
			$horas_achadas+=($excessoes_anuais[$indice2]['jornada_excessao_trabalha'] ? $excessoes_anuais[$indice2]['jornada_excessao_duracao'] : 0);
			}	
		else {
			$dia_semana=date("w", strtotime($indice))+1;
			$horas_achadas+=$calendario['jornada_'.$dia_semana.'_duracao'];
			}	
		if ($horas_achadas >= $horas) $terminado=true;
		}
	$excesso_horas=$horas_achadas-$horas;
	
	
	
	
	//retirar as horas em excesso da data final
	if (isset($excessoes2[$indice])) {
		$inicio=strtotime($excessoes2[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes2[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes2[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes2[$indice]['jornada_excessao_almoco_fim']);
		$hora_inicio=$excessoes2[$indice]['jornada_excessao_inicio'];
		}
	else if (isset($excessoes_anuais2[$indice2])) {
		$inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais2[$indice2]['jornada_excessao_almoco_fim']);
		$hora_inicio=$excessoes_anuais2[$indice2]['jornada_excessao_inicio'];
		}	
	else if (isset($excessoes[$indice])) {
		$inicio=strtotime($excessoes[$indice]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes[$indice]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes[$indice]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes[$indice]['jornada_excessao_almoco_fim']);
		$hora_inicio=$excessoes[$indice]['jornada_excessao_inicio'];
		}
	else if (isset($excessoes_anuais[$indice2])) {
		$inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_inicio']);
		$fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_fim']);
		$almoco_inicio=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_inicio']);
		$almoco_fim=strtotime($excessoes_anuais[$indice2]['jornada_excessao_almoco_fim']);
		$hora_inicio=$excessoes_anuais[$indice2]['jornada_excessao_inicio'];
		}	
	else {
		$dia_semana=date("w", strtotime($indice))+1;
		$inicio=strtotime($calendario['jornada_'.$dia_semana.'_inicio']);
		$fim=strtotime($calendario['jornada_'.$dia_semana.'_fim']);
		$almoco_inicio=strtotime($calendario['jornada_'.$dia_semana.'_almoco_inicio']);
		$almoco_fim=strtotime($calendario['jornada_'.$dia_semana.'_almoco_fim']);
		$hora_inicio=$calendario['jornada_'.$dia_semana.'_inicio'];
		}	
	
	if ($excesso_horas){
		$horas_ate_almoco=0;
		if (($fim > $almoco_fim) && ($almoco_fim > $inicio)) $horas_ate_almoco=($fim-$almoco_fim)/3600;
		if ($horas_ate_almoco < 0) $horas_ate_almoco=0;
		$intervalo_almoco=0;
		if (($fim > $almoco_fim) && ($almoco_fim > $inicio) && ($almoco_fim > $almoco_inicio)) $intervalo_almoco=($almoco_fim-$almoco_inicio)/3600;
		if ($intervalo_almoco < 0) $intervalo_almoco=0;
		if ($horas_ate_almoco && ($horas_ate_almoco <= $excesso_horas)) $excesso_horas+=$intervalo_almoco;	
		$data_inicial=strtotime($data->format($d).' '.$hora_inicio)+($excesso_horas*3600);
		$data_inicial=date("Y-m-d H:i:s", $data_inicial);
		}
	else $data_inicial=$data->format($d).' '.$hora_inicio;
	return $data_inicial;
	}

function vetor_jornada(&$calendario, &$excessoes, &$excessoes2, &$excessoes_anuais, &$excessoes_anuais2, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0){
	global $config;
	$sql = new BDConsulta;
	$campos=array();
	if($cia_id) $campos[]='jornada_pertence_cia='.(int)$cia_id;
	if($usuario_id) $campos[]='jornada_pertence_usuario='.(int)$usuario_id;
	if($projeto_id) $campos[]='jornada_pertence_projeto='.(int)$projeto_id;
	if($tarefa_id) $campos[]='jornada_pertence_tarefa='.(int)$tarefa_id;
	if($recurso_id) $campos[]='jornada_pertence_recurso='.(int)$recurso_id;
		
	$sql->adTabela('jornada_pertence');
	$sql->esqUnir('jornada', 'jornada', 'jornada_pertence_jornada=jornada_id');
	$sql->adCampo('jornada.*, jornada_pertence_cia, jornada_pertence_usuario, jornada_pertence_projeto, jornada_pertence_tarefa, jornada_pertence_recurso');
	if (count($campos)) $sql->adOnde(implode(' OR ', $campos));
	$sql->adOrdem('jornada_pertence_usuario DESC, jornada_pertence_recurso DESC, jornada_pertence_tarefa DESC, jornada_pertence_projeto DESC, jornada_pertence_cia DESC'); 
	$sql->setLimite(1);
	$calendarios = $sql->Lista();
	$sql->limpar();

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
		
		//se nao achar nada tentar um nível acima
		if (!count($excessoes2) && $tarefa_id && $projeto_id){
			$sql->adTabela('jornada_excessao');
			$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual');
			$sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
			$sql->adOnde('jornada_excessao_anual!=1');
			$sql->adOrdem('jornada_excessao_data');
			$excessoes2 = $sql->ListaChaveSimples('jornada_excessao_data');
			$sql->limpar();
			}
		
		//se nao achar nada tentar um nível acima
		if (!count($excessoes2) && ($usuario_id || $recurso_id || $projeto_id) && $cia_id){
			$sql->adTabela('jornada_excessao');
			$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual');
			$sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
			$sql->adOnde('jornada_excessao_anual!=1');
			$sql->adOrdem('jornada_excessao_data');
			$excessoes2 = $sql->ListaChaveSimples('jornada_excessao_data');
			$sql->limpar();
			}
		
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
		
		//se nao achar nada tentar um nível acima
		if (!count($excessoes_anuais2) && $tarefa_id && $projeto_id){
			$sql->adTabela('jornada_excessao');
			$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual, formatar_data(jornada_excessao_data, "%m-%d") AS indice');
			$sql->adOnde('jornada_excessao_projeto='.(int)$projeto_id);
			$sql->adOnde('jornada_excessao_anual=1');
			$sql->adOrdem('indice');
			$excessoes_anuais2 = $sql->ListaChaveSimples('indice');
			$sql->limpar();
			}
		
		//se nao achar nada tentar um nível acima
		if (!count($excessoes_anuais2) && ($usuario_id || $recurso_id || $projeto_id) && $cia_id){
			$sql->adTabela('jornada_excessao');
			$sql->adCampo('jornada_excessao_duracao, jornada_excessao_inicio, jornada_excessao_almoco_inicio, jornada_excessao_almoco_fim, jornada_excessao_fim, jornada_excessao_data, jornada_excessao_trabalha, jornada_excessao_anual, formatar_data(jornada_excessao_data, "%m-%d") AS indice');
			$sql->adOnde('jornada_excessao_cia='.(int)$cia_id);
			$sql->adOnde('jornada_excessao_anual=1');
			$sql->adOrdem('indice');
			$excessoes_anuais2 = $sql->ListaChaveSimples('indice');
			$sql->limpar();
			}
		
		//if (count($excessoes_anuais2)) $excessoes_anuais=array_merge($excessoes_anuais, $excessoes_anuais2);
		}
	}

function atualizar_percentagem($projeto_id){
	global $Aplic, $config;
	$sql = new BDConsulta;

	$sql->adTabela('projetos');
	$sql->esqUnir('tarefas', 't1', 'projetos.projeto_id = t1.tarefa_projeto');
	$sql->adCampo('((SUM(t1.tarefa_duracao * (t1.tarefa_percentagem/100))/SUM(t1.tarefa_duracao))*100) AS percentagem');
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$sql->adOnde('tarefa_dinamica=0 OR tarefa_dinamica IS NULL');
	$sql->adOnde('tarefa_duracao > 0');
	$percentagem=$sql->Resultado();
	$sql->Limpar();
	
	//verifica como estava a porcentagem antes no projeto
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_percentagem');
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$percentagem_antiga=$sql->Resultado();
	$sql->Limpar();
	
	//forçar a atualização
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_percentagem', $percentagem);
	$sql->adOnde('projeto_id ='.(int)$projeto_id);
	$sql->exec();
	$sql->Limpar();

	if ($percentagem!=$percentagem_antiga){
		
		if ($Aplic->profissional){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			$obj=new CProjeto();
			$obj->projeto_id=(int)$projeto_id;
			$obj->disparo_observador('fisico');
			}
		}
	}

function verifica_dependencias($tarefa_id){
	global $m, $a, $config;
	$sql = new BDConsulta;
	$sql->adTabela('tarefa_dependencias');
	$sql->esqUnir('tarefas', 'tarefas','tarefas.tarefa_id=dependencias_req_tarefa_id');
	$sql->adCampo('dependencias_req_tarefa_id, tipo_dependencia, latencia, tipo_latencia, tarefa_inicio, tarefa_fim, tarefa_cia, tarefa_projeto');
	$sql->adOnde('dependencias_tarefa_id ='.(int)$tarefa_id);
	$dependencias=$sql->lista();
	$sql->Limpar();
	$maior_inicial='';
	$maior_final='';
	$mudou_data=0;
	//$existe_TI=0;
	
	foreach($dependencias as $dependencia){
		$data=($dependencia['tipo_dependencia']=='TI' || $dependencia['tipo_dependencia']=='TT' ? $dependencia['tarefa_fim'] : $dependencia['tarefa_inicio']);
		if ($dependencia['tipo_latencia']=='d') $latencia=$config['horas_trab_diario']*$dependencia['latencia'];
		elseif ($dependencia['tipo_latencia']=='s') $latencia=$config['horas_trab_diario']*5*$dependencia['latencia'];
		elseif ($dependencia['tipo_latencia']=='m') $latencia=$config['horas_trab_diario']*22*$dependencia['latencia'];
		else $latencia=$dependencia['latencia'];
		if ($latencia) $data=calculo_data_final_periodo($data, $latencia, $dependencia['tarefa_cia'], '', $dependencia['tarefa_projeto']);
		if (($dependencia['tipo_dependencia']=='TI' || $dependencia['tipo_dependencia']=='II') && ($data > $maior_inicial)) $maior_inicial=$data;
		if (($dependencia['tipo_dependencia']=='TT') && ($data > $maior_final)) $maior_final=$data;
		if ($dependencia['tipo_dependencia']=='IT'&& ($data > $maior_final)) $maior_final=$data;
		}

	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_cia, tarefa_dinamica, tarefa_projeto');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
	$tarefa=$sql->linha();
	$sql->Limpar();
	
	if (($maior_inicial > $tarefa['tarefa_inicio']) && ($maior_final <= $tarefa['tarefa_fim'])){
		//somente empura para frente a partir do início
		$data_fim=calculo_data_final_periodo($maior_inicial, $tarefa['tarefa_duracao'], $tarefa['tarefa_cia'],null,$tarefa['tarefa_projeto'],null, $tarefa_id);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_inicio', $maior_inicial);
		$sql->adAtualizar('tarefa_fim', $data_fim);
		$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
		$sql->exec();
		$sql->Limpar();
		$mudou_data++;
		calcular_superior($tarefa_id);
		}
	elseif (($maior_inicial <= $tarefa['tarefa_inicio']) && ($maior_final > $tarefa['tarefa_fim'])){
		//a partir do novo fim empurra para trás
		$data_inicio=calculo_data_inicial_periodo($maior_final, $tarefa['tarefa_duracao'], $tarefa['tarefa_cia'],null,$tarefa['tarefa_projeto'],null, $tarefa_id);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_inicio', $data_inicio);
		$sql->adAtualizar('tarefa_fim', $maior_final);
		$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
		$sql->exec();
		$sql->Limpar();
		$mudou_data++;
		calcular_superior($tarefa_id);
		}
	elseif (($maior_inicial > $tarefa['tarefa_inicio']) && ($maior_final > $tarefa['tarefa_fim'])){
		//muda inicio e fim e recalcula o tempo
		$horas=horas_periodo($maior_inicial, $maior_final, $tarefa['tarefa_cia'],'',$tarefa['tarefa_projeto'],null, $tarefa_id);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_inicio', $maior_inicial);
		$sql->adAtualizar('tarefa_fim', $maior_final);
		$sql->adAtualizar('tarefa_duracao', $horas);
		$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
		$sql->exec();
		$sql->Limpar();
		$mudou_data++;
		calcular_superior($tarefa_id);
		}
	//if ($existe_TI) dependencia_it($tarefa_id);

	$sql->adTabela('tarefa_dependencias');
	$sql->adCampo('dependencias_tarefa_id');
	$sql->adOnde('dependencias_req_tarefa_id ='.(int)$tarefa_id);
	$dependentes=$sql->carregarColuna();
	$sql->Limpar();
	foreach($dependentes as $dependente) verifica_dependencias($dependente);
	
	if ($mudou_data && $m=='projetos' && $a!='ver'){
		$objResposta = new xajaxResponse();
		$objResposta->assign("datas_recalculadas","value", 1);
		return $objResposta;
		}
	}

function dependencia_it($tarefa_id){
	global $config;
	//Ao contrário das outras dependências a tarefa fica parada e as outras são empurradas para trás
	$sql = new BDConsulta;
	$sql->adTabela('tarefa_dependencias');
	$sql->esqUnir('tarefas', 'tarefas','tarefas.tarefa_id=dependencias_req_tarefa_id');
	$sql->adCampo('dependencias_req_tarefa_id, tipo_dependencia, latencia, tipo_latencia, tarefa_inicio, tarefa_fim, tarefa_cia, tarefa_projeto, tarefa_duracao');
	$sql->adOnde('dependencias_tarefa_id ='.(int)$tarefa_id);
	$sql->adOnde('tipo_dependencia = \'IT\'');
	$dependencias=$sql->lista();
	$sql->Limpar();
	
	
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_cia, tarefa_dinamica, tarefa_projeto');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
	$tarefa=$sql->linha();
	$sql->Limpar();
	
	
	foreach($dependencias as $dependencia){

		if ($dependencia['tipo_latencia']=='d') $latencia=$config['horas_trab_diario']*$dependencia['latencia'];
		elseif ($dependencia['tipo_latencia']=='s') $latencia=$config['horas_trab_diario']*5*$dependencia['latencia'];
		elseif ($dependencia['tipo_latencia']=='m') $latencia=$config['horas_trab_diario']*22*$dependencia['latencia'];
		else $latencia=$dependencia['latencia'];
		if ($latencia) $data=calculo_data_inicial_periodo($tarefa['tarefa_inicio'], $latencia, $dependencia['tarefa_cia'], '', $dependencia['tarefa_projeto']);
		else $data=$tarefa['tarefa_inicio'];

		if ($dependencia['tarefa_fim'] > $data){

			$data_inicial=calculo_data_inicial_periodo($data, $dependencia['tarefa_duracao'], $dependencia['tarefa_cia'], '', $dependencia['tarefa_projeto']);
			
			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_inicio', $data_inicial);
			$sql->adAtualizar('tarefa_fim', $data);
			$sql->adAtualizar('tarefa_duracao', $dependencia['tarefa_duracao']);
			$sql->adOnde('tarefa_id ='.(int)$dependencia['dependencias_req_tarefa_id']);
			$sql->exec();
			$sql->Limpar();
			$mudou_data++;
			calcular_superior($dependencia['dependencias_req_tarefa_id']);
			
			$sql->adTabela('tarefa_dependencias');
			$sql->adCampo('dependencias_tarefa_id');
			$sql->adOnde('dependencias_req_tarefa_id ='.(int)$dependencia['dependencias_req_tarefa_id']);
			$sql->adOnde('tipo_dependencia = \'IT\'');
			$dependentes2=$sql->carregarColuna();
			$sql->Limpar();
			foreach($dependentes2 as $dependente2) dependencia_it($dependente2);
			}
	
		}
	return true;
	}

function recalcular_duracao_projeto($projeto_id){
	//achar as tarefas que não são dinâmicas
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('DISTINCT tarefa_superior');
	$sql->adOnde('tarefa_superior != tarefa_id');
	$sql->adOnde('tarefa_dinamica = 0');
	$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	$lista=$sql->carregarColuna();
	$sql->Limpar();
	foreach($lista as $tarefa_id) calcular_superior($tarefa_id);
	}
	
function calcular_superior($tarefa_id=0){
	global $Aplic, $config;
	$sql = new BDConsulta;
	
	//alerta de tarefa TI antecessora completada
	
	if ($Aplic->profissional && $config['aviso_TI']){
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_percentagem');
		$sql->adOnde('tarefa_id='.(int)$tarefa_id);
		$porcentagem_antes=$sql->Resultado();
		$sql->Limpar();
		}
		
	//recalcular inicio e término da tarefa atual, se for superior
	$sql->adTabela('tarefas');
	$sql->adCampo('MIN(tarefa_inicio) AS inicio, MAX(tarefa_fim) AS fim, SUM(tarefa_duracao) AS duracao, SUM(tarefa_duracao) AS total_horas, SUM(tarefa_duracao*(tarefa_percentagem/100)) AS total_feito');
	$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
	$sql->adOnde('tarefa_id != '.(int)$tarefa_id);
	$data=$sql->linha();
	$sql->Limpar();
	
	if ($data['inicio'] && $data['fim']){
		// é uma tarefa superior	
		$porcentagem=($data['total_horas']>0 ?($data['total_feito']/$data['total_horas'])*100 : 0);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_marco', 0);
		$sql->adAtualizar('tarefa_dinamica', 1);
        $sql->adAtualizar('tarefa_inicio_manual', $data['inicio']);
        $sql->adAtualizar('tarefa_fim_manual', $data['fim']);
		$sql->adAtualizar('tarefa_inicio', $data['inicio']);
		$sql->adAtualizar('tarefa_fim', $data['fim']);
        $sql->adAtualizar('tarefa_duracao_manual', $data['duracao']);
		$sql->adAtualizar('tarefa_duracao', $data['duracao']);
		$sql->adAtualizar('tarefa_percentagem', $porcentagem);
		$sql->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$sql->exec();
		$sql->Limpar();
		
		if ($Aplic->profissional && $config['aviso_TI']){
			require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
			require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
			alerta_sucessora_TI($tarefa_id, $porcentagem_antes, $porcentagem);
			}
		}
	else {
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_cia');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$linha=$sql->linha();
		$sql->Limpar();
		$horas=horas_periodo($linha['tarefa_inicio'], $linha['tarefa_fim'], $linha['tarefa_cia']);
		$horas=abs($horas);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_dinamica', 0);
        $sql->adAtualizar('tarefa_duracao_manual', $horas);
		$sql->adAtualizar('tarefa_duracao', $horas);
		$sql->adAtualizar('tarefa_marco', ($horas > 0 ? 0 : 1));
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$sql->exec();
		$sql->Limpar();
		}	
	//recalcular os superiores recursivamente
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_superior');
	$sql->adOnde('tarefa_superior != '.(int)$tarefa_id);
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$superior=$sql->resultado();
	$sql->Limpar();
	if ($superior) calcular_superior($superior);
	}		
?>