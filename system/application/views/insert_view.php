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