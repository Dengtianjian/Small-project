<style scoped>
.address {
  width: 100%;
  height: 100%;
  overflow: auto;
  box-sizing: border-box;
  padding-top: 45px;
}

.city-search {
  background-color: #fff;
  padding: 10px 20px;
  color: #333;
}

.search {
  background-color: #eee;
  height: 40px;
  border-radius: 10px;
  box-sizing: border-box;
  line-height: 40px;
}
.search .city {
  padding: 0 10px;
}
.city i {
  margin-right: 10px;
}
.search input {
  margin-left: 5px;
  background-color: #eee;
  outline: none;
  border: none;
}

.area {
  margin-top: 16px;
  background: #fff;
}
.area li {
  border-bottom: 1px solid #eee;
  padding: 8px 16px;
  color: #aaa;
}
.area li h4 {
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}
.location {
  margin-top:10px;
}
</style>

<template>
  <div class="address">
    <Header isLeft="true" title="选择收获地址" />
    <div class="city-search">
      <div class="search">
        <span class="city" @click="$router.push('/city')">
          {{ city }}
          <i class="fa fa-angle-down"></i>
        </span>
        <i class="fa fa-search"></i>
        <input type="text" v-model="searchValue" placeholder="小区、写字楼、学校" />
      </div>
      <Location :address="address" />
    </div>
    <div class="area">
      <ul class="area-list" v-for="(item,index) in areaList" :key="index">
        <li @click="selectAddress(item)">
          <h4>{{ item['name'] }}</h4>
          <p>
            {{ item['district'] }}
            <span v-show="item['address'].length>0">{{ item['address'] }}</span>
          </p>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Header from "../components/header";
import Location from "../components/Location";
export default {
  data() {
    return {
      city: "北京市",
      searchValue: "",
      areaList: []
    };
  },
  components: {
    Header,
    Location
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.city = to.params.city;
    });
  },
  computed: {
    address() {
      return this.$store.getters.location.formattedAddress;
    }
  },
  watch: {
    searchValue() {
      this.searchPlace();
    }
  },
  methods: {
    searchPlace() {
      const self = this;
      AMap.plugin("AMap.Autocomplete", function() {
        // 实例化Autocomplete
        var autoOptions = {
          //city 限定城市，默认全国
          city: self.city
        };
        var autoComplete = new AMap.Autocomplete(autoOptions);
        autoComplete.search(self.searchValue, function(status, result) {
          // 搜索成功时，result即是对应的匹配数据
          self.areaList = result.tips;
        });
      });
    },
    selectAddress(item) {
      this.$store.dispatch(
        "setAddress",
        item.district + item.address + item.name
      );
      this.$router.push("/home");
    }
  }
};
</script>