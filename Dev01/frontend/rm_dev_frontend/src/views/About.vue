<style>
.box {
  width: 100px;
  height: 100px;
  background: pink;
}
</style>


<template>
  <div class="about">
    <h1>This is an about page</h1>
    <div class="box">{{ username }}</div>
  </div>
</template>

<script>
export default { 
  created(){
    console.log("about");
  },
  data(){
    return {
      username:"admin"
    }
  }
 }
</script>

