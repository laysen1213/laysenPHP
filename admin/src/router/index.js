import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Main from '@/view/main'
import Login from '@/view/login'

import blog from './blog'
import data from './data'
import forum from './forum'
import home from './home'
import user from './user'
import weixin from './weixin'

export default new Router({
  	routes: [
		{//登录
			path: '/login',
			name: 'login',
			component: Login
		},
		{
			path: '/blog',
			component: Main,
			children: blog
		},
		{
			path: '/data',
			component: Main,
			children: data
		},
		{
			path: '/forum',
			component: Main,
			children: forum
		},
		{
			path: '/home',
			component: Main,
			children: home
		},
		{
			path: '/user',
			component: Main,
			children: user
		},
		{
			path: '/weixin',
			component: Main,
			children: weixin
		},
  	]
})
