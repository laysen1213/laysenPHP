<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v=1.3" />
	<link rel="stylesheet" href="<?=$plug?>css/laysen.css">
	<link rel="stylesheet" href="<?=$plug?>css/style.css?v=1.7">
	<script type="text/javascript" src="<?=$plug?>js/jquery-1.8.3.min.js"></script>
</head>
<body>
<header id="header">
	<div class="header_content">
		<h2>
			<a href="/" title="">
				<img src="<?=$plug?>images/logo.png?v=1" alt="" >
			</a>
		</h2>
		<nav id="nav">
			<a href="/" class="<?php if($nav == 1){ ?>on<?php } ?>">首页</a>
			<a href="<?=U('blog/lists')?>?type=1" class="<?php if($nav == 2){ ?>on<?php } ?>">技术分享</a>
			<a href="<?=U('blog/lists')?>?type=2" class="<?php if($nav == 3){ ?>on<?php } ?>">心情随笔</a>
			<a href="<?=U('blog/message')?>" class="<?php if($nav == 4){ ?>on<?php } ?>">给我留言</a>
			<a href="<?=U('blog/about')?>" class="<?php if($nav == 5){ ?>on<?php } ?>">关于我</a>
			<!-- <a href="<?=U('index/music')?>" class="<?php if($nav == 3){ ?>on<?php } ?>">音乐</a> -->
			<div class="nav_line"></div>
		</nav>
		<div class="header_search">
			<form action="<?=U('blog/lists')?>">
				<input type="text" name="search" placeholder="搜索" id="search_input" value="<?=isset($search)?$search:''?>">
				<input type="hidden" name="type" value="<?=$nav == 3 ? 2 : 1?>">
				<img src="<?=$plug?>images/search.png" >
				<input type="submit" id="search_submit">
			</form>
		</div>
	</div>
</header>
