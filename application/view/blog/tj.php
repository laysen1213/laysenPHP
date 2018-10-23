<div class="list_right box_shaw">
	<h3>推荐阅读</h3>
	<ul class="list_right_ul">
		<?php foreach($tj_list as $k => $v){ ?>
		<li>
			<i></i>
			<a href="<?=U('blog/detail').'?pid='.$v['pid'].'&id='.$v['id']?>" title="<?=$v['title']?>"><?=$v['title']?></a>
			<p><?=$v['ctime']?></p>
		</li>
		<?php } ?>
	</ul>
</div>