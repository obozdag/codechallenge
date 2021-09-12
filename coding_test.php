<?php 

require_once 'coding_db.php';
require_once 'coding_render.php';

$db     = new DB();
$render = new Render($db);
$result = $db->getall();
// $result = $db->where("employee_name", "Reto Fanzen");
// $result = $db->where("event_name", "PHP 7 crash course");
// $result = $db->where("event_date", "2019-09-04 08:00:00");
// echo $render->table($result);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Coding Test</title>
	<link rel="stylesheet" type="text/css" href="css/coding.css">
	<script type="text/javascript">
		function select_field(field, value)
		{
			var form_data = new FormData()
			form_data.append("field", field)
			form_data.append("value", value)
			const table = document.getElementById('table')
			
			fetch("coding_ajax.php", {
				method: "POST",
				body: form_data
			})
			.then(response=>response.text())
			.then(function(res){
				table.innerHTML = res
			})
		}
	</script>
</head>
<body>
	<div class="container">
		<div class="row">
			<form method="post" action="coding_ajax.php">
				<?=  $render->select("employee_name"); ?>
				<?=  $render->select("event_name"); ?>
				<?=  $render->select("event_date"); ?>
			</form>
		</div>
		<div id="table">
			<?=  $render->table($result); ?>
		</div>
	</div>
</body>
</html>