<?php 

require_once 'coding_db.php';
require_once 'coding_render.php';

$db     = new DB();
$render = new Render($db);
// $result =  $db->getall();
$result = $db->where($_POST['field'], $_POST['value']);
// $result = $db->where("event_name", "PHP 7 crash course");
// $result = $db->where("event_date", "2019-09-04 08:00:00");
echo $render->table($result);

