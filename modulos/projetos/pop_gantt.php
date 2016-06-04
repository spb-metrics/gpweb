<?php
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$src = '?m=tarefas&a=gantt&sem_cabecalho=1&width=1600&projeto_id='.$projeto_id;
echo '<div style="font:16px/26px Georgia, Garamond, Serif;"><script>document.write(\'<img src=\"'.$src.'\">\')</script></div>';



?>