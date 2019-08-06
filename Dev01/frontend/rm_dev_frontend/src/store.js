import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      isLogin: false,
      info: {
        user_groupid: 11
      },
      group: 11,
      uKey: null
    }
  },
  mutations: {
    loginSuccess(state, data) {
      state.user = {
        isLogin: true,
        ...data,
        group: data['info']['user_groupid']
      };
    },
    loginExpire(state) {
      state.user = {
        isLogin: false,
        info: {
          user_groupid: 11
        },
        group: 11,
        uKey: null
      };
    }
  },
  actions: {

  }
})