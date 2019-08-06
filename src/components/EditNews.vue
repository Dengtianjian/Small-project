<template>
  <div id="publish_news_page" class="publish_news">
    <div class="title_common1">发布新闻</div>
    <form class="publish_form" action>
      <div class="form_item">
        <div class="item_title">新闻标题</div>
        <input type="text" v-model="blog.title" required>
      </div>
      <div class="form_item">
        <div class="item_title">新闻内容</div>
        <textarea name id cols="30" rows="10" wrap="hard" v-model="blog.content"></textarea>
      </div>
      <div class="form_item catagory">
        <div class="item_title">新闻类型</div>
        <div class="options">
          <div class="option_item">
            <input id="vuejs" type="checkbox" value="Vue.js" v-model="blog.categories">
            <label for="vuejs">Vue.js</label>
          </div>
          <div class="option_item">
            <input id="nodejs" type="checkbox" value="Node.js" v-model="blog.categories">
            <label for="nodejs">Node.js</label>
          </div>
          <div class="option_item">
            <input id="raectjs" type="checkbox" value="React.js" v-model="blog.categories">
            <label for="raectjs">React.js</label>
          </div>
          <div class="option_item">
            <input id="angularjs" type="checkbox" value="Angular.js" v-model="blog.categories">
            <label for="angularjs">Angular.js</label>
          </div>
        </div>
      </div>
      <div class="form_item">
        <div class="item_title">作者</div>
        <select v-model="blog.author">
          <option :value="author" v-for="author in authors" :key="author">{{ author }}</option>
        </select>
      </div>
      <input type="button" @click.prevent="post" value="发布新闻">
    </form>
  </div>
</template>

<script>
import Axios from "axios";
export default {
  created() {
    tinymce.init({});
    if (this.$route.params.id) {
      Axios.get(`/posts/${this.$route.params.id}/.json`).then(response => {
        this.blog = response.data;
        this.$set(this.blog, "id", this.$route.params.id);
      });
    }
  },
  data() {
    return {
      blog: {
        id: null,
        title: "",
        content: "",
        categories: [],
        author: ""
      },
      authors: ["Andy", "Tina", "Tianjian"]
    };
  },
  methods: {
    post() {
      if (this.blog.id) {
        Axios.put(`/posts/${this.blog.id}/.json`, this.blog).then(response => {
          this.$router.push({
            path: `/item/${this.blog.id}`
          });
        });
      } else {
        Axios.post("/posts.json", this.blog).then(response => {
          this.$router.push({
            path: `/item/${response.data.name}`
          });
        });
      }
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.publish_news {
  margin: 10px auto;
  padding: 0 20px;
  width: auto;
  max-width: 800px;
  box-sizing: border-box;
}

.publish_form {
  margin: 20px 0;
}
.form_item {
  margin-top: 15px;
}
.form_item label {
  display: block;
}

.item_title {
  margin-bottom: 10px;
}

.catagory .options {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}
.catagory .option_item {
  display: flex;
  color: #666;
  font-size: 14px;
}

input[type="text"],
textarea,
select {
  display: block;
  width: 100%;
  padding: 8px;
  font-family: 微软雅黑;
  outline: none;
}
textarea {
  resize: vertical;
}

input[type="button"] {
  display: block;
  margin: 20px 0;
  padding: 10px 15px;
  background: coral;
  color: white;
  border: none;
  outline: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
