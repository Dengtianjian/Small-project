<template>
  <div id="header">
    <nav class="navigation">
      <router-link to="/" exact>新闻</router-link>
      <router-link to="/publish" exact>发布</router-link>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.navigation {
  width:100%;
  height:60px;
  display:flex;
  align-items:center;
  justify-content: center;
  background:white;
  box-shadow:0 3px 5px rgba(0,0,0,0.2);
}
.navigation a {
  height:inherit;
  line-height:60px;
  padding:0 20px;
  text-decoration:none;
  color:#999;
  transition:all 0.5s ease-in-out;
}
.navigation a:hover {
  color:#333;
  background:none;
}
.router-link-active {
  color:#333!important;
}
</style>
