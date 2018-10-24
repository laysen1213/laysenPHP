<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <router-link to="index" class="on">博客列表</router-link>
            <router-link to="blogCate">分类列表</router-link>
            <a href="javascript:;" @click="handleEdit()">添加博客</a>
            <a href="javascript:;" @click="handleDelete()">删除博客</a>
        </div>
        <ls-Table 
            :dao="'blog'"
            :dataList="list" 
            :navList="['排序','标题','分类','封面','推荐','状态','创建时间']"
            :dataType="[
                {rank:'rank'},{string:'title'},{string:'cate_name'},{image:'cover'},
                {status:'is_recommend'},{status:'status'},{string:'ctime'}
            ]"
            :oper="oper"
            ref="tab">
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
                <el-form-item label="标题" :label-width="labelWidth">
                    <el-input v-model="form.title" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="分类" :label-width="labelWidth">
                    <el-select v-model="form.cid" placeholder="请选择分类">
                        <el-option v-for="v in cate" :key="v.id" :label="v.name" :value="v.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="封面" :label-width="labelWidth">
                    <upload ref="cover" :image="cover"></upload>
                </el-form-item>
                <el-form-item label="简要" :label-width="labelWidth">
                    <el-input v-model="form.intro" type="textarea" style="max-width:400px;"></el-input>
                </el-form-item>
                <el-form-item label="网页标题" :label-width="labelWidth">
                    <el-input v-model="form.seo_title" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="关键词" :label-width="labelWidth">
                    <el-input v-model="form.seo_keyword" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="描述" :label-width="labelWidth">
                    <el-input v-model="form.seo_describe" style="width:200px;"></el-input>
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
    name: 'blog',
    data () {
        return {
            oper:{del:true,edit:true},
            page:1,
            total:1,
            list:[],
            dialogFormVisible:false,
            form:{},
            formMsg:'',
            labelWidth:'80px',
            cate:[],
            cover:''
        }
	},
    created () {
        this.appData();
        ajax('blog/blogCate',{} , rs => {
            this.cate = rs.data.list;
        });
	},
    methods : {
        // 加载数据
		appData () {
            ajax('blog/index',{page:this.page} , rs => {
                this.list = rs.data.list;
                this.total = Number(rs.data.nums);
            });
        },
        // 翻页
        handleCurrentChange(val) {
            this.page = val;
            this.appData();
        },
        // 多条删除
        handleDelete () {
            this.$refs.tab.tabDelete()
        },
        // 添加&编辑
        handleEdit (row = ''){
            this.dialogFormVisible = true;
            this.form = row ? row : {};
            this.formMsg = (row ? '修改' : '添加')+'博客';
            this.cover = row ? row.cover : '';
        },
        // 提交
        submit () {
            this.form.cover = this.$refs.cover.imgSrc;
            ajax('blog/blogSubmit', this.form , rs => {
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
