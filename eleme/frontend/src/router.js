import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [{
      path: '/',
      children:[
        {
          path:"",
          redirect:"/home"
        },{
          path:"/home",
          name:"Home",
          component:()=>import("./views/home.vue")
        },{
          path:"/me",
          name:"Me",
          component:()=>import("./views/me.vue")
        },{
          path:"/order",
          name:"Order",
          component:()=>import("./views/order.vue")
        },{
          path:"/address",
          name:"Address",
          component:()=>import("./views/address.vue")
        },{
          path:"/city",
          name:"City",
          component:()=>import("./views/city.vue")
        }
      ],
      component: () => import("./views/index.vue")
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import("./views/Login.vue")
    }
  ]
})
//路由守卫
router.beforeEach((to, from, next) => {
  const isLogin = localStorage.ele_login ? true : false;
  if(to.path=="/login"){
    next();
  }else{
    //是否在登录状态下
    isLogin?next():next("/login");
  }
})

export default router;