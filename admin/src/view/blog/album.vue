<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <router-link to="album" class="on">相册列表</router-link>
            <router-link to="albumCate">相册分类</router-link>
            <a href="javascript:;" @click="handleEdit()">添加相册</a>
            <a href="javascript:;" @click="handleDelete()">删除相册</a>
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
            <el-table-column label="名称" prop="title" width="200"></el-table-column>
            <el-table-column label="分类" prop="cate_name"></el-table-column>
            <el-table-column label="封面">
                <template slot-scope="scope">
                    <img :src="scope.row.cover" alt="" height="80">
                </template>
            </el-table-column>
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
            <el-table-column label="创建时间" prop="ctime" width="150"></el-table-column>
            <el-table-column label="操作" width="200">
                 <template slot-scope="scope">
                    <el-button
                    size="mini"
                    @click="handleEdit(scope.row)">编辑</el-button>
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
                <el-form-item label="相册名称" :label-width="labelWidth">
                    <el-input v-model="form.title" style="width:200px;"></el-input>
                </el-form-item>
                <el-form-item label="分类" :label-width="labelWidth">
                    <el-select v-model="form.cid" placeholder="请选择分类">
                        <el-option v-for="v in cate" :key="v.id" :label="v.name" :value="v.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="相片" :label-width="labelWidth">
                    <upload ref="cover" :image="cover"></upload>
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
    name: 'album',
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
            cover:''
        }
	},
    created () {
        this.appData();
        ajax('blog/albumCateList',{} , rs => {
            this.cate = rs.data.list;
        });
	},
    methods : {
        // 加载数据
		appData () {
            ajax('blog/album',{page:this.page} , rs => {
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
            delAjax(this , 'albumn' , id);
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        },
        // 更改排序
        handleRank (id,rank) {
            rankAjax('albumn' , id , rank)
        },
        // 状态开关
        handleSwitch (id , val , field) {
            switchAjax('albumn' , id , val , field)
        },
        handleEdit (row = ''){
            this.dialogFormVisible = true;
            this.form = row ? row : {};
            this.formMsg = (row ? '修改' : '添加')+'相册';
            this.cover = row ? row.cover : '';
        },
        // 提交
        submit () {
            this.form.cover = this.$refs.cover.imgSrc;
            ajax('blog/albumSubmit', this.form , rs => {
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
