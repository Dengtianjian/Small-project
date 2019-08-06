<template>
  <div id="app">
    <blog-header></blog-header>
    <div class="view_wrapper">
      <router-view ></router-view>
    </div>
  </div>
</template>

<script>
import blogHeader from "./components/header";
export default {
  name: 'App',
  components: {
    blogHeader
  }
}
</script>

<style>
body {
  padding:0;
  margin:0;
}

input[type='text'],textarea,select {
  border: 1px solid #eee;
  padding: 10px;
  width: 100%;
  box-sizing: border-box;
  outline:none;
  transition:ease-in-out 0.2s all;
}

.view_wrapper {
  padding-bottom:20px;
  margin-top:30px;
}

/* 标题 */
/* 标题 - 常规1 */
.title_common1 {
  font-size:18px;
}

</style>
