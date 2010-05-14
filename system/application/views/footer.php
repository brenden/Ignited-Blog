<div class="span-8">

<?php

if($this->session->userdata('loggedin'))
{
	?>
	<a href="<?php echo(base_url()); ?>index.php/blog/logout" title="Login">Logout</a>
	<?php
}
else
{
	?>
	<a href="<?php echo(base_url()); ?>index.php/blog/login" title="Login">Login</a>
	<?php
}

?>

</div>

<div class="span-8">
	<h3>Made with</h3> 
	<div class="span-8"> 
	<a href="http://codeigniter.com/" title="CodeIgniter PHP Framework">CodeIgniter</a>: Open source MVC framework written in PHP
	</div> 
	<div class="span-8"> 
		<a href="http://stensi.com/datamapper/" title="DataMapper CodeIgniter Plugin">DataMapper</a>: Object Relational Mapper in PHP for CodeIgniter
	</div> 
	<div class="span-8"> 
		<a href="http://www.blueprintcss.org/" title="Blueprint CSS Framework">Blueprint</a>: The original CSS framework
	</div> 
	<div class="span-8"> 
		<a href="http://git-scm.com/" title="GIT - fast version control">git</a>: Fast version control system
	</div>
</div>