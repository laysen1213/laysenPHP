<?=$this->display('index/header')?>
<div class="list_left">
	<div class="info_left" style="min-height: 400px;">
		<img src="<?=$plug?>images/music.png" alt="" style="width: 200px;height: 200px;margin: auto;display: block;margin-top: 90px;">
	</div>
</div>
<div class="list_right">
	<h3>推荐阅读</h3>
	<ul class="list_right_ul">
		<?php foreach($tj_list as $k => $v){ ?>
		<li>
			<i></i>
			<a href="<?=U('index/detail').'?id='.$v['id']?>" title="<?=$v['title']?>"><?=$v['title']?></a>
			<p><?=$v['ctime']?></p>
		</li>
		<?php } ?>
	</ul>
</div>
<?=$this->display('index/footer')?>