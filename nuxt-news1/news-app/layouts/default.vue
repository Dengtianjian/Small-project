
<style>

body {
  padding:0;
  margin:0;
}

ul {
  margin:0;
  padding:0;
  list-style-type: none;
}

.page-width {
  margin:0 auto;
  width:1150px;
}

.page-grid-two {
  display:grid;
  grid-template-columns:125px auto;
}
</style>

<template>
  <div id="defaultLayout">
    <NavNornal/>
    <div class="page-width page-grid-two">
      <div id="defaultLeftNav" class="left-nav">
        123456
      </div>
      <nuxt></nuxt>
    </div>
  </div>
</template>

<script>
import NavNornal from "../components/nav/Nav-normal";
export default {
  components:{
    NavNornal
  }
}
</script>
