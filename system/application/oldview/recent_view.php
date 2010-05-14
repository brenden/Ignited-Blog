<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="description" content="Personal blog discussing various projects and ideas. Authored by Joshua Kehn at http://joshuakehn.com" /> 
	<meta name="keywords" content="joshua kehn, blog, programming, php, mysql, java, brighton, michigan, new york, long island, javascript, python, ruby, perl, thoughts" /> 
	
	<title><?php echo($title); ?></title>
	
	<script src="<?php echo(base_url()); ?>jquery-1_4_2.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo(base_url()); ?>script.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href='<?php echo(base_url()); ?>blueprint/screen.css' type="text/css" media="screen, projection" /> 
	<link rel="stylesheet" href='<?php echo(base_url()); ?>blueprint/print.css' type="text/css" media="print" /> 
	<!--[if lt IE 8]>
	    <link rel="stylesheet" href='<?php echo(base_url()); ?>blueprint/ie.css' type="text/css" media="screen, projection">
	  <![endif]-->
	<link rel="stylesheet" href='<?php echo(base_url()); ?>style.css' type="text/css" media="screen, projection" /> 
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
			<?php
				if(isset($messages))
				{
					echo('<div class="span-14 prepend-5 append-5 last">');
					if(isset($messages['errors']))
					{
						foreach($messages['errors'] as $e)
						{
							echo('<div class="error">'.$e.'</div>');
						}
					}
					if(isset($messages['notice']))
					{
						foreach($messages['notice'] as $n)
						{
							echo('<div class="notice">'.$n.'</div>');
						}
					}
					if(isset($messages['success']))
					{
						foreach($messages['success'] as $s)
						{
							echo('<div class="success">'.$s.'</div>');
						}
					}
					echo('</div>');
				}
			?>
			<div class="span-18" id="posts">
				<?php
					if(isset($posts))
					{
						foreach($posts as $p)
						{
							if($p->deleted != "false")
							{
								continue;
							}
							echo('<div class="post">');
							echo('<h3><a href="' . base_url() . 'index.php/blog/view/' . $p->id . '/' . str_replace(' ', '-',$p->title) . '/" title="' . $p->title . '">' . $p->title . '</a></h3>');
							
							echo('<div class="date">');
							echo('Posted on ' . $p->date.'<br />');
							
							$tags = explode(' ', $p->tags);
							
							echo('Tagged: ');
							foreach($tags as $tag)
							{
								echo('<a class="tag" href="' . base_url() . 'index.php/blog/tagged/' . $tag . '/" title="View posts tagged ' . $tag . '">' . $tag . '</a>');
							}
							
							echo('</div>');
							
							// echo('<p class="postbody">');
							// $body = explode(' ', $p->body);
							// 
							// for($i = 0; $i < count($body); $i++)
							// {
							// 	echo($body[$i].' ');
							// 	
							// 	if($i > 200)
							// 	{
							// 		echo("...");
							// 		$i = count($body);
							// 	}
							// }
							// echo('</p>');
							echo('<p class="more">');
							echo('<a class="" href="' . base_url() . 'index.php/blog/view/' . $p->id . '/' . str_replace(' ', '-', $p->title) . '/" title="Read Entry">Read</a>');
							if($this->session->userdata('loggedin'))
							{
								echo('&nbsp;&nbsp;<a href="' . base_url() . 'index.php/blog/edit/' . $p->id . '/" title="Edit Post">Edit</a>');
							}
							echo('</p>');
							echo('</div>');
						}
					}
					else
					{
						if($this->session->userdata('loggedin'))
						{
							echo('<div class="notice">No posts found. Please <a href="' . base_url() . 'index.php/blog/insert/" title="New Post">create an entry</a>.</div>');
						}
						else
						{
							echo('<div class="notice">No posts found. Please <a href="' . base_url() . 'index.php/blog/login/" title="Login">login</a> and create an entry.</div>');
						}
					}
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
