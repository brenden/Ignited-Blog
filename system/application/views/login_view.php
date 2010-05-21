<?php $this->load->view('head_view', array('title' => $title)); ?>
<body>
	<div class="container">
		<div id="masthead" class="span-24 last">
			<?php $this->load->view('masthead_view'); ?>
		</div>
		
		<div class="span-24" id="content">
			<div class="span-14 prepend-5 append-5 last">
				<?php
					if($this->session->userdata('loggedin'))
					{
						?>
						<div class="notice">
							You are already logged in. Would you like to <a href="<?php echo(base_url()); ?>index.php/blog/logout/" title="Logout">logout</a>?
						</div>
						<?php
					}
					else
					{
						$this->load->view('message_view');
					
						echo form_open('blog/login');
						?>
						<p>
						<?php
						echo form_fieldset('Login');

						echo form_hidden('post', 'true');
					
						echo form_label('Username', 'username', array('class' => 'label'));
						echo form_input('username', '');

						?>
						<br />
						<?php

						echo form_label('Password', 'password', array('class' => 'label'));
						echo form_password('password', '');

						?>
						<br />
						<?php

						echo form_submit('submit', 'Login');
					
						echo form_fieldset_close();
						?>
						</p>
						<?php
						echo form_close();
					}
				?>	
			</div>
		</div>
		
		<div class="span-24 last" id="footer">
			<?php $this->load->view('footer'); ?>
		</div>
	</div>
</body>
</html>