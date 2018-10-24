<template>
    <div class="c_right_box">
        <div class="c_right_top">
            <a href="javascript:;" class="on">用户列表</a>
        </div>
        <el-table
            ref="multipleTable"
            :data="list"
            tooltip-effect="dark"
            style="width: 100%"
            @selection-change="handleSelectionChange"
        >
            <el-table-column type="selection" ></el-table-column>
            <el-table-column label="昵称" prop="name" width="200"></el-table-column>
            <el-table-column label="头像" prop="account" width="200">
                <template slot-scope="scope">
                    <img :src="scope.row.headimg" alt="" height="80">
                </template>
            </el-table-column>
            <el-table-column label="手机号" prop="phone" width="200"></el-table-column>
            <el-table-column label="地区" prop="address" width="200"></el-table-column>
            <el-table-column label="创建时间" prop="ctime" width="150"></el-table-column>
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
import {ajax} from 'js/common'
export default {
    name: 'user',
    data () {
        return {
            page:1,
            total:1,
            list:[],
            selectArr:[],
        }
	},
    created () {
        this.appData();
	},
    methods : {
        // 加载数据
		appData () {
            ajax('user/index',{page:this.page} , rs => {
                this.list = rs.data.list;
                this.total = Number(rs.data.nums);
            });
        },
        // 翻页
        handleCurrentChange(val) {
            this.page = val;
            this.appData();
        },
        // 获取选中的id
        handleSelectionChange(val) {
            this.selectArr = val;
        }
    }
}
</script>    
