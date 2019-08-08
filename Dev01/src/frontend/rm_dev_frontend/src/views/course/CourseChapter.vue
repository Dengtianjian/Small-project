<style lang="less" scoped>
.course_chapter {
  padding: 20px;
}
.chapter_info {
  display: flex;
  margin-bottom: 20px;
  color: #333;
  font-size: 24px;
}
.chapter_info .chapter_num {
  padding-right: 5px;
}
.chapter_video {
  width: 100%;
  height: 478px;
  border-radius: 5px;
  outline: none;
}
@media screen and (max-width: 992px) {
  .chapter_video {
    height: auto;
  }
}
.chapter_video video {
  width: 100%;
}
/* 章节 */
.catalog_list_wrapper {
  height: 478px;
  padding: 10px 0;
  margin-left: 20px;
  overflow-y: scroll;
  background: #f1f1f1;
}
@media screen and (max-width: 992px) {
  .catalog_list_wrapper {
    margin:10px 0 0 0;
    height: auto;
  }
}
.catalog_list_wrapper .catalog_item {
  margin-top: 10px;
}
.catalog_list_wrapper .catalog_item.current {
  background: white;
}
.catalog_list_wrapper .chapter_parent {
  display: flex;
  padding: 5px 5px 7px 10px;
  font-size: 16px;
}
.catalog_list_wrapper .chapter_num {
  padding-right: 5px;
}
.catalog_list_wrapper .chapter_child {
  padding: 0 5px 0 0;
  cursor: pointer;
}

.catalog_list_wrapper .chapter_child .child_item {
  display: flex;
  padding: 5px 5px 5px 0;
  font-size: 14px;
  color: rgba(65, 162, 253, 0.8);
}
.catalog_list_wrapper .child_item.current,
.catalog_list_wrapper .child_item:hover {
  background: white;
  color: #1890ff;
}
.catalog_list_wrapper .chapter_child .child_item .chapter_num {
  padding-left: 15px;
}
</style>

<template>
  <div id="course_chapter_page" class="course_chapter">
    <div class="chapter_info">
      <div
        class="chapter_num"
      >第{{ chapter['chapter_parent_index']+1 }}章 {{ chapter['chapter_index']+1 }} </div>
      <div class="chapter_name">{{ chapter['chapter_name'] }}</div>
    </div>
    <a-row>
      <a-col :xs="24" :sm="24" :md="24" :lg="17" :xl="17">
        <div class="chapter_video">
          <video
            class="video"
            :src="videoURL"
            controls="controls"
          >抱歉，您的浏览器不支持HTML5 video标签，请升级浏览器后再重试。</video>
        </div>
      </a-col>
      <a-col :xs="24" :sm="24" :md="24" :lg="7" :xl="7">
        <div class="catalog_list_wrapper">
          <div class="catalog_list">
            <div
              class="catalog_item"
              v-for="(catalogItem,catalogIndex) in catalog"
              :key="catalogItem['parent']['chapter_id']"
            >
              <div class="chapter_parent">
                <div class="chapter_num">第 {{ catalogIndex+1 }} 章</div>
                <div class="chapter_title">{{ catalogItem['parent']['chapter_name'] }}</div>
              </div>
              <div class="chapter_child">
                <div
                  class="child_item"
                  :class="{ 'current':chapterSId==childChapter['chapter_sid'] }"
                  v-for="(childChapter,childIndex) in catalogItem['child']"
                  :key="childIndex"
                  @click.prevent="switchChapter(childChapter['chapter_sid'],catalogIndex,childIndex)"
                  replace
                >
                  <div class="chapter_num">{{ catalogIndex+1 }}-{{ childIndex+1 }}</div>
                  <div class="chapter_title">{{ childChapter['chapter_name'] }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a-col>
    </a-row>
  </div>
</template>

<script>
import RM_http from "../../functions/fetch.js";
export default {
  mounted() {
    this.courseSId = this.$route.params.courseSId;
    this.chapterID = this.$route.params.chapterSId;

    RM_http.getDataJSON("/api/course/catalog/getCatalogByCourseSId", {
      courseSId: this.courseSId
    }).then(response => {
      this.catalog = response.data;
      let chapter_parent_index = 0;
      let chapter_index = 0;
      this.catalog.forEach((item, catalogIndex) => {
        if (item["course_strid"] == this.courseSId) {
          chapter_parent_index = catalogIndex;
          item["child"].forEach(chapter, chapterIndex => {
            if (chapter["chapter_sid"] == this.chapterID) {
              chapter_index = chapterIndex;
            }
          });
        }
      });

      this.switchChapter(this.chapterID, chapter_parent_index, chapter_index);
    });
  },
  data() {
    return {
      chapterID: null,
      courseSId: null,
      videoFile: null,
      catalog: null,
      videoURL: null,
      chapter: {
        chapter_name: ""
      }
    };
  },
  methods: {
    switchChapter(chapterSId, catalogIndex, chapterIndex) {
      RM_http.getDataJSON("/api/course/catalog/getVideoByChapter", {
        chapterSId
      })
        .then(response => {
          let videoFileData = response["data"];
          this.videoFile = videoFileData["video_fileinfo"];
          this.videoURL = this._G["STATICURL"] + this.videoFile.fileFolder;
          this.chapterSId = chapterSId;
          this.$router.push({
            path: `/course/${this.courseSId}/${chapterSId}`
          });
          if (catalogIndex != undefined) {
            this.chapter = {
              chapter_parent_index: catalogIndex,
              chapter_index: chapterIndex,
              ...this.catalog[catalogIndex]["child"][chapterIndex]
            };
          }
        })
        .catch(response => {
          this.$message.warning("切换失败，章节尚未上传视频");
        });
    }
  }
};
</script>


