<style lang="less" scoped>
/* 头部 */
.course_item_page .header {
  padding: 20px;
}
.course_item_page .header .name {
  font-size: 24px;
  color: #333;
}
.course_item_page .header .source_category {
  display: flex;
  align-items: center;
  margin: 20px 0 40px;
}
.course_item_page .header .source_category > div:first-child {
  margin-right: 10px;
}
.course_item_page .header .cover img {
  padding-right: 20px;
  width: 100%;
  max-height: 280px;
}
@media screen and (max-width: 767px) {
  .course_item_page .header .cover img {
    padding-right: 0;
  }
}
.course_item_page .header .introduction {
  white-space:pre-line;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}
@media screen and (max-width: 767px) {
  .course_item_page .header .introduction {
    margin-top: 10px;
  }
}
.course_item_page .header .details {
  margin-top: 10px;
}
.course_item_page .header .details .details_item {
  display: flex;
}
.course_item_page .header .operate_group {
  margin-top: 20px;
}
.course_item_page .header .operate_group .operate_item {
  margin-right: 10px;
}
/* 信息区 */
.info {
  padding: 20px;
}
/* 章节 */
.catalog_list {
  padding: 0 10px;
}
.catalog_list .catalog_item {
  margin-bottom: 10px;
}
.catalog_list .chapter_parent {
  display: flex;
  padding: 0 0 10px 0;
  font-size: 20px;
  color: #333;
  border-bottom: 1px solid #eee;
}
.catalog_list .child_item {
  display: flex;
  margin: 10px 0;
  padding: 0 0 10px 10px;
  border-bottom: 1px solid #eee;
}
@media screen and (max-width: 576px) {
  .catalog_list .chapter_parent {
    font-size: 3vw;
  }
  .catalog_list .child_item {
    font-size: 2.5vw;
  }
}
</style>

<template>
  <div id="course_item_page" class="course_item_page">
    <link rel="stylesheet" href="/pages/course/course_item.css">
    <div class="header" v-if="courseLoading==false">
      <div class="name">{{ course['course_name'] }}</div>
      <div class="source_category">
        <div class="source">
          <a target="_blank" :href="course['course_source_link']">查看来源</a>
        </div>
        <div class="category">分类：计算机</div>
      </div>
      <a-row>
        <a-col :xs="24" :sm="24" :md="10" :lg="10" :xl="10">
          <div class="cover">
            <img :src="_G['STATICURL']+course['course_cover']" alt srcset>
          </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="14" :lg="14" :xl="14">
          <div class="introduction">{{ course['course_introduction'] }}</div>
          <div class="details">
            <div class="details_item publish_date">
              <div class="details_name">发布日期：</div>
              <div class="details_content">{{ course['course_publish_date'] }}</div>
            </div>
          </div>
          <div class="operate_group">
            <a-button class="operate_item" type="primary">前往课程页</a-button>
            <a-button class="operate_item">收藏</a-button>
            <router-link
              :to="'/course/'+course['course_strid']+'/catalog'"
              
            >管理章节</router-link>
          </div>
        </a-col>
      </a-row>
    </div>
    <div class="info" v-if="catalogLoading==false">
      <a-tabs defaultActiveKey="1">
        <a-tab-pane key="1">
          <span slot="tab">
            <a-icon type="profile"/>介绍
          </span>
          <div class="description" v-html="course['course_description']"></div>
        </a-tab-pane>
        <a-tab-pane key="2">
          <span slot="tab">
            <a-icon type="read"/>目录
          </span>
          <div class="catalog">
            <div class="catalog_list">
              <div
                class="catalog_item"
                v-for="(catalogItem,catalogIndex) in catalog"
                :key="catalogItem['parent']['chapter_id']"
              >
                <div class="chapter_parent">
                  <div class="chapter_num">第{{ catalogIndex+1 }}章</div>
                  <div class="chapter_title">{{ catalogItem['parent']['chapter_name'] }}</div>
                </div>
                <div class="chapter_child">
                  <router-link
                    :to="'/course/'+course['course_strid']+'/'+subChapter['chapter_sid']"
                    class="child_item"
                    v-for="(subChapter,subIndex) in catalogItem['child']"
                    :key="subChapter['chapter_id']"
                  >
                    <div class="chapter_num">{{ catalogIndex+1 }}-{{ subIndex+1 }}</div>
                    <div class="chapter_title">{{ subChapter['chapter_name'] }}</div>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </a-tab-pane>
      </a-tabs>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch";
export default {
  created() {
    let courseSId = this.$route.params.courseSId;
    RM_fetch.getDataJSON("/api/course/course/getCourseBySId",{
      courseSId
    })
      .then(apiData => {
        this.course = apiData.data;
        let date = new Date(Number(this.course["course_publish_date"]));
        this.course[
          "course_publish_date"
        ] = `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
        this.courseLoading=false;
      })
      .catch(response => {
        switch (response.status) {
          case 400:
          case 403:
          case 404:
            this.$message.error("加载失败。");
            this.$router.replace({path:"/"});
            break;
        }
      });
      RM_fetch.getDataJSON("/api/course/catalog/getCatalogByCourseSId",{
        courseSId
      }).then(response=>{
        this.catalog=response.data;
        this.catalogLoading=false;
      });
  },
  data() {
    return {
      course: null,
      catalog:null,
      courseLoading:true,
      catalogLoading:true
    };
  }
}
</script>

