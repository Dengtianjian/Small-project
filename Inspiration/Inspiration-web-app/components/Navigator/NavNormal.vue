<style lang="less" scoped>
.nav-list {
  display: flex;
  align-items: center;
}
.nav-item {
  margin: 0 20px;
  &:first-child {
    margin-left: 0;
  }
}
.nav-item-link {
  text-decoration-line: none;
  font-size: 13px;
  color: @mainFontColor;
  &.c {
    color: @highLightFontColor;
  }
}

.show-nav-button {
  display: none;
}
@media (max-width: 860px) {
  .show-nav-button {
    display: block;
    .icon {
      width: 20px;
      fill: #707070;
    }
  }
  .nav-list {
    // display:none;
    position: absolute;
    top: 40px;
    left: 0;
    z-index: 2;
    justify-content: flex-start;
    align-items: flex-start;
    flex-direction: column;
    width: 100%;
    height: 93.7vh;
    background: white;
    // box-shadow:0 5px 10px rgba(50,50,50,0.2);
    box-sizing: border-box;
  }
  .nav-item {
    margin: 0;
    width: 100%;
    border-bottom: 1px solid #eee;
  }
  .nav-item-link {
    display: block;
    min-width: 80px;
    padding: 10px 20px;
    font-size: 16px;
    color: #999;
  }
}
</style>

<template>
  <div id="navNromalComponent" class="nav-normal">
    <div class="show-nav-button" :class="{'open':isShow}" @click="isShow=!isShow">
      <svg
        t="1565249167729"
        class="icon"
        viewBox="0 0 1280 1024"
        version="1.1"
        xmlns="http://www.w3.org/2000/svg"
        p-id="8137"
      >
        <path
          d="M0.00512 96.00416A93.439066 93.439066 0 0 1 28.164838 28.164838 93.439066 93.439066 0 0 1 96.00416 0.00512h575.99424a93.439066 93.439066 0 0 1 67.839322 28.159718A93.439066 93.439066 0 0 1 767.99744 96.00416a93.439066 93.439066 0 0 1-28.159718 67.839322A93.439066 93.439066 0 0 1 671.9984 192.0032h-575.99424a93.439066 93.439066 0 0 1-67.839322-28.159718A93.439066 93.439066 0 0 1 0.00512 96.00416zM0.00512 512a93.439066 93.439066 0 0 1 28.159718-67.839322A93.439066 93.439066 0 0 1 96.00416 416.00096h1087.98912a95.99904 95.99904 0 0 1 67.839322 163.838362 93.439066 93.439066 0 0 1-67.839322 28.159718h-1087.98912a93.439066 93.439066 0 0 1-67.839322-28.159718A93.439066 93.439066 0 0 1 0.00512 512zM0.00512 927.99584a93.439066 93.439066 0 0 1 28.159718-67.839322A93.439066 93.439066 0 0 1 96.00416 831.9968h1087.98912a95.99904 95.99904 0 0 1 0 191.99808h-1087.98912a93.439066 93.439066 0 0 1-67.839322-28.159718A93.439066 93.439066 0 0 1 0.00512 927.99584z"
          p-id="8138"
        />
      </svg>
    </div>
    <transition name="fade-in-out">
      <ul class="nav-list" v-show="isShow">
        <li class="nav-item">
          <nuxt-link class="nav-item-link c" to="/">Home</nuxt-link>
        </li>
        <li class="nav-item">
          <nuxt-link class="nav-item-link " to="/work/publish">Publish work</nuxt-link>
        </li>
      </ul>
    </transition>
  </div>
</template>

<script>
export default {
  name: "NavNormal",
  mounted() {
    if (window.innerWidth > 860 && this.isShow == false) {
      this.isShow = true;
    } else if (window.innerWidth < 860 && this.isShow == true) {
      this.isShow = false;
    }
    window.addEventListener("resize", () => {
      if (window.innerWidth > 860 && this.isShow == false) {
        this.isShow = true;
      }
      if (window.innerWidth < 860 && this.isShow == true) {
        this.isShow = false;
      }
    });
  },
  data() {
    return {
      isShow: false
    };
  }
};
</script>


