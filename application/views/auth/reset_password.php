<html>
<head>
	<title>Reset Password</title>
</head>
<body>

	<p><a href="<?php echo site_url('auth/login'); ?>">Login</a></p>

	<?php 
	if($success){
		echo '<p>You have successfully reset your password.</p>';
	} else {
	    echo form_open(); 
	    echo form_label('Password', 'password') .'<br />';
	    echo form_password(array('name' => 'password', 'value' => set_value('password'))) .'<br />';
	    echo form_error('password');
	    echo form_label('Confirm Password', 'password_conf') .'<br />';
	    echo form_password(array('name' => 'password_conf', 'value' => set_value('password_conf'))) .'<br />';
	    echo form_error('password_conf');
	    echo form_submit(array('type' => 'submit', 'value' => 'Save New Password'));
	    echo form_close();
    }
    ?>

</body>
</html>
