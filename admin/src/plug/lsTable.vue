<template>
    <table class="ui-table ui-table-striped">
        <colgroup>
            <col style="width:80px;">
        </colgroup>
        <thead>
            <tr>
                <th style="width: 32px;"><input type="checkbox" class="all_check" @change="changeAll()" v-model="chk"></th>
                <th v-for="(v,k) in navList" :key="k">{{v}}</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(v,k) in dataList" :key="k">
                <td><input type="checkbox" class="sign_check" :value="v.id" @change="changeSel(v.id)" v-model="v.chk"></td>
                <td v-for="(vv,kk) in dataType" :key="kk">
                    <div v-for="(vvv,kkk) in vv" :key="kkk">
                        <template v-if="kkk == 'string'">
                            {{v[vvv]}}
                        </template>
                        <template v-else-if="kkk == 'rank'">
                            <input type="text" v-model="v[vvv]" class="w50 tac ajax_rank" @blur="handleRank(v.id,v[vvv])"/>
                        </template>
                        <template v-else-if="kkk == 'image'">
                            <img :src="v[vvv]" height="60">
                        </template>
                        <template v-else-if="kkk == 'status'">
                            <img :src="'/static/images/'+(v[vvv]==1?'true':'false')+'.gif'" class="change_status" @click="handleSwitch(v.id,vvv,k)">
                        </template>
                    </div>
                </td>
                <td>
                    <div>
                        <slot :name="'oper'+k"></slot>
                        <template v-if="oper.edit">
                            <a href="javascript:;" @click="tabEdit(v)" class="ui-btn">修改</a>
                        </template>
                        <template v-if="oper.del">
                        <a href="javascript:;" @click="tabDelete(v.id)" class="ui-btn">删除</a>
                        </template>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<!-- <div v-for="(v,k) in list" :key="k" :slot="'oper'+k">
    <a href="javascript:;" @click="handleEdit(v)" class="ui-btn">修改</a>
    <a href="javascript:;" @click="handleDelete(v.id)" class="ui-btn">删除</a>
</div> -->
<script>
import {ajax,delAjax,rankAjax,switchAjax} from 'js/common'
export default {
    name: 'lsTable',
    props: {
        dao: {
            type: String,
            default: ''
        },
        dataList: {
            type: Array,
            default: [],
        },
        navList: {
            type: Array,
            default: []
        },
        dataType: {
            type: Array,
            default: []
        },
        oper: {
            type: Object,
            default: ''
        }
    },
    data() {
        return {
            chk:false,//全选按钮
            selectArr: [],//选中数组
        }
    },
    created () {
        
    },
    watch : {
        
    },
    methods: {
        // 全选全不选
        changeAll(){
            this.selectArr = [];
            this.dataList.forEach((v,k) => {
                this.dataList[k].chk = this.chk
                if(this.chk){
                    this.selectArr.push(v.id)
                }
            })
        },
        // 选中复选框
        changeSel(id) {
            var _i = this.selectArr.indexOf(id);
            if(_i >= 0){
                this.selectArr.splice(_i,1);
            }else{
                this.selectArr.push(id)
            }
        },
        // 重新加载数据
        appAjax(){
            this.$parent.appData();
        },
        // 修改弹窗
        tabEdit (v) {
            this.$parent.handleEdit(v);
        },
        // 更改排序
        handleRank (id,rank) {
            rankAjax(this.dao , id , rank)
        },
        // 状态开关
        handleSwitch (id , field , k) {
            var val = this.dataList[k][field] == 1 ? 2 : 1;
            this.dataList[k][field] = val;
            switchAjax(this.dao , id , val , field)
        },
        // 删除
        tabDelete (id = 0) {
            delAjax(this , this.dao , id);
        },
    }
}
</script>
