<?php 

if (file_exists('../pdfimg.php')) @unlink('../pdfimg.php');

remover_arquivos_pro('../');
rrmdir('../lib/ckeditor4');
rrmdir('../lib/codigobarra');
rrmdir('../lib/jqmultiselect');
rrmdir('../lib/extgantt');
rrmdir('../lib/extjs');
rrmdir('../lib/SlickGrid');
rrmdir('../lib/PHPExcel');
rrmdir('../lib/mpdf');
rrmdir('../lib/highcharts3');
rrmdir('../modulos/fpti');
rrmdir('../modulos/sagri');
rrmdir('../modulos/sags');
rrmdir('../modulos/atas');
rrmdir('../modulos/graficos');
rrmdir('../modulos/sistema/menu');
rrmdir('../modulos/problema');
rrmdir('../modulos/operativo');
rrmdir('../modulos/projetos/eb');
rrmdir('../modulos/agrupamento');
rrmdir('../modulos/financeiro');
rrmdir('../modulos/swot');
rrmdir('../modulos/tr');
rrmdir('../modulos/sema');
rrmdir('../modulos/sistema/pauta');
rrmdir('../modulos/sistema/ator');
rrmdir('../modulos/sistema/nd');
echo 'Terminado em '.date('d/m/Y H:i:s');


function remover_arquivos_pro($dir = '../'){
	$files = scandir($dir);
	if(!$files) return;
	
	foreach($files as $file){
		$fullPath = $dir.$file;
		if(is_dir($fullPath) && $file != '.' && $file != '..'){
			remover_arquivos_pro($fullPath.'/');
			}
		else if((stripos($file, '_pro.') !== false || stripos($file, '_pro_') !== false)&& $file !='limpar_pro.php'){
			@unlink($fullPath);
			}
		}
	}



function rrmdir($dir) {
	if (is_dir($dir)){
		$objects = scandir($dir);
		foreach ($objects as $object){
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
		reset($objects);
		rmdir($dir);
		}
	} 
?>