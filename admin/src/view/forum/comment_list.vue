<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" class="on">评论列表</a>
            <a href="javascript:;" @click="handleDelete()">删除评论</a>
        </div>
        <el-table
            ref="multipleTable"
            :data="list"
            tooltip-effect="dark"
            style="width: 100%"
            @selection-change="handleSelectionChange"
        >
            <el-table-column type="selection" ></el-table-column>
            <el-table-column label="所属帖子" prop="tz_name" width="200"></el-table-column>
            <el-table-column label="评论内容" prop="content"></el-table-column>
            <el-table-column label="点赞数" prop="dzs"></el-table-column>
            <el-table-column label="回复数" prop="hfs"></el-table-column>
            <el-table-column label="创建时间" prop="ctime" width="150"></el-table-column>
            <el-table-column label="操作" width="200">
                 <template slot-scope="scope">
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
    </div>
</template>

<script type="text/javascript">
import {ajax,delAjax} from 'js/common'
export default {
    name: 'forumComment',
    data () {
        return {
            id:0,
            page:1,
            total:1,
            list:[],
            selectArr:[],
        }
	},
    created () {
        this.id = this.$route.query.id || 0;
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('forum/comment_list',{page:this.page,id:this.id} , rs => {
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
            delAjax(this , 'forum_comment' , id);
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        }
    },
    watch: {
        '$route' (to,from){
            this.id = to.query.id || 0;
            this.appData();
        }
    }
}
</script>    
