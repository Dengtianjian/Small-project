<style lang="less" scoped>
.course_list_wrapper {
  padding: 20px;
  .rm_title_common {
    height: 50px;
    font-size: 16px;
  }
  .course_item {
    .cover {
      height: 130px;
      overflow: hidden;
      img {
        width: 100%;
        height: 100%;
        border-radius: 5px;
        transition:filter 0.4s linear;
        &:hover {
          filter:brightness(0.7);
        }
      }
    }
    @media screen and (min-width: 1200px) {
      .cover {
        height: 150px;
      }
    }
    .title {
      display: -webkit-box;
      margin-top: 5px;
      width: 100%;
      height: 42px;
      font-size: 14px;
      text-overflow: ellipsis;
      overflow: hidden;
      -webkit-line-clamp: 2;
    }
  }
}
</style>

<template>
  <div id="courses_page" class="course_page">
    <div class="course_list_wrapper">
      <div class="rm_title_common">全部课程</div>
      <a-list
        class="course_list demo-loadmore-list"
        :loading="course['loading']"
        itemLayout="horizontal"
        :dataSource="course['list']"
        :grid="{ gutter: 50, xs: 1, sm: 2, md: 3, lg: 4, xl: 4, xxl: 8 }"
      >
        <div
          v-if="course['showLoadingMore']"
          slot="loadMore"
          :style="{ textAlign: 'center', marginTop: '12px', height: '32px', lineHeight: '32px' }"
        >
          <a-spin v-if="course['loadingMore']"/>
          <a-button v-else @click="onLoadMore()">加载更多</a-button>
        </div>
        <a-list-item slot="renderItem" slot-scope="courseItem" style="margin-bottom:10px;">
          <router-link class="course_item" :to="'/course/'+courseItem['course_strid']">
            <div class="cover">
              <img
                :src="_G['STATICURL']+courseItem['course_cover']"
                :alt="courseItem['course_name']"
              >
            </div>
            <div class="title">{{ courseItem['course_name'] }}</div>
          </router-link>
        </a-list-item>
      </a-list>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch";

export default {
  created() {
    this.getCourses()
      .then(apiData => {
        this.course.list = apiData;
      })
  },
  data() {
    return {
      course: {
        page: 0,
        limit: 12,
        loading: false,
        loadingMore: false,
        showLoadingMore: true,
        list: []
      }
    };
  },
  methods: {
    getCourses() {
      this.course.page++;
      this.course.loadingMore = true;
      this.course.loading = true;
      return RM_fetch.getDataJSON(`/api/course/course/getCoursesLimit`, {
          page: this.course.page * this.course.limit - this.course.limit,
          limit: this.course.limit,
          fields: "course_strid,course_name,course_cover",
          order: "course_publish_date,DESC"
        }).then(response=>{
          this.course.loadingMore = false;
          this.course.loading = false;
          return response;
        })
    },
    onLoadMore() {
      this.getCourses()
        .then(apiData => {
          this.course.loadingMore = false;
          this.course.loading = false;
          if (apiData == 0) {
            this.$message.warning("已获取了全部的课程了。", 2);
            this.course.page--;
            this.course.showLoadingMore = false;
            return;
          }
          this.$message.success("更新成功！", 2);
          this.course.list.push(...apiData);
        })
        .catch(Response => {
          this.course.page--;
          this.course.loadingMore = false;
          this.course.loading = false;
          this.$message.error("更新失败！网络错误📶", 2);
        });
    }
  }
};
</script>

