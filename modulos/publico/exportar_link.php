<?php
$sql = new BDConsulta;

$id = getParam($_REQUEST, 'id', null);
$tipo = getParam($_REQUEST, 'tipo', 'painel_composicao');

$cia_id = getParam($_REQUEST, 'cia_id', null);


$posicao_inicial=$Aplic->getPosicao();
$posicao_inicial=explode('&', $posicao_inicial);

$posicao=array();
foreach($posicao_inicial as $valor){
	$aux=explode('=', $valor);
	if (isset($aux[0]) && isset($aux[1])) $posicao[$aux[0]]=$aux[1];
	}



if ($tipo=='painel_composicao') $endereco='m=praticas&a=painel_composicao_pro_exibir&jquery=1&dialogo=1&painel_composicao_id='.$id;
else if ($tipo=='slideshow') $endereco='m=praticas&a=painel_slideshow_pro_exibir&jquery=1&dialogo=1&painel_slideshow_id='.$id;
else if ($tipo=='odometro') $endereco='m=praticas&a=odometro_pro_exibir&jquery=1&dialogo=1&painel_odometro_id='.$id;
else if ($tipo=='painel') $endereco='m=praticas&a=painel_pro_exibir&jquery=1&dialogo=1&painel_id='.$id;
else if ($tipo=='indicador'){
	$ano=getParam($_REQUEST, 'ano', 0);
	$faixas=getParam($_REQUEST, 'faixas', 0);
	$mostrar_valor=getParam($_REQUEST, 'mostrar_valor', null);
	$mostrar_pontuacao=getParam($_REQUEST, 'mostrar_pontuacao', 0);
	$data = new CData(getParam($_REQUEST, 'data_final', null));
	$data2 = new CData(getParam($_REQUEST, 'data_final2', null));
	$nr_pontos=getParam($_REQUEST, 'nr_pontos', null);
	$mostrar_titulo=getParam($_REQUEST, 'mostrar_titulo',null);
	$max_min=getParam($_REQUEST, 'max_min', null);
	$agrupar=getParam($_REQUEST, 'agrupar', null);
	$tipografico=getParam($_REQUEST, 'tipografico', null);
	$segundo_indicador=getParam($_REQUEST, 'segundo_indicador', 0);
	$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);
	$endereco='m=praticas&a=indicador_exibir&dialogo=1&tipo=indicador&&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$mostrar_valor.'&mostrar_pontuacao='.$mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&data_final2='.$data2->format("%Y-%m-%d").'&nr_pontos='.$nr_pontos.'&mostrar_titulo='.$mostrar_titulo.'&max_min='.$max_min.'&agrupar='.$agrupar.'&tipografico='.$tipografico.'&segundo_indicador='.$segundo_indicador.'&pratica_indicador_id='.$pratica_indicador_id;
	}
else if ($tipo=='projeto_gantt' || $tipo=='portfolio_gantt') $endereco='m=tarefas&a=ver_gantt_pro&dialogo=1&projeto_id='.$id.'&auto_height=1';

else if ($tipo=='projeto_ver') $endereco='m=projetos&a=ver&dialogo=1&projeto_id='.$id;
else if ($tipo=='projeto_dashboard') $endereco='m=projetos&a=deshboard_geral_pro&jquery=1&dialogo=1&projeto_id='.$id;
elseif ($tipo=='arvore_portfolio') $endereco=$Aplic->getPosicao().'&dialogo=1'.(!isset($posicao['cia_id']) && $cia_id? '&cia_id='.$cia_id : '');
elseif ($tipo=='arvore_gestao') $endereco=$Aplic->getPosicao().'&dialogo=1'.(!isset($posicao['cia_id']) && $cia_id? '&cia_id='.$cia_id : '') ;
else $endereco='';


if ($endereco){
	//checar se já não existe

	$sql->adTabela('usuario_externo');
	$sql->adCampo('usuario_externo_chave');
	$sql->adOnde('usuario_externo_endereco=\''.$endereco.'\'');
	$sql->adOnde('usuario_externo_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('usuario_externo_geral=1');
	$chave=$sql->Resultado();
	$sql->limpar();
	if (!$chave){
		$chave=uuid();
		$sql->adTabela('usuario_externo');
		$sql->adInserir('usuario_externo_usuario', (int)$Aplic->usuario_id);
		$sql->adInserir('usuario_externo_geral', 1);
		$sql->adInserir('usuario_externo_chave', $chave);
		$sql->adInserir('usuario_externo_endereco', $endereco);
		$sql->exec();
		$sql->limpar();
		}
	$link=$config['dominio_site'].'/index.php?login_externo=1&id='.$chave;
	echo '<table width="100%" cellpadding=0 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td align=center>'.$link.'</td></tr></tsble>';
	}
?>