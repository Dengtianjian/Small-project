<style lang="less" scoped>
.work-list-normal {
  position: relative;
}
.work-list {
  display: grid;
  grid-template-columns: repeat(4, 24%);
  grid-row-gap: 20px;
  justify-content: space-between;
}
@media (max-width: 930px) {
  .work-list {
    grid-template-columns: repeat(3, 33%);
  }
}
@media (max-width: 620px) {
  .work-list {
    grid-template-columns: repeat(2, 54%);
  }
}
@media (max-width: 420px) {
  .work-list {
    grid-template-columns: 100%;
  }
}
.work-item {
  justify-content: center;
  padding: 10px;
  text-decoration-line: none;
  text-align: center;
  background: white;
  box-sizing: border-box;
  border-radius: 5px;
  user-select: none;
  &:hover {
    box-shadow: 0 10px 25px -16px rgba(0, 36, 100, 0.3);
    .work-cover-img {
      opacity: 0.8;
    }
  }
}
.work-item-cover {
  width: 100%;
  background: black;
}
.work-cover-img {
  position: relative;
  z-index: 0;
  width: 100%;
  height: 100%;
}
.work-item-info {
  margin-top: 20px;
}
.work-name {
  color: @mainFontColor;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}

//展示作品弹窗
.show-work-pop-ups {
  .pop-ups-mark {
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    text-align: center;
    background: rgba(0, 0, 0, 0.945);
    .loading {
      width: 70px;
    }
  }
  .work-content-wrapper {
    position: absolute;
    z-index: 2;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width: 80vw;
  }
  .work-content {
    margin: 20px 0;
    padding: 20px;
    min-height: 100vh;
    background: white;
    img {
      width: 100%;
    }
    p {
      margin: 5px 0;
    }
  }
}
.work-load {
  margin: 20px 0;
  width: 100%;
}
.work-load-button {
  margin: 0 auto;
  width: 100px;
  height: 30px;
  line-height: 30px;
  text-align: center;
  color: @mainColor;
  border: 2px solid @mainColor;
  border-radius: 3px;
  cursor: pointer;
  user-select: none;
}
</style>

<template>
  <div id="WorkListNormal" class="work-list-normal">
    <div class="show-work-pop-ups" v-show="isShowWork">
      <transition name="fade-in-out">
        <div class="work-content-wrapper" v-show="loadingEnd">
          <div class="work-content">
            <p>
              <span style="font-size:16px">新一期同学们的作品，互联网运营设计产出，整合成合集</span>
              <img alt src="http://img.uigreat.com/Fsxtm-xNQU5noGO5nUumf5iFpdx5" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FqPSaKoSls82eFPlSqW02DzAHdaJ" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fmj7Tdos6wB7X7fWGG3nPnPtoy0q" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FoRFHJWwH3W4Ion7FHmSSKmFeeQj" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fk5dVyGyp3Md2bv96kurVfOBtdoX" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fqf6N9v3U7OxUerwaHlDL8g8uOV_" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FsHKnV8AhICGXMMaTvNsIRmSlqXV" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FrYOmlw5wT1nb1RRUFz7HXh3FiAU" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FtkW_Ui2UL3nGzKyHKVnri1tlcRl" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FpLVBZTIYJrQCZXJlMrGI-Bqd2km" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FiA8AJpQF9eGB2QiM1vAv-1W8kll" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fj-j7vJNmHl-ZWUrT8D21NjQC57e" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FnNrR5pLUxqQxkVqa_Y7T9BSYeQX" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FuHL1YwAENsyP4_-aY6PZzi27SOJ" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FgeiGBJcmrmT03IsAGtZG5WBd7Dh" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Ft4vg3qI4KGaRpSBItmmSuINfi2L" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FlkxqMyyfsCAK5iSkb1sOomeucCA" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fq6yPAKLI-AgirmwWUYVF0-M9K54" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FuOoY83TCv1HeHbqAZD5hJtktBIk" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fu-hz4a4xD4Q6VvL5ANiABFE1jZq" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FtEk3p8lpitktub_u0fpgwbarfzS" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FjbhQnFIFm9ud1Qrdc2D3IiAyvCT" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fu9SSwIRpJ5H8qUURPz8cnYz58UF" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FtOR_fkkzlgJ9twZ68PS61aBQBSf" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FhemZJY56qpqE9b21W296GFV8ge3" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FnRrZi1ecu-AduoL2N-vtOGs82-1" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FrKHn1QZrpVWdy7360hblJDJqAzh" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FibgAbKKL2yWnr8u2tlyfNeJsci5" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FsdL9vXVplvYo6mZ2QyiNn3z5S8Y" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FonDYY5R8Bo8ClZgdROYH8xBg8GG" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fukke16a0PtV-8QTjJoEHAHW8aHA" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FsSxTM-dtyZ4TA8ErVagDeAS-W1B" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fj7ZCprWXZbywlvzC2VGfLn-Nttf" />
            </p>

            <p>&nbsp;</p>

            <p>&nbsp;</p>

            <p>
              <img alt src="http://img.uigreat.com/FlhD9rmW68m-X1QEmxYDKtkKDZWf" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fm4-qoV3s87s-4zRg150u2M5zxfj" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FuyegcfH9iCaH6mDP-0tJkrgvnzZ" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FlEz4kogPNnzO-X-a8zIqbQV8id7" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fv7o7-8kolPiDjz8GL-O7vJpk9o6" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FhgBtm-0UeUUlqqGkxxvhqzdHLs-" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fv5kIu0Mo7URSeHPuylu-PBbQ6FT" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FqYb6ixuQQN6TYomSxjkHeX-46RN" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FutP6y92YLEpNpSot-K62Wklfe6O" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FjSu8hdZVuh54PQHzwbU8_b9wq9x" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FrPLcliUJDC46vIIguksqOXSfH4S" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FjXwO8tZX6FBujxVqSelhQ9Vgp0p" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fsx7D9850LUu59jLlKOX2c8ud92h" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FrHBgCvSL6Jd5et2MOtfzVd3ZybU" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FqU2SdJxokUycL92y77tAKi74yqq" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FmOAcfEQpCbZLc51mChgpYzfZ1Ox" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FlBsWe4jZbltnrilcbVRCLVuKDbE" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/FuIf11eH5o-F15ldVnNgmuqym3HO" />
            </p>

            <p>
              <img alt src="http://img.uigreat.com/Fmi4-SNWn9wq_5GSzR8G-ZVXgKSm" />
            </p>
          </div>
        </div>
      </transition>
      <div class="pop-ups-mark" @click="hiddenShowPopUps">
        <img src="~/assets/svg/Infinity-loading.svg" class="loading" v-show="isLoading" />
      </div>
    </div>
    <div class="work-list" :style="{position:isFixed}">
      <a
        :href="'/work/'+workItem['id']"
        class="work-item"
        v-for="workItem in works"
        :key="workItem['id']"
        @click.prevent="showWork"
      >
        <div class="work-item-cover">
          <img :src="workItem['cover']" alt srcset class="work-cover-img" />
        </div>
        <div class="work-item-info">
          <div class="work-name">{{ workItem['name'] }}</div>
        </div>
      </a>
    </div>
    <div class="work-load">
      <div class="work-load-button" @click="loadWork">Loading</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "WorkListNormal",
  props: {
    openMode: {
      type: String,
      default: "pop-ups",
      required: true
    }
  },
  data() {
    return {
      isShowWork: false,
      isLoading: false,
      loadingEnd: false,
      works: [
        {
          id: 1,
          cover:
            "http://img.uigreat.com/FtxeojTHSXdTMSv-fdjAMSULNVtj?imageView2/1/w/536/h/410",
          name: "《布丁集2》—品牌运营设计汇总"
        },
        {
          id: 2,
          cover:
            "http://img.uigreat.com/FjEt9tlTXyP82NkwhvAb8-jyV-RV?imageView2/1/w/536/h/410",
          name: "#像素范儿#线下运营全能班一周产出"
        },
        {
          id: 3,
          cover:
            "http://img.uigreat.com/FmQa5EtdHEcGCaGCu621FVE_I3Aa?imageView2/1/w/536/h/410",
          name: "短视频app动效合集"
        },
        {
          id: 4,
          cover:
            "http://img.uigreat.com/FqkOSKISMFtrJ-k13BKrHGOP7-D1?imageView2/1/w/536/h/410",
          name: "《布丁集》—学生互联网品牌运营设"
        },
        {
          id: 5,
          cover:
            "http://img.uigreat.com/Fv29N-JccUj5kZRj0ciSQYA5LCKJ?imageView2/1/w/536/h/410",
          name: "#布丁集#—字体设计"
        }
      ]
    };
  },
  methods: {
    showWork() {
      this.isShowWork = true;
      this.isLoading = true;
      setTimeout(() => {
        this.loadingEnd = true;
        this.isLoading = false;
      }, 2000);
    },
    hiddenShowPopUps() {
      this.isShowWork = false;
      this.isLoading = false;
      this.loadingEnd = false;
    },
    loadWork() {
      this.works.push({
        id: this.works.length+1,
        cover:
          "http://img.uigreat.com/FrKsVhpRHD85W8vI1AhSs-2SKTkj?imageView2/1/w/536/h/410",
        name: "浅谈学习类运营banner设计技巧"
      },{
        id: this.works.length+2,
        cover:
          "http://img.uigreat.com/FvLpDFKRi0aABKHxVdzAq5PT9Smf?imageView2/1/w/536/h/410",
        name: "Motion Design Summary"
      },{
        id: this.works.length+3,
        cover:
          "http://img.uigreat.com/FhZDp42RyvCv6F3Gh18AuQtzyQ15?imageView2/1/w/536/h/410",
        name: "一顿操作猛如虎的动效合集"
      });
      this.$emit("loadWork");
    }
  },
  computed: {
    isFixed() {
      if (this.isShowWork) {
        return "fixed";
      }
      return "static";
    }
  }
};
</script>


