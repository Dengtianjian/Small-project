<template>
  <div id="news_item_page" class="news_item">
    <div class="title">{{ blog['title'] }}</div>
    <div class="info">
      <div class="info_item">
        <div class="info_title">
          作者：
        </div>
        <div class="info_content">
          {{ blog['author'] }}
        </div>
      </div>
      <div class="info_item catagory">
        <div class="info_title">
          类别：
        </div>
        <div class="info_content">
          <div class="catagory_item" v-for="catagory in blog['categories']" :key="catagory">{{ catagory }}</div>
        </div>
      </div>
    </div>
    <article class="content">{{ blog['content'] }}</article>
    <div class="operate">
      <div class="operate_item" @click="deleteBlog">删除</div>
      <router-link class="operate_item" :to="'/publish/'+id">编辑</router-link>
    </div>
  </div>
</template>

<script>
import Axios from 'axios';
export default {
  created() {
    Axios
      .get(`/posts/${this.id}/.json`)
      .then(response => {
        this.blog = response.data;
      });
  },
  data() {
    return {
      id: this.$route.params.id,
      blog: {
        title: null,
        content: null
      }
    };
  },
  filters: {},
  methods:{
    deleteBlog(){
      Axios.delete(`/posts/${this.id}/.json`).then(response=>{
        this.$router.push({path:"/"});
      })
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.news_item {
  max-width: 960px;
  margin: 0 auto;
  padding:10px 20px;
}
.news_item .title {
  color: #666;
  font-size:22px;
}

.info {
  display:flex;
  flex-direction:column;
  margin:10px 0;
}
.info .info_item {
  display:flex;
  margin:3px 0;
  font-size:12px;
  color:#999;
}
.info_item .catagory {
  margin:10px 0;
  display:flex;
}
.info_item .info_content {
  display:flex;
}
.catagory .catagory_item {
  margin-right:10px;
}

.operate {
  display:flex;
  margin-top:30px;
}
.operate .operate_item {
  padding:5px 10px;
  margin-right:10px;
  font-size:14px;
  color:#666;
  text-decoration:none;
  background:#eee;
  cursor:pointer;
}
.operate .operate_item:hover {
  color:#333;
}

.content {
  line-height:26px;
  font-size:16px;
  color:#333;
  white-space: pre-wrap;
}
</style>
