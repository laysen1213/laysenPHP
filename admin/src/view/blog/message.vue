<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" class="on">留言列表</a>
            <a href="javascript:;" @click="handleDelete()">删除留言</a>
        </div>
        <el-table
            ref="multipleTable"
            :data="list"
            tooltip-effect="dark"
            style="width: 100%"
            @selection-change="handleSelectionChange"
        >
            <el-table-column type="selection" ></el-table-column>
            <el-table-column label="排序" width="80">
                <template slot-scope="scope">
                    <el-input v-model="scope.row.rank" @blur="handleRank(scope.row.id,scope.row.rank)"></el-input>
                </template>
            </el-table-column>
            <el-table-column label="姓名" prop="name" width="200"></el-table-column>
            <el-table-column label="手机号" prop="phone"></el-table-column>
            <el-table-column label="邮箱" prop="email"></el-table-column>
            <el-table-column label="性别" prop="sex"></el-table-column>
            <el-table-column label="留言内容" prop="content"></el-table-column>
            <el-table-column label="留言回复" prop="hf"></el-table-column>
            <el-table-column label="IP地址" prop="ip"></el-table-column>
            <el-table-column label="状态" width="80">
                <template slot-scope="scope">
                    <el-switch
                    v-model="scope.row.status"
                    active-value=1
                    inactive-value=2
                    active-color="#13ce66"
                    inactive-color="#999999"
                    @change="handleSwitch(scope.row.id,scope.row.status,'status')"
                    >
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column label="留言时间" prop="ctime" width="150"></el-table-column>
            <el-table-column label="操作" width="200">
                 <template slot-scope="scope">
                    <el-button
                    size="mini"
                    @click="handleEdit(scope.row)">回复</el-button>
                    <el-button
                    size="mini"
                    type="danger"
                    @click="handleDelete(scope.row.id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
        @current-change="handleCurrentChange"
        :current-page="page"
        :page-size="10"
        layout="total,  prev, pager, next, jumper"
        :total="total">
        </el-pagination>

        <el-dialog :title="formMsg" :visible.sync="dialogFormVisible">
            <el-form :model="form">
                <el-form-item label="回复内容" :label-width="labelWidth">
                    <el-input v-model="form.intro" type="textarea" style="max-width:400px;"></el-input>
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
    name: 'message',
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
        }
	},
    created () {
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('blog/message',{page:this.page} , rs => {
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
            delAjax(this , 'blog_message' , id);
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        },
        // 更改排序
        handleRank (id,rank) {
            rankAjax('blog_message' , id , rank)
        },
        // 状态开关
        handleSwitch (id , val , field) {
            switchAjax('blog_message' , id , val , field)
        },
        // 编辑
        handleEdit (row){
            this.dialogFormVisible = true;
            this.form = row;
            this.formMsg = '回复';
        },
        // 提交
        submit () {
            ajax('blog/blogMessageSubmit', this.form , rs => {
                if(rs.ret == 1){
                    var message = '回复成功!';
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
