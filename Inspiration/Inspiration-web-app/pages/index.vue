<style lang="less">
.index-page {
  margin:20px 0 40px;
}
</style>


<template>
  <div id="IndexPage" class="index-page">
    <div class="page-width">
      <div class="feature-work-list">
        <WorkListNormal openMode="pop-ups" @loadWork="load"/>
      </div>
    </div>
  </div>
</template>

<script>
import WorkListNormal from "../components/WorkShow/WorkListNormal";
export default {
  components:{
    WorkListNormal
  },
  methods:{
    load(){
      
    }
  }
}
</script>
