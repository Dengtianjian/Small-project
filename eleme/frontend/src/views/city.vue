<style scoped>
.city {
  width: 100%;
  height: 100%;
  overflow: auto;
  box-sizing: border-box;
  padding-top: 45px;
}
.search-wrap {
  position: fixed;
  top: 0;
  height: 45px;
  width: 100%;
  background: #fff;
  box-sizing: border-box;
  padding: 3px 16px;
  display: flex;
  justify-content: space-between;
}
.search {
  background-color: #eee;
  border-radius: 10px;
  line-height: 40px;
  width: 85%;
  box-sizing: border-box;
  padding: 0 16px;
}
.search input {
  background: #eee;
  outline: none;
  border: none;
  margin-left: 5px;
}
.search-wrap button {
  outline: none;
  color: #009eef;
  outline:none;
  border:none;
  background:none;
}

.city-wrapper {
  height:100%;
}

.location {
  background: #fff;
  padding: 8px 16px;
  box-sizing: border-box;
}

.search-list {
  background: #fff;
  padding: 5px 16px;
}
.search-list li {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
</style>

<template>
  <div class="city">
    <div class="search-wrap">
      <div class="search">
        <i class="fa fa-search"></i>
        <input type="text" placeholder="输入城市名称" v-model="cityValue">
      </div>
      <button @click="$router.go(-1)">取消</button>
    </div>
    <div class="city-wrapper">
      <div class="location">
        <Location :address="city"/>
      </div>
      <Alphatbet ref="allCity" :cityInfo="cityInfo" :keys="keys"/>
    </div>
  </div>
</template>

<script>
import Location from "../components/Location";
import Alphatbet from "../components/Alphatbet";
export default {
  created(){
    this.getCityInfo();
  },
  data(){
    return {
      cityValue:"",
      cityInfo:null,
      keys:[]
    }
  },
  components:{
    Location,
    Alphatbet
  },
  computed:{
    city(){
      return this.$store.getters.location.addressComponent.city || this.$store.getters.location.addressComponent.city.province;
    }
  },
  methods:{
    getCityInfo(){
      this.$axios.get("/api/posts/cities").then(response=>{
        this.cityInfo=response.data;
        this.keys=Object.keys(response.data);
        this.keys.pop();
        this.keys.sort();
        this.$nextTick(()=>{
          this.$refs.allCity.initScroll();
        })
      });
    }
  }
}
</script>