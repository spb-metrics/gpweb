<?php

if (!$Aplic->checarModulo('sistema', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Configura��o', '../../../modulos/social/imagens/brasil.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=vermods', 'voltar','','Voltar','Voltar � tela de administra��o de m�dulos.');
$botoesTitulo->mostrar();
echo 'Este m�dulo n�o tem necessidade de configura��o adicional';

?>