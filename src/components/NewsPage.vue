<template>
  <div id="news" class="news_page">
    <div class="title_common1">头条新闻</div>

    <input class="search_input" type="text" v-model="search" placeholder="输入关键字...">
    <div class="news_list">
      <router-link class="news_item" v-for="blog in filterBlog" :key="blog['id']" :to="'/item/'+blog['id']">
        <div class="title">
          {{ blog['title'] }}
        </div>
        <article class="description">{{ blog['content'] | snippet }}</article>
      </router-link>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
export default {
  created() {
    Axios.get("/posts.json").then(response => {
      let blogData = response.data;
      let blogs = [];
      for (let key in blogData) {
        blogs.push({
          id: key,
          ...blogData[key]
        });
      }
      this.blogs = blogs;
    });
  },
  data() {
    return {
      blogs: [],
      search: ""
    };
  },
  computed: {
    filterBlog() {
      return this.blogs.filter(blog => {
        return blog.title.match(this.search.toLowerCase());
      });
    }
  },
  filters: {
    toUppercase(value) {
      return value.toUpperCase();
    }
  },
  directives: {
    rainbow: {
      bind(el, binding, vnode) {
        el.style.color =
          "#" +
          Math.random()
            .toString(16)
            .slice(2, 8);
      }
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.title_common1 {
  height: 40px;
}

.news_page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 10px;
}
.news_list {
  display:flex;
  flex-wrap:wrap;
}
.news_item {
  width:100%;
  padding: 15px;
  margin: 20px 0 0;
  box-sizing: border-box;
  background: white;
  border: 1px solid #eee;
  border-radius: 3px;
  text-decoration: none;
}
.news_item a {
  text-decoration: none;
}
.news_item .title {
  font-size: 18px;
  color: #333;
}
.news_item:hover .title {
  text-decoration:underline;
}
.news_item .description {
  margin-top:10px;
  color: #666;
  font-size:14px;
  line-height:20px;
}
.search_input {
  border: 1px solid #eee;
  padding: 10px;
  width: 100%;
  box-sizing: border-box;
  outline:none;
  transition:ease-in-out 0.2s all;
}
.search_input:focus, .search_input:hover {
  background:#eee;
  color:#333;
}
</style>
