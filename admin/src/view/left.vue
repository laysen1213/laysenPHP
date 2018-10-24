<template>
   <div class="c_left">
        <div class="c_slider">
            <div class="c_slider_header">
                <router-link to="/home/account">
                    <span style="background-image: url();"></span>
                </router-link>
            </div>
            <div class="c_slider_nav">
                <a href="javascript:;" v-for="(v,k) in list" :key="v.id" :class="k==index?'on':''" @click="leftNav(k)">{{v.name}}</a>
            </div>
        </div>
        <div class="c_secnd">
            <div class="c_secnd_list">
                <div class="c_secnd_h">{{childName}}</div>
                <div class="c_secnd_nav">
                    <a href="javascript:;" v-for="(v,k) in child" :key="v.id" :class="k==sec?'on':''" @click="secNav(k,v)">{{v.name}}</a>
                </div>
            </div>
        </div>
        <div class="c_ft">
            <a href="javascript:;" title="更新缓存" class="cacheDelete" data-url="<?=U('home/cacheDelete')?>">更新缓存</a>
            <a href="<?=U('home/account')?>" title="账号管理"></a>
            <a href="<?=U('main/logout')?>">退出登录</a>
        </div>
    </div>
</template>
<script type="text/javascript">
import {ajax,setStorage,getStorage} from 'js/common'
export default {
    name: 'left',
    data () {
        return {
            uri_1:'',
            uri_2:'',
            index:'',//一级菜单选中下标
            sec:'',//二级菜单选中下标
            list:[],//一级菜单列表
            child:[],//二级菜单列表
            childName:''//一级菜单名称
        }
	},
    created () {
		this.appData();
	},
    methods : {
		appData () {
			ajax('main/column', {} , res => {
                this.list = res.data.list;
                this.index = getStorage('nav_index') || 0;
                this.sec = getStorage('nav_sec') || 0;
                this.childUpdate();
			});
        },
        // 切换一级菜单
        leftNav (k) {
            this.index = k;
            this.sec = 0;
            this.childUpdate();
            this.href();
        },
        // 切换二级菜单
        secNav (k){
            this.sec = k;
            this.href();
        },
        // 更新二级内容
        childUpdate () {
            this.child = this.list[this.index].child;
            this.childName = this.list[this.index].name;
        },
        // 跳转
        href (){
            this.$router.push({
                path : '/'+this.list[this.index].url+'/'+this.child[this.sec].url,
                query : {}
            })
        }
    },
    watch : {
        index () {
            setStorage('nav_index',this.index);
        },
        sec () {
            setStorage('nav_sec',this.sec);
        }
    }
}
</script>