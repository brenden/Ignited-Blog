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
			<div class="span-18" id="edit">
				<?php
					echo form_open('blog/editpost/' . $post->id);
					echo form_fieldset('Edit');
					echo('<p class="span-12">');
				
					echo form_label('Post Title', 'title', array('class' => 'label'));
					echo '<br />';
					
					if(isset($post->title))
					{
						echo form_input('title', $post->title);
					}
					else
					{
						echo form_input('title');
					}
					echo '<br />';
					echo form_label('Body', 'body', array('class' => 'label'));
					echo '<br />';

					if(isset($post->markdown))
					{
						echo form_textarea('body', $post->markdown);
					}
					else
					{
						echo form_textarea('body');
					}
					
					echo '<br />';
					
					echo form_label('Tags', 'tags', array('closs' => 'label'));
					echo '<br />';
					
					if(isset($post->tags))
					{
						echo form_input('tags', $post->tags);
					}
					else
					{
						echo form_input('tags');
					}
					echo '<br />';
					echo form_submit('submit', 'Edit');
					echo('<a class="cancel" href="' . base_url() . 'index.php/blog/" title="Cancel Editing post">Cancel</a>');
					echo('<a class="delete" href="' . base_url() . 'index.php/blog/delete/' . $post->id . '/" title="Delete Post"> Delete</a>');
					echo('</p>');
					
					echo('<p class="span-4 last">');
					
					echo form_label('Post Date', 'date', array('class' => 'label'));
					echo form_hidden('date', $post->date);
					echo '<br />';
					echo($post->date);
					
					echo('</p>');				
					echo form_fieldset_close();
					echo form_close();
					
				?>
			</div>
			<div class="span-4 last" id="sidebar">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			</div>
		</div>
		
		<div class="span-24 last" id="footer">
			<?php $this->load->view('footer'); ?>
		</div>
	</div>
</body>
</html>