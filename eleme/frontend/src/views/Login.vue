<style scoped>
.login {
  width: 100%;
  height: 100%;
  padding: 30px;
  box-sizing: border-box;
  background: #fff;
}

.logo {
  text-align: center;
}
.logo img {
  width: 150px;
}
.text-group,
.login-des,
.login-btn {
  margin-top: 20px;
}
.login-des {
  color: #aaa;
  line-height: 22px;
}
.login-des span {
  color: #4d90fe;
}
.login-btn button {
  width: 100%;
  height: 40px;
  background-color: #48ca38;
  border-radius: 4px;
  color: white;
  font-size: 14px;
  border: none;
  outline: none;
}
.login-btn button[disabled] {
  background-color: #8bda81;
}
</style>

<template>
  <div class="login">
    <div class="logo">
      <img class="logo-img" src="../assets/logo.png" alt srcset />
    </div>
    <InputGroup
      type="number"
      v-model="phone"
      placeholder="手机号"
      :btnTitle="btnTitle"
      :disabled="disabled"
      :error="errors.phone"
      @btnClick="getVerifyCode"
    />
    <InputGroup type="number" v-model="verifyCode" placeholder="验证码" :error="errors.code" />
    <div class="login-des">
      <p>
        新用户登录即自动注册，表示已同意
        <span>《用户服务协议》</span>
      </p>
    </div>
    <div class="login-btn">
      <button :disabled="isClick" @click="login">登录</button>
    </div>
  </div>
</template>

<script>
import InputGroup from "../components/inputGroup";
export default {
  data() {
    return {
      phone: "",
      verifyCode: "",
      errors: {},
      btnTitle: "获取验证码",
      disabled: false
    };
  },
  components: {
    InputGroup
  },
  methods: {
    getVerifyCode() {
      if (this.validatePhone()) {
        //获取验证码
        this.validateBtn();
        this.$axios
          .post("/api/posts/sms_send", {
            tpl_id: "178966",
            key: "293dcae7fe05a8ff71e7268426da315f",
            phone: this.phone
          })
          .then(response => {
            console.log(response);
          });
      }
    },
    validateBtn() {
      let time = 60;
      let timer = setInterval(() => {
        if (time == 0) {
          clearInterval(timer);
          this.btnTitle = "获取验证码";
          this.disabled = false;
        } else {
          this.btnTitle = time + "秒后重试";
          this.disabled = true;
          time--;
        }
      }, 1000);
    },
    validatePhone() {
      //验证手机号码
      if (!this.phone) {
        this.errors = {
          phone: "手机号码不能为空"
        };
        return false;
      } else if (!/^1[345678]\d{9}$/.test(this.phone)) {
        this.errors = {
          phone: "手机号码不正确"
        };
        return false;
      } else {
        this.errors = {};
        return true;
      }
    },
    login() {
      this.errors = {};
      this.$axios.post("/api/posts/sms_back",{
        phone:this.phone,
        code:this.verifyCode
      }).then(response=>{
        console.log(response);
        localStorage.setItem("ele_login",true);
        this.$router.push("/");
      }).catch(error=>{
        this.errors={
          code:error.response.data.msg
        }
      })
    }
  },
  computed: {
    isClick() {
      if (!this.phone || !this.verifyCode) {
        return true;
      }
      return false;
    }
  }
};
</script>