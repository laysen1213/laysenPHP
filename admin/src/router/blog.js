import index from '@/view/blog/index'
import blogCate from '@/view/blog/blogCate'
import album from '@/view/blog/album'
import albumCate from '@/view/blog/albumCate'
import music from '@/view/blog/music'
import musicCate from '@/view/blog/musicCate'
import musicSinger from '@/view/blog/musicSinger'
import message from '@/view/blog/message'

export default [
    {//博客管理
        path: 'index',
        name: 'blog',
        component: index
    },
    {//博客管理
        path: 'blogCate',
        name: 'blogCate',
        component: blogCate,
    },
    {//相册设置
        path: 'album',
        name: 'album',
        component: album
    },
    {//相册设置
        path: 'albumCate',
        name: 'albumCate',
        component: albumCate
    },
    {//音乐管理
        path: 'music',
        name: 'music',
        component: music
    },
    {//音乐分类
        path: 'musicCate',
        name: 'musicCate',
        component: musicCate
    },
    {//音乐歌手
        path: 'musicSinger',
        name: 'musicSinger',
        component: musicSinger
    },
    {//留言管理
        path: 'message',
        name: 'message',
        component: message
    },
]
