<style lang="less" scoped>
.login {
  width: 100vw;
  height: 100vh;
  user-select: none;
  background: linear-gradient(45deg, #1890ff, #4099ff);
}
.header {
  height: 60px;
  line-height: 60px;
  width: 100%;
}
.header .left_col {
  margin-left: 100px;
}
.header .left_col a {
  text-decoration: none;
  color: rgba(255, 255, 255, 0.8);
}
.header .left_col a:hover {
  color: white;
}
.login_from {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 30px;
  margin: auto;
  width: 320px;
  height: 350px;
  border: 1px solid #eee;
  box-shadow: 0 3px 15px rgba(50, 50, 50, 0.2);
  border-radius: 5px;
  background: white;
}
@media screen and (max-width: 320px) {
  .login_from {
    padding: 15px;
    width: 80%;
    height: 70%;
  }
}
.login_from .form_title {
  width: 100%;
  height: 60px;
  font-size: 24px;
  text-align: center;
}
#components-form-demo-normal-login .login-form {
  max-width: 300px;
}
#components-form-demo-normal-login .login-form-forgot {
  float: right;
}
#components-form-demo-normal-login .login-form-button {
  width: 100%;
}
</style>

<template>
  <div id="login_page" class="login">
    <link rel="stylesheet" href="/pages/user/logreg.css">
    <div class="header">
      <a-row>
        <a-col class="left_col" :span="12">
          <router-link to="/">返回首页</router-link>
        </a-col>
        <a-col :span="12"></a-col>
      </a-row>
    </div>
    <div class="login_from">
      <div class="form_title">登录</div>
      <a-form
        id="components-form-demo-normal-login"
        :form="form"
        class="login-form"
        @submit="login"
      >
        <a-form-item>
          <a-input
            v-decorator="[
      'email',
      { rules: [{ required: true, message: '请输入邮箱地址!' },{type:'email',message:'请输入正确的邮箱地址'}] }
    ]"
            placeholder="邮箱"
          >
            <a-icon slot="prefix" type="user" style="color: rgba(0,0,0,.25)"/>
          </a-input>
        </a-form-item>
        <a-form-item>
          <a-input
            v-decorator="[
      'password',
      { rules: [{ required: true, message: '请输入密码!' }] }
    ]"
            type="password"
            placeholder="密码"
          >
            <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)"/>
          </a-input>
        </a-form-item>
        <a-form-item>
          <a-checkbox
            v-decorator="[
      'remember',
      {
        valuePropName: 'checked',
        initialValue: true,
      }
    ]"
          >记住我👌！</a-checkbox>
          <a class="login-form-forgot" href>忘记密码😥</a>
          <a-button type="primary" html-type="submit" class="login-form-button">登录</a-button>Or
          <router-link to="/register">注册!</router-link>
        </a-form-item>
      </a-form>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch.js";
import RM_cookie from "../../functions/cookie.js";

export default {
  beforeCreate() {
    this.form = this.$form.createForm(this);
  },
  methods: {
    login(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          RM_fetch.postDataJSON("/api/user/logreg/userLogin", values)
            .then(response => {
              let responseData = response["data"];
              this.$message.success("登录成功，欢迎回来");
              this.$store.commit("loginSuccess",responseData);
              RM_cookie.setCookie([
                {
                  key: "uKey",
                  value: responseData["uKey"],
                  expires: values.remember ? 7 : 0
                }
              ]);
              this.$router.push({ path: "/" });
            })
            .catch(response => {
              return;
              switch (response.reason.code) {
                case 1:
                  this.$message.error("密码或者邮箱地址错误。");
                  break;
                case 2:
                  this.$message.warning("请输入邮箱地址或者密码");
                  break;
                default:
                  this.$message.warning("登录失败，请稍后再试");
              }
            });
        }
      });
    }
  }
};
</script>
