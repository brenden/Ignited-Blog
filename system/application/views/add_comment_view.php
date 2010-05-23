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
					echo form_open('blog/insertcomment');
					echo form_fieldset('New Comment');
					?>
					<p class="span-12">
					<?php
					echo form_label('Name (required)', 'title', array('class' => 'label'));
					?>
					<br />
					<?php
					
					if(isset($author))
					{
						echo form_input('author', $author);
					}
					else
					{
						echo form_input('author');
					}
					?>
					<br />
					<?php
					
					echo form_label('Email (required, not displayed)', 'email', array('class' => 'label'));
					?>
					<br />
					<?php

					if(isset($email))
					{
						echo form_input('email', $email);
					}
					else
					{
						echo form_input('email');
					}
					?>
					<br />
					<?php
					
					echo form_label('Website (optional)', 'website', array('class' => 'label'));
					?>
					<br />
					<?php
					if(isset($website))
					{
						echo form_input('website', $website);
					}
					else
					{
						echo form_input('website');
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
						echo form_textarea('body', $body);
					}
					else
					{
						echo form_textarea('body');
					}
					
					?>
					<br />
					<?php

					echo form_submit('submit', 'Submit');

					?>
					</p>
					<?php
					
					?>
					<p class="span-4 last">
					<?php
					
					echo form_label('Date', 'date', array('class' => 'label'));
					echo form_hidden('date', date("m-d-Y H:i:s"));
					echo form_hidden('post_id', $post->id);
					?>
					<br />
					<?php
					echo(date("m-d-Y H:i"));
					
					?>
					</p>
					<?php
									
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