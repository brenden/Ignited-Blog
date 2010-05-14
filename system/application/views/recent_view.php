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
			<?php
				$this->load->view('message_view');
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
							?>
							<div class="post">
								<h3>
									<a href="<?php echo(base_url()); ?>index.php/blog/view/<?php echo($p->id); ?>/<?php echo(str_replace(' ', '-',$p->title)); ?>/" title="<?php echo($p->title); ?>"><?php echo($p->title); ?></a>
								</h3>
							
								<div class="date">
									Posted on <?php echo($p->date) ?><br />
									Tagged:&nbsp;
							<?php
							$tags = explode(' ', $p->tags);
							
							foreach($tags as $tag)
							{
								?>
								<a class="tag" href="<?php echo(base_url()); ?>index.php/blog/tagged/<?php echo($tag); ?>/" title="View posts tagged <?php echo($tag); ?>"><?php echo($tag); ?></a>
								<?php
							}
							?>
							</div>
							<p class="more">
								<a class="" href="<?php echo(base_url()); ?>index.php/blog/view/<?php echo($p->id); ?>/<?php echo(str_replace(' ', '-', $p->title)); ?>/" title="Read Entry">Read</a>
							<?php
							if($this->session->userdata('loggedin'))
							{
								?>
								&nbsp;&nbsp;<a href="<?php echo(base_url()); ?>index.php/blog/edit/<?php echo($p->id); ?>/" title="Edit Post">Edit</a>
								<?php
							}
							?>
							</p>
							</div>
							<?php
						}
					}
					else
					{
						if($this->session->userdata('loggedin'))
						{
							?>
							<div class="notice">
								No posts found. Please <a href="<?php echo(base_url()); ?>index.php/blog/insert/" title="New Post">create an entry</a>.
							</div>
							<?php
						}
						else
						{
							?>
							<div class="notice">
								No posts found. Please <a href="<?php echo(base_url()); ?>index.php/blog/login/" title="Login">login</a> and create an entry.
							</div>
							<?php
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
