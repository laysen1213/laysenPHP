<?=$this->display('blog/header')?>
<main id="main_content">
	<div class="list_left box_shaw">
		<!-- <div class="list_left_top">
			<div class="list_left_tj">
				<span>最新动态：</span>
				<a href="">做最好的自己</a>
			</div>
			<div class="list_left_tjPage">
				<a href="javascript:;" class="prev"></a>
				<a href="javascript:;" class="next"></a>
			</div>
		</div> -->
		<div class="list_left_nav">
			<h3 class="fl"><?=$c1==1?'技术分享':'心情随笔'?></h3>
			<div class="list_left_navLt fr">
				<a href="<?=U('blog/lists').'?type='.$c1?>" class="<?=$c2==0?'on':''?>">全部</a>
				<?php foreach($cate as $k => $v){ ?>
				<a href="<?=U('blog/lists').'?type='.$c1.'&sec='.$v['id']?>" class="<?=$c2==$v['id']?'on':''?>"><?=$v['name']?></a>
				<?php } ?>
				<div class="nav_line"></div>
			</div>
		</div>
		<div class="list_left_main">
			<ul class="list_left_ul">
				<?php if($data){ foreach($data as $k => $v){ ?>
				<li>
					<a href="<?=U('blog/detail').'?pid='.$c1.'&id='.$v['id']?>" class="list_left_ad box_shaw">
						<img src="<?=$v['cover']?>" alt="" >
					</a>
					<div class="list_left_mF">
						<h3>
							<a href="<?=U('blog/detail').'?pid='.$c1.'&id='.$v['id']?>" title="<?=$v['title']?>"><?=$v['title']?></a>
						</h3>
						<div class="list_left_mC"><?=$v['intro']?></div>
						<div class="list_left_mA">
							<span><?=$v['ctime']?></span>
							<a href="<?=U('blog/lists').'?type='.$c1.'&sec='.$v['pid']?>" class="list_left_mlook">[<?=$v['cid']?>]</a>
						</div>
					</div>
				</li>
				<?php } } else { ?>
				<li>暂无数据</li>
				<?php } ?>
			</ul>
			<?=$strpage?>
		</div>
	</div>
	<?=$this->display('blog/tj')?>
</main>
<?=$this->display('blog/footer')?>
<script type="text/javascript">
	// 弹性跟随
	$(".list_left_navLt").nav();
</script>