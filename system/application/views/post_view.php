<?php $this->load->view('head_view', array('title' => $title)); ?>
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
						echo('<h2><a href="' . base_url() . 'index.php/blog/view/' . $post->id . '/' . make_url_friendly($post->title) . '/" title="' . $post->title . '">' . $post->title . '</a></h2>');
						echo('<div class="date">');
						echo('Posted on ' . $post->date);
						echo('</div>');
					
						echo('<div id="post-body">');	
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
						echo('</div>');
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
