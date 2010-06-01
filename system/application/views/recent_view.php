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
									<a href="<?php echo(base_url()); ?>index.php/blog/view/<?php echo($p->id); ?>/<?php echo make_url_friendly($p->title); ?>/" title="<?php echo($p->title); ?>"><?php echo($p->title); ?></a>
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
								<a class="" href="<?php echo(base_url()); ?>index.php/blog/view/<?php echo($p->id); ?>/<?php echo make_url_friendly($p->title); ?>/" title="Read Entry">Read</a>
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
