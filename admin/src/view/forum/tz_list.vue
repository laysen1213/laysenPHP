<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <router-link to="index" class="on">帖子列表</router-link>
            <a href="javascript:;" @click="handleDelete()">删除帖子</a>
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
            <el-table-column label="所属话题" prop="ht_name"></el-table-column>
            <el-table-column label="图片">
                <template slot-scope="scope" >
                    <img v-for="v in scope.row.image" :key="v.id" :src="v" alt="" height="80">
                </template>
            </el-table-column>
            <el-table-column label="评论内容" prop="content"></el-table-column>
            <el-table-column label="评论数" prop="pls"></el-table-column>
            <el-table-column label="点赞数" prop="dzs"></el-table-column>
            <el-table-column label="收藏数" prop="scs"></el-table-column>
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
                    @click="plHref(scope.row.id)">查看评论</el-button>
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
import {ajax,delAjax,rankAjax,switchAjax} from 'js/common'
export default {
    name: 'tz_list',
    data () {
        return {
            page:1,
            total:1,
            list:[],
            selectArr:[]
        }
	},
    created () {
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('forum/tz_list',{page:this.page} , rs => {
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
            delAjax(this , 'forum_tz' , id);
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        },
        // 更改排序
        handleRank (id,rank) {
            rankAjax('forum_tz' , id , rank)
        },
        // 状态开关
        handleSwitch (id , val , field) {
            switchAjax('forum_tz' , id , val , field)
        },
        plHref (id){
            this.$router.push({
                path: 'comment_list',
                query:{id:id}
            })
        }
    }
}
</script>    
