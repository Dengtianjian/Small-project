// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VurResource from "vue-resource";
import VueRouter from "vue-router";
import routes from "./routes";

import Axios from "axios";

//axios 全局配置
Axios.defaults.baseURL="https://blogvue2.firebaseio.com";
Axios.defaults.headers.common['Authorization']='Token';

import App from './App'

Vue.use(VurResource);
Vue.use(VueRouter);

//自定义指令
// Vue.directive('rainbow',{
//   bind(el,binding,vnode){
//     el.style.color="#"+Math.random().toString(16).slice(2,8);
//   }
// });

Vue.directive('theme',{
  bind(el,binding,vnode){
    if(binding.value=='wide'){
      el.style.maxWidth="1260px";
    }else if(binding.value=='narrow'){
      el.style.maxWidth="560px";
    }

    if(binding.arg=="column"){
      el.style.background="#6767cc";
      el.style.padding="5px";
    }

  }
});

// Vue.filter("to-uppercase",function(value){
//   return value.toUpperCase();
// });

Vue.filter("snippet",function(value){
  return value.slice(0,100)+"...";
});

Vue.config.productionTip = false

const router=new VueRouter({
  routes
});

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
