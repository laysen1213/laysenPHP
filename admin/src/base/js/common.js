import axios from 'axios'
// "http://m.91laysen" + 
export function ajax (url, data = {} , callback = () => {}) {
    var _data =  {
        // api_type: 1
    }
    url = '/admin/' + url;
    var data = formData (Object.assign(data, _data))
    return axios.post(url, data).then((response) => {
        var res = response.data;
        callback(res);
    })
}
// 数组序列号
export function formData (data) {
    let arr = [];
    for (let it in data) {
        arr.push(encodeURIComponent(it) + "=" + encodeURIComponent(data[it]))
    }
    return arr.join("&")
}

// 通用删除
export function delAjax(_this = '' , dao = '' , id = 0 , callback = () => {}) {
    _this.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        if (id == 0) {
            //多选删除
            id = idArr(_this.selectArr);
        }
        ajax('main/delAjax', {id: id, dao: dao}, res => {
            _this.appAjax();
            _this.$message({
                showClose: true,
                type: 'success',
                message: '删除成功!'
            });
        });
    }).catch(() => { });
}

// 通用排序
export function rankAjax (dao = '' , id = 0 , val = 0) {
    ajax('main/rankAjax', {id: id , dao: dao , val : val}, res => {});
}

// 通用开关
export function switchAjax(dao = '', id = 0, val = '', field = ''){
    ajax('main/switchAjax', {id: id , dao: dao, val : val ,field: field}, res => { });
}

// 分解数组
export function idArr(arr = []){
    var id = [];
    for(let v of arr){
        id.push(v);
    }
    return id.join(",");
}

export function pageList(data = {}, callback = () => { } , url = 'main/pageList') {
    ajax(url , data , res => {
        callback(res.data);
    });
}

// 获取域名
// default
export function http_host() {
    return document.location.protocol + '//' + window.location.host;
}

//html5 localStorage存储数据
//设置数据
export function setStorage(_key, val) {
    localStorage[_key] = val;
}

//获取数据
export function getStorage(_key) {
    return localStorage[_key];
}
//清除数据
export function clearStorage(_key) {
    localStorage.removeItem(_key);
}

//html5 localStorage存储数据
//设置数据
export function setSessionStorage(_key, val) {
    sessionStorage[_key] = val;
}
//获取数据
export function getSessionStorage(_key) {
    return sessionStorage[_key];
}
//清除数据
export function clearSessionStorage(_key) {
    sessionStorage.removeItem(_key);
}
