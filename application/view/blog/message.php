<?=$this->display('blog/header')?>
<main id="main_content">
	<div class="list_left box_shaw">
		<div class="list_left_nav">
			<h3 class="fl">给我留言</h3>
		</div>
		<div class="info_main">
			<div class="info_content">
				<form id="contact-form">
					<label>
						<span><i style="color: red;">*</i>昵称：</span>
						<input name="name" type="text" placeholder="请填写你的昵称">
					</label>
					<label>
						<span><i style="color: red;">*</i>性别：</span>
						<select name="sex">
							<option value="男">男</option>
							<option value="女">女</option>
						</select>
					</label>
					<label>
						<span>邮箱：</span>
						<input name="email" type="text" placeholder="请填写你的邮箱">
					</label>
					<label>
						<span>手机号：</span>
						<input name="phone" type="text" placeholder="请填写你的手机号">
					</label>
					
					<label class="message">
						<span><i style="color: red;">*</i>吐槽：</span>
						<textarea name="content"  placeholder="写点什么呢..."></textarea>
					</label>
					<button type="submit">吐槽</button>
				</form>
				<div class="c_content">
					<?php foreach($message as $k => $v){ ?>
					<div class="c_main">
						<div class="c_ct"><?=$v['content']?></div>
						<?php if($v['hf']){ ?>
						<div class="c_hf"><span>回复：</span><?=$v['hf']?></div>
						<?php } ?>
						<div class="c_meta">
							<span class="c_author"><?=$v['name']?></span>
							<?=date('Y-m-d H:i:s',$v['ctime'])?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?=$this->display('blog/tj')?>
</main>
<?=$this->display('blog/footer')?>
<script type="text/javascript">
$("#contact-form").submit(function(){
	if($("input[name=name]").val() == ''){
		alert("请填写你的昵称~");
		return false;
	}
	if($("textarea[name=content]").val() == ''){
		alert("请吐槽一点内容");
		return false;
	}
	$.post("/blog/message_create",$(this).serialize(),function(rs){
		alert("感谢你的吐槽");
		window.location.reload();
	},'json')
	return false;
})
</script>