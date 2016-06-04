<?php
if (file_exists('../arquivos/.htaccess')) @unlink('../arquivos/.htaccess');
if (file_exists('../arquivos/organizacoes/.htaccess')) @unlink('../arquivos/organizacoes/.htaccess');
if (file_exists('../modulos/pesquisa/objetos/arquivos_conteudo.inc.php')) @unlink('../modulos/pesquisa/objetos/arquivos_conteudo.inc.php');
?>