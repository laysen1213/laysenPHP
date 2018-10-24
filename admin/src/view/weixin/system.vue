<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" class="on">系统设置</a>
        </div>
        <el-form :model="form">
            <el-form-item label="AppId" :label-width="labelWidth">
                <el-input v-model="form.appid" style="width:200px;"></el-input>
            </el-form-item>
            <el-form-item label="AppSecret" :label-width="labelWidth">
                <el-input v-model="form.appsecret" style="width:200px;"></el-input>
            </el-form-item>
            <el-form-item label="商户号" :label-width="labelWidth">
                <el-input v-model="form.mchid" style="width:200px;"></el-input>
            </el-form-item>
            <el-form-item label="paykey" :label-width="labelWidth">
                <el-input v-model="form.paykey" style="width:200px;"></el-input>
            </el-form-item>
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
            ajax('weixin/system',{} , rs => {
                this.form = rs.data.info;
            });
        },
        // 提交
        submit () {
            this.form.type = this.type;
            ajax('weixin/systemSubmit', this.form , rs => {
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
