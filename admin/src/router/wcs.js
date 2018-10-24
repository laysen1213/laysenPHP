import wcs_list from '@/view/wcs/wcs_list'
import wcs_cate from '@/view/wcs/wcs_cate'
import wcs_log from '@/view/wcs/wcs_log'

export default [
    {//博客管理
        path: 'wcs_list',
        name: 'wcs_list',
        component: wcs_list,
    },
    {//博客管理
        path: 'wcs_cate',
        name: 'wcs_cate',
        component: wcs_cate
    },
    {//相册设置
        path: 'wcs_log',
        name: 'wcs_log',
        component: wcs_log
    }
]
