<?php
require_once('../classes/Global.php');
require_once('../classes/Store.php');

$sAction	= $_REQUEST['action'];
$sTableName 	= $_REQUEST['table'];

//TEST
$s  = new Store(1, $sTableName);
$s->put("pdf", "\.pdf");
$s->put("jpg", "\.jpg");
print json_encode( $s->getAll());
//GET
//PUT
//DELETE
?>
[  
{name:'jpg', value:'\.jpg', type:'string'},
{name:'gif', value:'\.gif', type:'string'}
]
