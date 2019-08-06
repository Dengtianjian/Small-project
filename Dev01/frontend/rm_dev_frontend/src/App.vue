
<style lang="less">
@import "~ant-design-vue/dist/antd.less";

/* 头部 */
@media screen and (max-width: 576px) {
  .header {
    padding: 0 10px;
  }
}
.header .logo {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 64px;
  background: rgba(255, 255, 255, 0.2);
  float: left;
}
.header .logo img {
  height: 80%;
}
.header .header_right_col {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  height: 64px;
}
.header_right_col .avatar {
  margin: 0 5px;
}
@media screen and (max-width: 576px) {
  .header_right_col .email {
    display: none;
  }
}
@media screen and (max-width: 768px) {
  .header_right_col .course_publish_link {
    display: none;
  }
}
.header_right_col .link {
  padding: 0 10px;
  margin: 0 2px;
}

/* 容器 */
.app_layout_container .app_layout_container_content {
  padding: 0 50px;
  margin: 24px 0 54px;
}
@media screen and (max-width: 576px) {
  .app_layout_container .app_layout_container_content {
    padding: 0 10px;
  }
}
</style>

<template>
  <div id="app">
    <router-view name="fullscreen_view" v-if="nav.fullScreen.includes(nav.current[0])"/>
    <a-layout
      id="components-layout-demo-top-side"
      class="app_layout_container"
      v-if="!nav.fullScreen.includes(nav.current[0])"
    >
      <a-layout-header
        class="header"
        :style="{ position:'sticky', zIndex: 1, width: '100%',background:'#fff' }"
      >
        <a-row>
          <a-col :xs="4" :sm="4" :md="4" :lg="4" :xl="4">
            <div class="logo">
              <img src="./assets/logo.png" alt="logo">
            </div>
          </a-col>
          <a-col :xs="8" :sm="8" :md="9" :lg="11" :xl="13">
            <a-menu
              v-model="nav['current']"
              mode="horizontal"
              :style="{ lineHeight: '64px' }"
              :selectedKeys="nav['current']"
            >
              <a-menu-item
                class="nav_menu"
                v-for="(navItem) in nav['links']"
                :key="navItem['name']"
                @click="changeNav(navItem['name'])"
              >
                <router-link :to="navItem['path']">{{ navItem['text'] }}</router-link>
              </a-menu-item>
            </a-menu>
          </a-col>
          <a-col class="header_right_col" :xs="10" :sm="10" :md="9" :lg="8" :xl="6" :offset="1">
            <template v-if="$store['state']['user']['group']==1">
              <router-link class="link course_publish_link" to="/course/publish">
                <a-icon type="course-camera"/>发布视频
              </router-link>
              <a-dropdown class="user" :trigger="['click']">
                <a class="ant-dropdown-link" href="#">
                  <a-avatar
                    class="avatar"
                    src="https://cn.vuejs.org/images/logo.png"
                    alt="Tianjian"
                    style="backgroundColor:#87d068"
                  >T</a-avatar>
                  <span class="email">{{ $store['state']['user']['info']['user_email'] }}</span>
                  <a-icon type="down"/>
                </a>
                <a-menu slot="overlay">
                  <a-menu-item key="0">
                    <a href>个人主页</a>
                  </a-menu-item>
                  <a-menu-divider/>
                  <a-menu-item key="99" @click="logout">退出</a-menu-item>
                </a-menu>
              </a-dropdown>
            </template>
            <template v-if="$store['state']['user']['group']==11">
              <router-link class="link login_link" to="/login">登录</router-link>
              <router-link class="link register_link" to="/register">注册</router-link>
            </template>
          </a-col>
        </a-row>
      </a-layout-header>

      <a-layout-content class="app_layout_container_content">
        <a-breadcrumb style="margin: 16px 0">
          <a-breadcrumb-item>Home</a-breadcrumb-item>
          <a-breadcrumb-item>List</a-breadcrumb-item>
        </a-breadcrumb>
        <div class="content" :style="{ background:'white' }">
          <router-view/>
        </div>
      </a-layout-content>
      <a-layout-footer :style="{ textAlign: 'center' }">Rabbitmonth ©2019 Created by Rabbitmonth</a-layout-footer>
    </a-layout>
  </div>
</template>


<script>
import RM_cookie from "./functions/cookie";
import RM_fetch from "./functions/fetch";
export default {
  created() {
    this.verifyuKey();

    // if (false && !RM_cookie.getCookies("aKey")) {
    //   RM_fetch.getDataJSON("apiv1/users/logreg/verify").then(apiData => {
    //     RM_cookie.setCookie([
    //       {
    //         key: "aKey",
    //         value: apiData["aKey"]
    //       }
    //     ]);
    //     this.$set(this.$root.user, "aKey", apiData["aKey"]);
    //   });
    // }

    this.nav.current = [this.$route.name];
  },
  data() {
    return {
      nav: {
        current: ["course"],
        fullScreen: ["user_login", "user_register"],
        links: [
          {
            name: "course",
            path: "/courses",
            text: "视频"
          }
        ]
      }
    };
  },
  methods: {
    changeNav(navName) {
      this["nav"]["current"] = [navName];
    },
    changeRoute() {
      this.changeNav(this.$route.name);
    },
    verifyuKey() {
      if (RM_cookie.getCookies("uKey")) {
        let uKey = RM_cookie.getCookies("uKey");
        RM_fetch.getDataJSON("/api/user/logreg/verifyAccount", {
          uKey
        })
          .then(apiData => {
            this.$message.success("✨重新验证登录成功，欢迎肥来！🎊");
            let userData = {
              info: apiData["data"],
              uKey: RM_cookie.getCookies("uKey")
            };
            this.$store.commit("loginSuccess", userData);
          })
          .catch(response => {
            this.$message.error("登录已过期，请重新登录！", 1);
            RM_cookie.deleteCookies(["uKey"]);
            this.$store.commit("loginExpire");
          });
      } else {
        this.$store.commit("loginExpire");
      }
    },
    logout() {
      if (this.$store.state.user.uKey || RM_cookie.getCookies("uKey")) {
        RM_fetch.postDataJSON("/api/user/logreg/logout", {
          uKey: this.$store.state.user.uKey
        }).then(apiData => {
          RM_cookie.deleteCookies(["uKey"]);
          this.$message.success("退出成功！👋");
        });
      }
      this.$store.commit("loginExpire");
    }
  },
  watch: {
    $route: "changeRoute"
  }
};
</script>
