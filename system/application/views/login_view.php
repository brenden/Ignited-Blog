<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="description" content="Personal blog discussing various projects and ideas. Authored by Joshua Kehn at http://joshuakehn.com" /> 
	<meta name="keywords" content="joshua kehn, blog, programming, php, mysql, java, brighton, michigan, new york, long island, javascript, python, ruby, perl, thoughts" /> 
	
	<title><?php echo($title); ?></title>
	
	<script src="<?php echo(base_url()); ?>js/jquery-1_4_2.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo(base_url()); ?>js/script.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href='<?php echo(base_url()); ?>css/blueprint/screen.css' type="text/css" media="screen, projection" /> 
	<link rel="stylesheet" href='<?php echo(base_url()); ?>css/blueprint/print.css' type="text/css" media="print" /> 
	<!--[if lt IE 8]>
	    <link rel="stylesheet" href='<?php echo(base_url()); ?>css/blueprint/ie.css' type="text/css" media="screen, projection">
	  <![endif]-->
	<link rel="stylesheet" href='<?php echo(base_url()); ?>css/style.css' type="text/css" media="screen, projection" /> 
</head>
<body>
	<div class="container">
		<div id="masthead" class="span-24 last">
			<?php $this->load->view('masthead_view'); ?>
		</div>
		
		<div class="span-24" id="content">
			<div class="span-14 prepend-5 append-5 last">
				<?php
					if($this->session->userdata('loggedin'))
					{
						?>
						<div class="notice">
							You are already logged in. Would you like to <a href="<?php echo(base_url()); ?>index.php/blog/logout/" title="Logout">logout</a>?
						</div>
						<?php
					}
					else
					{
						$this->load->view('message_view');
					
						echo form_open('blog/login');
						?>
						<p>
						<?php
						echo form_fieldset('Login');

						echo form_hidden('post', 'true');
					
						echo form_label('Username', 'username', array('class' => 'label'));
						echo form_input('username', '');

						?>
						<br />
						<?php

						echo form_label('Password', 'password', array('class' => 'label'));
						echo form_password('password', '');

						?>
						<br />
						<?php

						echo form_submit('submit', 'Login');
					
						echo form_fieldset_close();
						?>
						</p>
						<?php
						echo form_close();
					}
				?>	
			</div>
		</div>
		
		<div class="span-24 last" id="footer">
			<?php $this->load->view('footer'); ?>
		</div>
	</div>
</body>
</html>