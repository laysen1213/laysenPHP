<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <router-link to="index">博客列表</router-link>
            <router-link to="blogCate" class="on">分类列表</router-link>
            <a href="javascript:;" @click="handleEdit()">添加分类</a>
            <a href="javascript:;" @click="handleDelete()">删除分类</a>
        </div>
        <ls-Table 
            :dao="'blog_cate'"
            :dataList="list" 
            :navList="['排序','分类名称','状态','创建时间']"
            :dataType="[
                {rank:'rank'},{string:'name'},{status:'status'},{string:'ctime'}
            ]"
        >
            <div v-for="(v,k) in list" :key="k" :slot="'oper'+k">
                <a href="javascript:;" @click="handleEdit(v)" class="ui-btn">修改</a>
                <a href="javascript:;" @click="handleDelete(v.id)" class="ui-btn">删除</a>
            </div>
        </ls-Table>
        <el-pagination
        @current-change="handleCurrentChange"
        :current-page="page"
        :page-size="10"
        layout="total,  prev, pager, next, jumper"
        :total="total">
        </el-pagination>

        <el-dialog :title="formMsg" :visible.sync="dialogFormVisible">
            <el-form :model="form">
                <el-form-item label="分类名称" :label-width="labelWidth">
                    <el-input v-model="form.name" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="分类" :label-width="labelWidth">
                    <el-select v-model="form.cid" placeholder="请选择分类">
                        <el-option v-for="v in cate" :key="v.id" :label="v.name" :value="v.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-input v-model="form.id" type="hidden"></el-input>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script type="text/javascript">
import {ajax,delAjax,rankAjax,switchAjax} from 'js/common'
export default {
    name: 'blogCate',
    data () {
        return {
            page:1,
            total:1,
            list:[],
            selectArr:[],
            dialogFormVisible:false,
            form:{},
            formMsg:'',
            labelWidth:'80px',
            cate:[],
        }
	},
    created () {
        this.appData();
        ajax('blog/blogCateAll',{} , rs => {
            this.cate = rs.data.list;
        });
	},
    methods : {
        // 加载数据
		appData () {
            ajax('blog/blogCate',{page:this.page} , rs => {
                this.list = rs.data.list;
                this.total = Number(rs.data.nums);
            });
        },
        // 翻页
        handleCurrentChange(val) {
            this.page = val;
            this.appData();
        },
        // 单条/多条删除
        handleDelete (id = 0) {
            delAjax(this , 'blog_cate' , id);
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        },
        // 更改排序
        handleRank (id,rank) {
            rankAjax('blog_cate' , id , rank)
        },
        // 状态开关
        handleSwitch (id , val , field) {
            switchAjax('blog_cate' , id , val , field)
        },
        // 添加&编辑
        handleEdit (row = ''){
            this.dialogFormVisible = true;
            this.form = row ? row : {};
            this.formMsg = (row ? '修改' : '添加')+'分类';
        },
        // 提交
        submit () {
            ajax('blog/blogCateSubmit', this.form , rs => {
                if(rs.ret == 1){
                    var message = (this.form.id ? '修改' : '添加')+'成功!';
                    this.$message({
                        showClose: true,
                        type: 'success',
                        message: message
                    });
                    this.dialogFormVisible = false;
                    this.appData();
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
