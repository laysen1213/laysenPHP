import column from '@/view/home/column'
import system from '@/view/home/system'
import log from '@/view/home/log'
import role_list from '@/view/home/role_list'
import account from '@/view/home/account'
import admin_list from '@/view/home/admin_list'

export default [
    {//栏目管理
        path: 'column',
        name: 'column',
        component: column
    },
    {//系统设置
        path: 'system',
        name: 'system',
        component: system
    },
    {//日志管理
        path: 'log',
        name: 'log',
        component: log
    },
    {//角色管理
        path: 'role_list',
        name: 'role_list',
        component: role_list
    },
    {//管理员管理
        path: 'account',
        name: 'account',
        component: account
    },
    {//管理员列表
        path: 'admin_list',
        name: 'admin_list',
        component: admin_list
    },
]
