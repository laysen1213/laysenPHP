<template>
   <div class="bgs">
		<div class="login_top">
			<p class="fl right_word">后台管理</p>
		</div>
		<div class="typeArea mit clearfix">
			<img src="~images/login_img.png" class="fl mrt30">
			<div class="fr login_box f16">
				<p class="tc f24">登录</p>
				<form class="formBox" @submit.prevent="submit()">
					<div class="form_div hei86 mrt30">
						<input type="text" class="inputs w350" v-model="form.name" placeholder="请输入您的账号">
					</div>
					<div class="form_div hei86">
						<input type="password" class="inputs w350" v-model="form.password" placeholder="请输入您的密码">
					</div>
					<div class="form_div hei86 prs clearfix">
						<input type="text" class="inputs w170 fl mrr30" v-model="form.code" placeholder="验证码">
						<div class="fl yzms"><img :src="img" alt="" @click="verify()"></div>
					</div>
					<div class="form_div mrt20"><input type="submit" class="inputs w100 btns f20" value="登录"></div>
				</form>
			</div>
		</div>
  	</div>
</template>
<style lang="scss" scoped>
*{padding: 0;margin: 0;font-family:"Microsoft YaHei", arial, helvetica, sans-serif;}
.fl{ float: left;}
.fr{ float: right;}
#code_img{cursor: pointer;}

.bgs{position: absolute;width: 100%;height: 100%;left: 0;top: 0;z-index: 0;background-image: url("~images/login_bg.jpg");background-position: center center;background-size:100% 100%;-webkit-background-size:100% 100%;}
.right_word{font-size: 20px;margin-top: 25px;margin-left: 25px;}
.login_top{width: 100%;height: 80px;overflow: hidden; background-color: rgba(5,16,39,0.6);color: #fff;}
.typeArea{width: 1024px;margin-left: auto;margin-right: auto;}
.logos{margin-right: 25px; margin-top: 22px; padding-right: 25px;border-right: 1px solid #fff;height: 34px;}
.logos img{height: 34px;width: auto;display: inline-block;}
.right_word{font-size: 20px;margin-top: 25px;}

.footer_lines{border-top: 1px solid #414e68;height: 80px;line-height: 80px; width: 100%;text-align: center;color: #fff; position: absolute;bottom: 0;left: 0;z-index: 1;}
.tc{text-align: center;}
.login_box{box-shadow: 0 10px 5px rgba(0,0,0,0.2); background-color: rgba(255,255,255,0.2);border-radius: 10px;width: 390px; padding: 32px;padding-bottom: 50px; color: #fff;}
.inputs{height: 56px;border: 0;border-radius: 5px;display: block;padding: 0 20px;font-size: 16px;}
.mrr30{margin-right: 20px;}
.mrt10{margin-top: 10px;}
.mrt20{margin-top: 20px;}
.mrt30{margin-top: 30px;}
.mrt40{margin-top: 40px;}
.mrt50{margin-top: 50px;}
.mrb30{margin-bottom: 30px;}
.w100{width: 100%;}
.w170{width: 170px;}
.w350{width: 350px;}
.yzms img{height: 56px;width: 160px;border-radius: 5px;cursor: pointer;}
label{cursor: pointer;}
.fcred{color: #ee2626;}

.checks{
	-webkit-appearance: none;
    vertical-align: middle;
    margin-right: 5px;
    width: 21px;
    height: 21px;
    background: url("~/images/checks.png") no-repeat;
    background-position: 0 0;
    cursor: pointer;
}
.checks:checked{background-position: -22px 0;}

.btns{box-shadow: 0 3px 3px rgba(0,0,0,0.3);background-color: #fefefe;cursor: pointer;}
.f16{font-size: 16px;}
.f20{font-size: 20px;}
.f24{font-size: 24px;}
.tips_p{width: 100%;margin-top:6px;font-size: 14px;padding-left: 20px;}
.hei30{height: 35px;}
.hei86{height: 86px;}
.mit{margin-top: 4%;}

</style>
<script type="text/javascript">
import {ajax} from 'js/common'
export default {
  	name: 'login',
  	data () {
        return {
			img:'',
			ver:0,
            form: {
				name:'admin',
				password:'114477',
				code:''
			}
        }
	},
	created () {
		this.verify();
	},
	methods : {
		// 验证码
		verify () {
			this.img = 'admin/login/verify?v='+this.ver;
			this.ver++;
		},
		// 提交
		submit () {
			ajax('/admin/login/submit', this.form , res => {
                if(res.ret == 1){
					// alert("登录成功");
					this.$router.push({
						path: '/'
					})
				}else{
					alert(res.msg);
					if(res.ret != -1){
						this.form.code = '';
						this.verify();
					}
				}
			})
		}
	}
}
</script>