import ht_list from '@/view/forum/ht_list'
import tz_list from '@/view/forum/tz_list'
import comment_list from '@/view/forum/comment_list'

export default [
    {//话题管理
        path: 'ht_list',
        name: 'ht_list',
        component: ht_list
    },
    {//帖子
        path: 'tz_list',
        name: 'tz_list',
        component: tz_list
    },
    {//评论管理
        path: 'comment_list',
        name: 'forumComment',
        component: comment_list
    }
]
