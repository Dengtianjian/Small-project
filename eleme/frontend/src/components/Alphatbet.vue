<style scoped>
.area {
  margin-top: 10px;
  box-sizing: border-box;
  padding: 0 16px;
  background: #fff;
  height: calc(100% - 65px);
  overflow: hidden;
}
.scroll-wrap {
  background: #fff;
  overflow: auto;
}
.title {
  color: #aaa;
  padding: 15px 0;
}
.hot-city {
  padding: 0 16px;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
}
.hot-city li {
  width: 30%;
  background: #f1f1f1;
  margin: 0 10px 10px 0;
  text-align: center;
  padding: 10px;
  box-sizing: border-box;
}
.city-content li {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
.area-keys {
  position: fixed;
  right: 0;
  top: 25%;
  color: #aaa;
  font-size: 12px;
  line-height: 1.4;
  text-align: center;
  padding: 0 5px;
}
</style>

<template>
  <div class="area" ref="areaScroll" v-if="cityInfo">
    <div class="scroll-wrap">
      <div class="hot-wrap">
        <div class="title">热门城市</div>
        <ul class="hot-city city-list">
          <li v-for="(item,index) in cityInfo['hotCities']" :key="index">{{ item['name'] }}</li>
        </ul>
      </div>
      <!--[ 所有城市 ]-->
      <div class="city-wrap">
        <div class="city-content city-list" v-for="(item,index) in keys" :key="index">
          <div class="title">{{ item }}</div>
          <ul>
            <li v-for="(city,cityIndex) in cityInfo[item]" :key="cityIndex">{{ city['name'] }}</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="area-keys">
      <ul>
        <li @click="selectKey(0)">#</li>
        <li @click="selectKey(index+1)" v-for="(item,index) in keys" :key="index">{{ item }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
import BScroll from "better-scroll";
export default {
  props: {
    cityInfo: Object,
    keys: Array
  },
  data() {
    return {
      scroll: null
    };
  },
  methods: {
    initScroll() {
      console.log(this.$refs.areaScroll);
      this.scroll = new BScroll(this.$refs.areaScroll);
      this.scroll.refresh();
    },
    selectKey(key) {
      const cityList=this.$refs.areaScroll.getElementsByClassName("city-list");
      let el=cityList[key];
      
      console.log(this.scroll);
      this.scroll.scrollToElement(el,250);
    }
  }
};
</script>