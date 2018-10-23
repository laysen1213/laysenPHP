<?=$this->display('blog/header')?>
<div class="banner_slide">
	<ul>
		<li style="background-image: url(<?=$plug?>images/3.jpg?v=1.2);"></li>
		<li style="background-image: url(<?=$plug?>images/2.jpg?v=1.2);"></li>
		<li style="background-image: url(<?=$plug?>images/1.jpg?v=1.2);"></li>
	</ul>
	<a href="javascript:;" class="banner_flex banner_flex_prev"></a>
	<a href="javascript:;" class="banner_flex banner_flex_next"></a>
	<div class="banner_active"></div>
</div>
<main id="main">
	<section class="section2">
		<h2 class="section_h2">已经有许多的爱好者入驻了我的网站······</h2>
		<p class="section_p">
		在这里，发现基于共同兴趣的同好；鼓励原创和分享精神；除了牛逼的技能，我们更在意背后价值观的认同。
		</p>
		<div class="section2_list">
			<ul>
				<?php foreach($data as $k => $v){ ?>
				<li class="box_shaw">
					<a href="<?=U('blog/detail').'?pid='.$v['pid'].'&id='.$v['id']?>">
						<div class="section_img_a" style="background-image: url(<?=$v['cover']?>);" data-image="<?=$v['cover']?>">
							<div class="section2_info">
								<div class="section2_gh"><?=$v['intro']?></div>
							</div>
						</div>
						<div class="section2_right">
							<h5><?=$v['title']?></h5>
							<div class="section2_content"><?=$v['intro']?></div>
							<div class="section2_dean">
								<div class="section2_fl fl"><?=$v['ctime']?></div>
								<div class="section2_fr fr">[<?=$v['cid']?>]</div>
							</div>
						</div>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<section class="section1">
		<h2 class="section_h2">【匆匆那年】</h2>
		<p class="section_p">
			是岁月宽容恩赐反悔的时间
		</p>
		<ul class="section1_ul">
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh1.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh2.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh3.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh4.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh5.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh6.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh7.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh8.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh9.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh10.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh11.jpg" alt=""></a></li>
			<li><a href="javascript:;"><img src="<?=$plug?>images/qh12.jpg" alt=""></a></li>
			<div class="section1_box"></div>
		</ul>
	</section>
</main>
<style type="text/css">
	@media only screen  and (max-width:750px){
		.dean{background: #fff;}
	}
</style>
<?=$this->display('blog/footer')?>
<script type="text/javascript">
$(function(){
	$(".banner_slide").banner();
})
</script>