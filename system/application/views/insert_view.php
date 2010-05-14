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
		<div id="navigation" class="span-24 last">
			<?php
				if($this->session->userdata('loggedin'))
				{
					?>
					<ul>
						<li><a href="<?php echo(base_url()); ?>index.php/blog/insert" title="Create New Post">New Post</a></li>
						<li><a href="<?php echo(base_url()); ?>index.php/blog/logout" title="Logout">Logout</a></li>
					</ul>
					<?php
				}
			
			?>
		</div>
		<div class="span-24 last" id="content">
			<?php $this->load->view('message_view'); ?>
			<div class="span-18" id="insert">
				<?php
					echo form_open('blog/insertpost');
					echo form_fieldset('New Entry');
					?>
					<p class="span-12">
					<?php
				
					echo form_label('Post Title', 'title', array('class' => 'label'));
					?>
					<br />
					<?php
					
					if(isset($post_title))
					{
						echo form_input('title', $post_title);
					}
					else
					{
						echo form_input('title');
					}
					?>
					<br />
					<?php
					echo form_label('Body', 'body', array('class' => 'label'));
					?>
					<br />
					<?php

					if(isset($post_body))
					{
						echo form_textarea('body', $post_body);
					}
					else
					{
						echo form_textarea('body');
					}
					
					?>
					<br />
					<?php
					
					echo form_label('Tags', 'tags', array('closs' => 'label'));
					?>
					<br />
					<?php
					
					if(isset($post->tags))
					{
						echo form_input('tags', $post->tags);
					}
					else
					{
						echo form_input('tags');
					}
					?>
					<br />
					<?php

					echo form_submit('submit', 'Post');
					?>
					</p>
					<p class="span-4 last">
					
					<?php
					echo form_label('Post Date', 'date', array('class' => 'label'));
					echo form_hidden('date', date("m-d-Y H:i:s"));
					?>
					<br />
					<?php
					echo(date("m-d-Y H:i:s"));
					
					?>
					</p>
					<?php
					echo form_fieldset_close();
					echo form_close();
					
				?>
			</div>
			<div class="span-4 last" id="sidebar">
				<?php $this->load->view('sidebar_view'); ?>
			</div>
		</div>
		
		<div class="span-24 last" id="footer">
			<?php $this->load->view('footer'); ?>
		</div>
	</div>
</body>
</html>