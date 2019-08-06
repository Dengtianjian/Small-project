import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import { Button,Layout,List,Row,Col,Breadcrumb,Menu,Icon,Dropdown,Avatar,Form,Upload,Modal,DatePicker,Tabs,Input,Cascader,Spin,notification,Switch,Checkbox,message } from 'ant-design-vue';
Vue.use(Checkbox)
Vue.use(Switch)
Vue.use(notification)
Vue.use(message)
Vue.use(Spin)
Vue.use(Button)
Vue.use(Input)
Vue.use(Cascader)
Vue.use(Layout)
Vue.use(List)
Vue.use(Row)
Vue.use(Col)
Vue.use(Breadcrumb)
Vue.use(Menu)
Vue.use(Icon)
Vue.use(Dropdown)
Vue.use(Avatar)
Vue.use(Form)
Vue.use(Upload)
Vue.use(Modal)
Vue.use(DatePicker)
Vue.use(Tabs)

Vue.prototype.$message = message;
Vue.prototype.$notification = notification;
Vue.prototype.$info = Modal.info;
Vue.prototype.$success = Modal.success;
Vue.prototype.$error = Modal.error;
Vue.prototype.$warning = Modal.warning;
Vue.prototype.$confirm = Modal.confirm;

Vue.prototype._G={
  APIURL:"http://devapi.rabbitmonth.com",
  STATICURL:"http://devapi.rabbitmonth.com/static/"
}

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
