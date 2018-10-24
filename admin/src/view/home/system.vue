<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" class="on">系统设置</a>
        </div>
        <el-form ref="form" :model="form" label-width="80px">
            <el-form-item label="后台标题">
                <el-input v-model="form.title" label-width="200px"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submit">立即创建</el-button>
                <el-button>取消</el-button>
            </el-form-item>
        </el-form>
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
        }
	},
    created () {
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('home/system',{} , rs => {
                this.form = rs.data.info;
            });
        },
        // 提交
        submit () {
            ajax('home/systemSubmit', this.form , rs => {
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
