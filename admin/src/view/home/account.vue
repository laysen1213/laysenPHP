<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" :class="type==1?'on':''" @click="type=1">账号管理</a>
            <a href="javascript:;" :class="type==2?'on':''" @click="type=2">修改密码</a>
        </div>
        <el-form :model="form">
            <template v-if="type==1">
                <el-form-item label="标题" :label-width="labelWidth">
                    <el-input v-model="form.nickname" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="封面" :label-width="labelWidth">
                    <upload ref="cover" :image="cover"></upload>
                </el-form-item>
            </template>

            <template v-else>
                <el-form-item label="密码" :label-width="labelWidth">
                    <el-input v-model="form.password" style="width:200px;" type="password"></el-input>
                </el-form-item>
                <el-form-item label="确认密码" :label-width="labelWidth">
                    <el-input v-model="form.passwordAgain" style="width:200px;" type="password"></el-input>
                </el-form-item>
            </template>
            
            <el-input v-model="form.id" type="hidden"></el-input>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="submit">确 定</el-button>
        </div>
    </div>
</template>

<script type="text/javascript">
import {ajax} from 'js/common'
export default {
    name: 'account',
    data () {
        return {
            type:1,
            form:{},
            formMsg:'',
            labelWidth:'80px',
            cover:''
        }
	},
    created () {
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('home/account',{} , rs => {
                this.form = rs.data.info;
                this.cover = rs.data.info.logo
            });
        },
        // 提交
        submit () {
            if(this.type == 1){
                this.form.logo = this.$refs.cover.imgSrc;
            }
            this.form.type = this.type;
            ajax('home/accountSubmit', this.form , rs => {
                if(rs.ret == 1){
                    var message = '修改成功!';
                    this.$message({
                        showClose: true,
                        type: 'success',
                        message: message
                    });
                }else{
                    this.$message({
                        showClose: true,
                        type: 'error',
                        message: rs.msg
                    });
                }
            });
        }
    }
}
</script>    
