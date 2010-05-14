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
			<div class="span-18" id="comments">
				<?php
					if(isset($messages))
					{
						echo('<div class="span-14 append-5 last">');
						if(isset($messages['errors']))
						{
							foreach($messages['errors'] as $e)
							{
								echo('<div class="error">'.$e.'</div>');
							}
						}
						if(isset($messages['notices']))
						{
							foreach($messages['notices'] as $n)
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
					if(isset($post) and isset($comments))
					{
						echo('<h2>Comments for <a href="' . base_url() . 'index.php/blog/view/' . $post->id . '/' . str_replace(' ', '-', $post->title). '/" title="' . $post->title . '">' . $post->title . '</a>');
						if(!$post->commentlock)
						{
							echo(" [Locked]");
						}
						echo('</h2>');
						echo('<h4><a href="' . base_url() . 'index.php/blog/view/' . $post->id . '/' . str_replace(' ', '-',$post->title) . '/" title="' . $post->title . '">' . 'Back to post' . '</a></h4>');
						
						foreach($comments as $c)
						{
							if($c->flagged == "false" or ($c->flagged == "true" and $c->flagcount < 3))
							{
								echo('<div class="comment span-12">');
							
								echo('<h5>');
								if(isset($c->website))
								{
									echo('<a href="' . $c->website .'">' . $c->author . '</a> wrote: ');
								}
								else
								{
									echo($c->author . ' wrote: ');
								}
								echo('</h5>');
								echo('<div class="date">' . $c->date . '</div>');
								echo('<p class="commentbody">');
								echo($c->body);
								echo('</p>');
							
								echo('<div class="commenttoolbar">');
								echo('<a href="' . base_url() . 'index.php/blog/flag/comment/' . $c->id . '/" title="Flag as spam, offensive, or requires attention">Flag</a>');
								echo('</div>');
							
								echo('</div>');
							}
						}
												
						echo('<div id="postfooter" class="span-12"><a href="' . base_url() . 'index.php/blog/comments/' . $post->id . '/add/" title="Add comment">Add Comment</a> <br /><a href="' . base_url() . 'index.php/blog/" title="Back to home page">Back to home page</a></div>');
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