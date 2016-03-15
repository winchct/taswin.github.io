<!DOCTYPE html>
<html>
<head>
	<title>Username to UUID</title>
	<?php
	function getUUID($users,$agent) {
		$data = array();
		foreach ($users as $key => $value) {
			array_push($data, $value);
		}
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json',
				'content' => json_encode($data)
				)
			);
		$context  = stream_context_create($opts);
		return json_decode(file_get_contents('https://api.mojang.com/profiles/'.@$_GET['uuidagent'], false, $context),true);
	}
	?>
</head>
<body class="minecraft">
	<?php
	if (@$_GET['uuiduser'] != '' && $_GET['uuidagent'] != '') {
		$users = explode(',', @$_GET['uuiduser']);
		$data = getUUID($users,@$_GET['uuidagent']);;
		foreach ($data as $key => $value) {
			$imgdata = file_get_contents('https://sessionserver.mojang.com/session/minecraft/profile/'.$value['id']);
			$img = json_decode(base64_decode(json_decode($imgdata,true)['properties'][0]['value']),true)['textures']['SKIN']['url'];
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="well">
						<img style="image-rendering: -webkit-optimize-contrast;" src="<?php echo $img; ?>">
						<h1><?php echo $value['name']; ?></h1><br>
						<h5><?php echo $value['id']; ?></h5>
						<pre><?php
							print_r(json_decode($imgdata,true));
						?></pre>
						<pre><?php
							print_r(json_decode(base64_decode(json_decode($imgdata,true)['properties'][0]['value']),true));
						?></pre>
						<pre><?php
							print_r($value);
						?></pre>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
	<form>
		<div class="row">
			<div class="col-md-4">
				<input name="uuiduser" value="<?php echo @$_GET['uuiduser']; ?>" type="text" class="form-control" placeholder="Username (comma seperate: Dinnerbone,Notch)">
			</div>
			<div class="col-md-4">
				<select class="form-control" name="uuidagent"><option value="minecraft">Minecraft</option><option value="scrolls">Scrolls</option></select>
			</div>
			<div class="col-md-4">
				<input type="submit" value="Fetch" class="btn btn-default btn-block">
			</div>
		</div>
	</form>
	<?php
	?>
</body>
</html>
