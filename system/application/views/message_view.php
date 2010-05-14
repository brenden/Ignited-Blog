<?php
				if(isset($messages))
				{
					?>
					<div id="messages">
					<?php
//					<div class="span-14 prepend-5 append-5 last">

					if(isset($messages['errors']))
					{
						foreach($messages['errors'] as $e)
						{	
							?>
							<div class="error"><?php echo($e); ?></div>
							<?php
						}
					}
					if(isset($messages['notice']))
					{
						foreach($messages['notice'] as $n)
						{
							?>
							<div class="notice"><?php echo($n); ?></div>
							<?php
						}
					}
					if(isset($messages['success']))
					{
						foreach($messages['success'] as $s)
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