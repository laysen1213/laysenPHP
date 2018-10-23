window.onload = function(){
	var http = "/music/"
	var audio = document.getElementById("audio");
	var m_mdf = document.getElementById("m_mdf");
	var m_music_name = document.getElementById("m_music_name");
	var m_blur = document.getElementById("m_blur");
	var m_img = document.getElementById("m_img");
	var progress_wap = document.getElementById("progress_wap");
	var progress_box = document.getElementById("progress_box");
	var progress_touch = document.getElementById("progress_touch");
	var progress_text1 = document.getElementById("progress_text1");
	var progress_text2 = document.getElementById("progress_text2");
	var timer = null;
	var rotateSum = 0;
	var ps = true;

	var vm = new Vue({
		el : "#app",
		data : {
			message : 'hello1',
			navShow : true,
			recData : null,
			cateDate : null,
			catesShow : false,
			catesName : null,
			singerDate : null,
			singerShow : false,
			singName : null,
			songDate : null,
			songShow : false,
			word : '',
			searchDate : null,
			searchFocus : false,
			active : 0,
			nav : ["推荐","歌手","搜索"],
			musicName : null,
			musicAuthor : null,
			musicCover : '',
			musicSrc : '',
			musicPause : true,
			next_id : 0,
			prev_id : 0
		},
		created : function(){
			this.init();
		},
		methods : {
			// 初始化
			init : function(){
				this.recList();
			},
			// 推荐列表
			recList : function(){
				var _this = this;
				L.post(http+'songRec',{},function(rs){
					// 推荐列表
					if(rs.ret == 1){
						_this.recData = rs.list;
						if(!rs.list || rs.list.length <= 0){
							return false;
						}
						// 读取cookie信息
						var musicId = getCookie('musicPlay');
						if(musicId){
							_this.musicFind(musicId , rs.list[0].id);
						}else{
							_this.musicFind(rs.list[0].id);
						}
					}
				});
			},
			// 音乐详情
			musicFind : function(id , id_bak){
				var _this = this;
				L.post(http+'songFind',{id:id},function(rs){
					if(rs.ret == 1){
						if(!rs.list){
							_this.musicFind(id_bak);
							return false;
						}
						_this.musicName = rs.list.title;
						_this.musicAuthor = rs.list.name;
						_this.musicCover = rs.list.cover;
						_this.next_id = rs.next_id;
						_this.prev_id = rs.prev_id;
						_this.musicPause = true;
						audio.src = rs.list.music;
						start();
						setCookie('musicPlay' , id);
					}
				});
			},
			// 分类列表
			cateList : function(){
				var _this = this;
				L.post(http+'cate',{},function(rs){
					if(rs.ret == 1){
						_this.cateDate = rs.list;
					}
				});
			},
			// 歌手列表
			singerList : function(cid){
				var _this = this;
				L.post(http+'singer',{cid:cid},function(rs){
					// 分类列表
					if(rs.ret == 1){
						_this.singerDate = rs.list;
					}
				});
			},
			// 歌曲列表
			songList : function(sid){
				var _this = this;
				L.post(http+'song',{sid:sid},function(rs){
					if(rs.ret == 1){
						_this.songDate = rs.list;
					}
				});
			},
			// 切换导航
			tabs : function(i){
				this.active = i;
				if(i == 1){
					if(!this.cateDate){
						this.cateList();
					}
					this.catesShow = true;
					this.singerShow = false;
				}
			},
			// 切换分类导航
			cate_tab : function(i , names){
				this.singerList(i);
				this.catesShow = false;
				this.singerShow = true;
				this.navShow = false;
				this.catesName = names;
			},
			// 切换歌曲导航
			song_tab : function(i , names){
				this.songList(i);
				this.songShow = true;
				this.singerShow = false;
				this.singName = names;
			},
			// 歌手返回
			singer_back : function(){
				this.catesShow = true;
				this.singerShow = false;
				this.navShow = true;
				this.singerDate = null;
			},
			// 歌曲返回
			song_back : function(){
				this.songShow = false;
				this.singerShow = true;
				this.songDate = null;
			},
			// 搜索
			search : function(){
				var _this = this;
				L.post(http+'search',{word:this.word},function(rs){
					if(rs.ret == 1){
						_this.searchDate = rs.list;
					}
					if(rs.list.length == 0){
						alert("未找到您搜索的歌曲");
					}
				});
			},
			// 清除搜索
			search_close : function(){
				this.word = "";
				this.searchDate = null;
				searchFocus = true;
			},
			// 开始暂停音乐
			playMusic : function(){
				this.musicPause = !this.musicPause;
				if(this.musicPause){
					audio.play();
					setRotate();
				}else{
					audio.pause();
					clearInterval(timer);
				}
			},
			// 打开详情
			musicDetail : function(){
				m_blur.style.display = "block";
			},
			playChose : function(id){
				log(id);
			}
		}
	});

	// 播放开始
	function start(){
		m_music_name.innerText = vm.musicName;
		audio.play();
		rotateSum = 0;
		m_img.style.backgroundImage = "url("+vm.musicCover+")";
		setRotate();
		ps = true;
	}

	function setRotate(){
		clearInterval(timer);
		timer = setInterval(function(){
			rotateSum += 1;
			m_img.style.transform = "rotate(" + rotateSum + "deg)";
		},50);
	}

	m_mdf.onclick = function(){
		m_blur.style.display = "none";
	}

	// 拖拽进度条
	/*progress_touch.addEventListener("touchstart",function(ev){
		var ev = ev.touches[0] || window.event;
		var initX = ev.clientX - this.offsetLeft;
		this.addEventListener("touchmove",function(es){
			var es = es.touches[0] || window.event;
			var x = es.clientX - initX;
			if(x < 0){x = 0;}
			if(x > progress_wap.offsetWidth){x = progress_wap.offsetWidth;}
			progress(x);
		});
		document.addEventListener("touchend",function(){
			document.touchmove = null;
			progress_touch.touchmove = null;
		})
	});
*/
	// 点击进度条
	progress_wap.addEventListener("click",function(ev){
		var ev = ev || window.event;
		var x = ev.clientX - (progress_wap.offsetLeft) - 7;
		progress(x);
	})

	// 进度条
	function progress(x){
		progress_box.style.width = x + "px";
		var timego = x / progress_wap.offsetWidth * audio.duration;
		audio.currentTime = timego;
		playedTime();
	}

	// 播放开始
	audio.addEventListener("timeupdate" , function(){
		playedTime();
	});

	playedTime();
	
	function playedTime(){
		if(!audio.paused){
			if(ps && audio.duration){
				progress_text2.innerHTML = format(audio.duration);
				ps = false;
			}
			if((audio.currentTime + 1) >= audio.duration){
				// 播放结束
				vm.musicFind(vm.next_id);
			}
			var n = audio.currentTime / audio.duration;
			progress_box.style.width = n * progress_wap.offsetWidth + "px";
			progress_text1.innerHTML = format(audio.currentTime);
		}
	}

	// 时间格式转换
	function format(time){
		var time = parseInt(time);
		var m = parseInt(time / 60);
		var s = parseInt(time % 60);
		m = zero(m);
		s = zero(s);
		function zero(num){
			if(num < 10){
				num = "0" + num;
			}
			return num;
		}
		return m + ":" + s;
	}

}
