<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
	<link rel="shortcut icon" href="<?php echo base_url ('asset/images/favicon.png');?> "/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/css/login.css');?>"/>


	<title>Login</title>
</head>

<body>
	<div id="login_box">
		<H1> Login Administrator</H1>
		<?php $attributes = array('name'=> 'login_form', 'id'=> 'login_form');

		echo form_open ('login', $attributes);
		?>

		<!-- pesan start -->
		<?php if (!empty ($pesan)) :?>
			<p id="message">
				<?php echo $pesan; ?>
			</p>
		<?php endif; ?>

		<!-- pesan end -->
		<p> 
			<label for ="username">Username: </label>
			<input type="text" name="username" size="25" class="col-sm-2 control-label" value="<?php echo set_value ('username'); ?>">
		</p>

		<?php echo form_error ('username',
		"<p class='field_error'>", '</p>');?>
		<p>
			<label for="password">Password:</label>
			<input type="password" name="password" size="25" class="col-sm-2 control-label" value="<?php echo set_value ('password');?>">
		</p>
		<?php echo form_error('password', '
		<p class="field_error">', '</p>');?>
		<input type="submit" name="submit" id="submit" class="ok" value="OK"/> 
		
	</p>
</form> 
</div>

</body>
</html>