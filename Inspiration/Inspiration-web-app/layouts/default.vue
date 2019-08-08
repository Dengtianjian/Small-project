<style lang="less" scoped>
.default-header {
  height:40px;
  box-shadow:0 1px 3px rgba(50,50,50,0.1);
  background:white;
}
.header-container {
  display: grid;
  grid-template-columns:41vw auto max-content;
  align-items:center;
  height:inherit;
}
@media (max-width:860px) {
  .header-container {
    grid-template-columns:max-content auto max-content;
    justify-content: space-between;
  }
}
.header-logo {
  padding:0 10px;
  color:#000;
  font-size:1.7vw;
  user-select:none;
}
.header-right {
  font-size:13px;
}
</style>

<template>
  <div>
    <header class="default-header">
      <main class="header-container page-width">
        <NavNormal />
        <div class="header-logo">灵感源泉</div>
        <div class="header-right">
          <a href="http://">Login</a>
        </div>
      </main>
    </header>
    <div class="default-container">
      <nuxt />
    </div>
  </div>
</template>

<script>
import "../assets/less/global.less";
import NavNormal from "../components/Navigator/NavNormal";
export default {
  components: {
    NavNormal
  }
};
</script>
