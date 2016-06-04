<?php
global $bd;

if(!file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')) exit();

$sql = new BDConsulta;
$sql->adTabela('menu');
$sql->adCampo('menu_id');
$sql->adOnde('menu_usuario_id != 0 AND menu_usuario_id IS NOT NULL');
$menus = $sql->lista();
$sql->limpar();

foreach($menus as $menu){
  $sql->adTabela('menu_item');
  $sql->adCampo('menu_item_id, menu_item_superior_id');
  $sql->adOnde('menu_item_menu_id = '.(int)$menu['menu_id']);
  $items = $sql->Lista();
  $sql->limpar();
  foreach($items as $item){
    $sql->adTabela('menu_item');
    $sql->adAtualizar('menu_item_chave', 'fav_'.$item['menu_item_id']);
    $sql->adAtualizar('menu_item_chave_pai', ($item['menu_item_superior_id'] ? 'fav_'.$item['menu_item_superior_id'] : ''));
    $sql->adOnde('menu_item_id = '.$item['menu_item_id']);
    $sql->exec();
    $sql->limpar();
    }
  }  

?>