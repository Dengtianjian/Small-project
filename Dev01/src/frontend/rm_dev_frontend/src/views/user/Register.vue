<style lang="less" scoped>
.register {
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
.register_form {
  padding: 30px;
  margin: 50px auto;
  width: 400px;
  height: auto;
  border: 1px solid #eee;
  box-shadow: 0 3px 15px rgba(50, 50, 50, 0.2);
  border-radius: 5px;
  background: white;
}
@media screen and (max-width: 375px) {
  .register_form {
    padding: 15px;
    width: 80%;
    height: 50%;
  }
}
@media screen and (max-width: 320px) {
  .register_form {
    height: 70%;
  }
}
.register_form .form_title {
  width: 100%;
  height: 60px;
  font-size: 24px;
  text-align: center;
}
</style>


<template>
  <div id="register_page" class="register">
    <link rel="stylesheet" href="/pages/user/logreg.css">
    <div class="header">
      <a-row>
        <a-col class="left_col" :span="12">
          <router-link to="/">返回首页</router-link>
        </a-col>
        <a-col :span="12"></a-col>
      </a-row>
    </div>
    <div class="register_form">
      <div class="form_title">注册</div>
      <a-form :form="form" @submit="handleSubmit">
        <a-form-item label="📫邮箱地址">
          <a-input
            v-decorator="[
    'email',
    {
      rules: [{
        type: 'email', message: '请输入正确的邮箱地址',
      }, {
        required: true, message: '请输入邮箱地址',
      }]
    }
  ]"
          />
        </a-form-item>
        <a-form-item label="🔐密码">
          <a-input
            type="password"
            v-decorator="[
    'password',
    {
      rules: [{
        required: true, message: '请输入密码',
      },{
        min:6,message:'密码最短长度为6'
      }]
    }
  ]"
          />
        </a-form-item>
        <a-form-item style="width:100%">
          <a-button type="primary" html-type="submit">注册</a-button>
          <span :style="{float:'right'}">
            Or
            <router-link to="/login">登录</router-link>
          </span>
        </a-form-item>
      </a-form>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch.js";
export default {
  beforeCreate() {
    this.form = this.$form.createForm(this);
  },
  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          RM_fetch.postDataJSON("/api/user/logreg/register", values)
            .then(apiData => {
              this.$message.success("注册成功！欢迎你加入我们！");
              this.$router.push({ name: "login" });
            })
            .catch(response => {
              let message = response.reason;
              switch (message.code) {
                case 1:
                  this.$message.warning("请输入密码或邮箱");
                  break;
                case 2:
                  this.$message.warning(
                    "密码最少6个字符，最长12个字符，必须包含小写字母以及数字，可增加大写字母或者特殊符号增加强度"
                  );
                  break;
                case 3:
                  this.$message.warning("请输入正确的邮箱地址");
                case 4:
                  this.$message.error("邮箱已存在");
                  break;
                default:
                  this.$message.error("注册失败，请刷新页面后重试");
              }
            });
        }
      });
    }
  }
};
</script>

