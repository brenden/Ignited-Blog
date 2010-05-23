<?php
if($errors = $this->session->userdata('errors') or $notices = $this->session->userdata('notices') or $successes = $this->session->userdata('successes'))
{
	?>
	<div id="messages">
	<?php
	//<div class="span-14 prepend-5 append-5 last">

	if($errors = $this->session->userdata('errors'))
	{
		$this->session->unset_userdata('errors');
		foreach($errors as $e)
		{	
			?>
			<div class="error"><?php echo($e); ?></div>
			<?php
		}
	}
	if($notices = $this->session->userdata('notices'))
	{
		$this->session->unset_userdata('notices');
		foreach($notices as $n)
		{
			?>
			<div class="notice"><?php echo($n); ?></div>
			<?php
		}
	}
	if($successes = $this->session->userdata('successes'))
	{
		$this->session->unset_userdata('successes');
		foreach($successes as $s)
		{
			?>
			<div class="success"><?php echo($s); ?></div>
			<?php
		}
	}
	?>
	</div>
	<?php
}
/* End of file message_view.php */
/* Location: ./system/application/views/message_view.php */