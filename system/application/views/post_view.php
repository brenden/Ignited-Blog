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
			<div class="span-18" id="post">
				<?php
					
					$this->load->view('message_view');
					
					if(isset($post))
					{
						echo('<h2><a href="' . base_url() . 'index.php/blog/view/' . $post->id . '/' . str_replace(' ', '-',$post->title) . '/" title="' . $post->title . '">' . $post->title . '</a></h2>');
						echo('<div class="date">');
						echo('Posted on ' . $post->date);
						echo('</div>');
						
						echo('<p id="post-body">');
						echo($post->body);
						echo('<div id="post-footer">');
						echo('<div id="tags">');
						
						$tags = explode(' ', $post->tags);
						
						echo('Tagged: ');
						foreach($tags as $tag)
						{
							echo('<a class="tag" href="' . base_url() . 'index.php/blog/tagged/' . $tag . '/" title="View questions tagged ' . $tag . '">' . $tag . '</a>');
						}
						
						echo('</div>');
						
						echo('<a href="' . base_url() .'index.php/blog/comments/' . $post->id . '/" title="View Comment(s)">' . $commentcount . ' comments</a>');
						
						
						
						if($post->commentlock == 'locked')
						{
							echo(' [Locked]');
						}
						
						if($this->session->userdata('loggedin'))
						{
							echo('&nbsp;&nbsp;<a href="' . base_url() . 'index.php/blog/edit/' . $post->id . '/" title="Edit Post">Edit</a>');
						}
						echo('<br /><a href="' . base_url() . 'index.php/blog/" title="Back to home page">Back to home page</a>');
						echo('</div>');
						echo('</p>');
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