<style type="text/css">
.info_content table,.info_content tbody{display: block;width: 100%}

.info_content table tr{display: block;width: 100%;height: auto !important;min-height: 30px;}
.info_content table tr td{display: block;padding: 0 !important;float: left;}
</style>
<?=$this->display('blog/header')?>
<main id="main_content">
	<div class="list_left box_shaw">
		<div class="list_left_nav">
			<h3 class="fl"><?=$data['title']?></h3>
		</div>
		<div class="info_main">
			<div class="list_left_mA">
				<span><?=$data['name']?></span>
				<span>&nbsp;&nbsp;|&nbsp;&nbsp;<?=$data['ctime']?></span>
			</div>
			<div class="info_content"><?=htmlspecialchars_decode($data['content'])?></div>
		</div>
	</div>
	<?=$this->display('blog/tj')?>
</main>
<?=$this->display('blog/footer')?>
<script type="text/javascript">
$(".info_content table tr").each(function(){
	var _l = $(this).find("td").length;
	if(_l == 1){
		$(this).find("td").css("width","99%");
	}else{
		$(this).find("td").css("width","49%");
		if(_l > 2){
			$(this).find("td").eq(2).remove();
		}
	}
})
</script>