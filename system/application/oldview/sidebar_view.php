<?php

$this->benchmark->mark('start');
$p = new Blogpost();

$count = $p->count();

$p->get();
$taglist = array();
$tagcount = array();

$this->benchmark->mark('middle');

foreach($p->all as $post)
{
	$tags = explode(' ', $post->tags);
	
	foreach($tags as $tag)
	{
		if(!in_array($tag, $taglist))
		{
			$taglist[] = $tag;
		}
		
		if(isset($tagcount[$tag]))
		{
			$tagcount[$tag]++;
		}
		else
		{
			$tagcount[$tag] = 1;
		}
	}
}

$this->benchmark->mark('end');

//echo $this->benchmark->elapsed_time('start', 'end') . ' ' . $this->benchmark->elapsed_time('start', 'middle') . ' ' . $this->benchmark->elapsed_time('middle', 'end');
echo('<div id="tagcloud">');
echo('<h4>Tag Cloud</h4>');
foreach($taglist as $tag)
{
	echo('<a class="tag tag-' . $tagcount[$tag] . '" href="' . base_url() . 'index.php/blog/tagged/' . $tag . '/" title="View ' . $tagcount[$tag] . ' posts tagged ' . $tag . '">' . $tag . '</a> ');
}
echo('</div>');
?>