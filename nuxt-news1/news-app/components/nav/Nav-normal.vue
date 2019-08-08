<style lang="less" scoped>
.com-container {
  display: grid;
  grid-template-columns:80px auto minmax(100px, max-content);
}
.nav-logo {
  margin:2px;
  width:68px;
  height:31px;
  background-image:url("@{assets}/images/home.png");
  background-size:230px 876px;
  background-position:0 0;
  background-repeat:no-repeat;
}
.nav-narmal {
  height: 35px;
  box-shadow: 0 1px 3px rgba(50, 50, 50, 0.1);
}
.nav-list {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 35px;
  font-size: 12px;
  .nav-item {
    margin:0 20px;
  }
  .nav-item-link {
    text-decoration-line:none;
    color:@mainFontColor;
  }
}
.other-entry {
  align-self: center;
  display: flex;
  justify-content: space-evenly;
  align-items: center;
}
.search-button {
  margin-right:18px;
  height:35px;
  line-height:35px;
  font-size:12px;
  color:@mainFontColor;
  cursor: pointer;
  .search-icon {
    position:relative;
    top:3px;
    display:inline-block;
    padding:7px;
    background-image:url("@{assets}/images/home.png");
    background-repeat:no-repeat;
    background-size:184.5px 759px;
    background-position:0 -45px;
  }
  .search-text {
    display:inline-block;
  }
}
.passport {
  display: flex;
  align-items: center;
  width: 118px;
  text-align: center;
  .passport-link {
    width:50%;
    height:35px;
    line-height:35px;
    text-decoration-line:none;
    color:white;
    font-size:12px;
    background: @mainColor;
    &:hover {
      background:@deepMainColor;
    }
  }
}
</style>

<template>
  <nav id="navNormalConponent" class="nav-narmal">
    <main class="page-width com-container">
      <div class="nav-logo"></div>
      <ul class="nav-list">
        <li v-for="navItem in navList" :key="navItem['id']" class="nav-item"><a class="nav-item-link" :href="navItem['link']" target="_blank" rel="noopener noreferrer">{{ navItem['text'] }}</a></li>
      </ul>
      <div class="other-entry">
        <div class="search-button">
          <span class="search-icon"></span>
          <span class="search-text">搜索</span>
        </div>
        <div class="passport">
          <a class="passport-link member-login" href="http://login">Login</a>
          <a class="passport-link member-register" href="http://login">Register</a>
        </div>
      </div>
    </main>
  </nav>
</template>

<script>
export default {
  name: 'NavNormal',
  data() {
    return {
      navList: [
        {
          id: 1,
          link:"https://www.cooocc.com",
          text: '首页'
        },
        {
          id: 2,
          link:"https://www.cooocc.com",
          text: '快讯'
        },
        {
          id: 3,
          link:"https://www.cooocc.com",
          text: '资讯'
        },
        {
          id: 4,
          link:"https://www.cooocc.com",
          text: '视频'
        },
        {
          id: 5,
          link:"https://www.cooocc.com",
          text: '专题'
        },
        {
          id: 6,
          link:"https://www.cooocc.com",
          text: '活动'
        }
      ]
    }
  }
}
</script>


