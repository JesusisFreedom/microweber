<? if(!is_admin()){error("must be admin");}; ?>
 <?

 $rand = uniqid(); ?>
<? $load_module = url_param('load_module');
 if($load_module == true): ?>
<?
$mod = str_replace( '___',DS, $load_module);
$mod = load_module($mod, $attrs=array('view' => 'admin','backend' => 'true'));
print $mod ;
?>

<? else: ?>

modules admin
<?
 
$mod_params = array();
$mod_params['ui']  = 'any';
//$mod_params['debug']  = 'any';
if(isset($params['reload_modules'])){
	$s = 'skip_cache=1';
	if(isset($params['cleanup_db'])){
		$s.= '&cleanup_db=1';
	}
	
	 $mods = modules_list($s); 
}
if(isset($params['category'])){
	
	 $mod_params['category'] = $params['category'];
}


 $mods = get_modules_from_db($mod_params); 
 if( $mods == false){
	  $mods = modules_list('skip_cache=1'); 
	  $mods = get_modules_from_db($mod_params); 
 }
//
 
 
?>
<ul>
  <? foreach($mods as $k=>$item): ?>
  <li>
   
  <module type="admin/modules/edit_module" data-module-id="<? print $item['id'] ?>" />
    
  </li>
  <? endforeach; ?>
</ul>
<? endif; ?>