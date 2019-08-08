<style lang="less" scoped>
.catalog {
  padding: 20px;
}
.drop {
  margin-bottom: 10px;
}
.drop .drop_tips {
  margin-top: 10px;
  font-size: 14px;
}
.drop .drop_tips p {
  padding: 5px 0;
}
.ondrap {
  width: 500px;
  height: 100px;
  border: 2px solid salmon;
}
.catalog_item {
  position: relative;
  margin-bottom: 30px;
}
.catalog_item .chapter_delete_button {
  display: none;
  margin-left: 10px;
}
.catalog_item:hover .chapter_delete_button {
  display: block;
}
.catalog_item .chapter_num {
  margin-right: 5px;
}
.catalog_item .chapter_drop {
  padding: 2px;
  height: 20px;
  font-size: 12px;
  color: rgba(46, 139, 87, 0.5);
  border: 1px dashed rgba(46, 139, 87, 0.5);
}
.catalog_item .chapter_name {
  width: 80%;
}
.catalog_item .chapter_parent {
  display: flex;
  align-items: center;
  padding: 10px 0;
  font-size: 20px;
  color: #333;
}
.chapter_child .chapter_child_item {
  position: relative;
  margin: 10px 0;
  padding: 5px 10px;
}
.chapter_child .chapter_drop {
  width: 95%;
  margin: 0 10px 10px;
}
.catalog_item .chapter_child_item .header {
  display: flex;
  padding-left: 10px;
  font-size: 16px;
}
.catalog_item .chapter_child_item .header {
  align-items: center;
}
.catalog_item .chapter_child_item .header .chapter_num {
  margin-right: 10px;
}
.catalog_item .chapter_child_item .chapter_drop {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
}
.chapter_child .upload_video_link {
  margin-left: 5px;
  font-size: 14px;
}
</style>

<template>
  <div class="catalog">
    <link rel="stylesheet" href="/pages/course/course_catalog.css">
    <div class="catalog_list">
      <div class="drop">
        改变顺序：
        <a-switch @click="isDrop=!isDrop" checkedChildren="1" unCheckedChildren="0"/>
        <div class="drop_tips" v-show="isDrop">
          父章节也可以拖动的，拖动的部分是章节名称部分，把要拖动的父章节拖动到每个章节底下的框框就好啦👌。
          <p>😋如果需要滚动屏幕的，拖住章节的同时，移动到网页的顶端就可以往上滚动，拖动到网页的最低端，就可以往下滚💨</p>不支持手机版📱变换顺序，只支持电脑版💻
        </div>
      </div>
      <transition-group name="fade_in_out">
        <div class="catalog_item" v-for="(catalogItem,catalogIndex) in catalog" :key="catalogIndex">
          <div class="chapter_parent" draggable="true" @dragstart="drag(catalogIndex)">
            <div class="chapter_num">第{{ catalogIndex+1 }}章</div>
            <div class="chapter_name">
              <a-input
                class="chapter_name_input"
                placeholder="章节名称"
                v-model="catalogItem['parent']['chapter_name']"
              />
            </div>
            <a-button
              class="chapter_delete_button"
              @click="removeChapter(catalogIndex)"
              size="small"
              type="dashed"
              shape="circle"
              icon="delete"
            ></a-button>
          </div>
          <div class="chapter_child">
            <div
              v-show="isDrop&&catalogItem['child'].length==0"
              araeType="child"
              @drop="drop()"
              @dragover="allowDrop()"
              class="chapter_drop"
              :cindex="0"
              :pindex="catalogIndex"
            ></div>
            <transition-group name="fade_in_out">
              <div
                class="chapter_child_item"
                :class="{ 'drop':isDrop }"
                v-for="(catalogChild,catalogChildIndex) in catalogItem['child']"
                :key="catalogChildIndex"
                draggable="true"
                @dragstart="drag(catalogIndex,catalogChildIndex)"
              >
                <div class="header">
                  <div class="chapter_num">{{ catalogIndex+1 }}-{{ catalogChildIndex+1 }}</div>
                  <div class="chapter_name">
                    <a-input
                      class="chapter_name_input"
                      placeholder="章节名称"
                      v-model="catalogChild['chapter_name']"
                    />
                  </div>
                  <router-link
                    class="upload_video_link"
                    :to="'/course/'+courseSId+'/catalog/'+catalogChild['chapter_sid']"
                  >上传视频</router-link>
                  <a-button
                    class="chapter_delete_button"
                    @click="removeChapter(catalogIndex,catalogChildIndex)"
                    size="small"
                    type="dashed"
                    shape="circle"
                    icon="delete"
                  ></a-button>
                </div>
                <div
                  v-show="isDrop"
                  araeType="child"
                  @drop="drop()"
                  @dragover="allowDrop()"
                  class="chapter_drop"
                  :cindex="catalogChildIndex"
                  :pindex="catalogIndex"
                ></div>
              </div>
            </transition-group>
          </div>
          <div
            v-show="isDrop"
            araeType="parent"
            @drop="drop()"
            @dragover="allowDrop()"
            class="chapter_drop chapter_parent_drop"
            :pindex="catalogIndex"
          ></div>
          <div class="append_chapter">
            <a-button type="dashed" @click="appendChapter(catalogIndex)">添加子章节</a-button>
          </div>
        </div>
      </transition-group>
    </div>
    <a-button type="dashed" @click="appendChapter()" :style="{marginRight:'10px'}">添加父章节</a-button>
    <a-button type="primary" @click="getCatalog">保存</a-button>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch.js";
export default {
  created() {
    this.courseSId = this.$route.params.courseSId;
    this.getCourseCatalog();
  },
  data() {
    return {
      isDrop: false,
      courseSId: null,
      catalog: []
    };
  },
  methods: {
    getCourseCatalog() {
      RM_fetch.getDataJSON("/api/course/catalog/getCatalogByCourseSId", {
        courseSId: this.courseSId
      }).then(response => {
        if (response) {
          this.catalog = response.data;
        }
      });
    },
    removeChapter(pindex, cindex) {
      if (!isNaN(cindex)) {
        if (
          this.catalog[pindex]["child"][cindex]["chapter_sid"].indexOf(
            "new_child"
          ) === 0
        ) {
          this.catalog[pindex]["child"].splice(cindex, 1);
          return;
        }
        RM_fetch.getDataJSON("/api/course/catalog/deleteChapterByChapterSId", {
          chapterSId: this.catalog[pindex]["child"][cindex]["chapter_sid"],
          type: "child"
        })
          .then(response => {
            this.$message.success("删除成功。");
            this.catalog[pindex]["child"].splice(cindex, 1);
          })
          .catch(response => {
            switch (response.status) {
              case 403:
                this.$message.error("删除失败，需要刷新页面后重试。");
                break;
            }
          });
      } else {
        if (this.catalog[pindex]["child"].length > 0) {
          if (confirm("该操作会把子章节也一并删除的喔🗑")) {
            if (
              this.catalog[pindex]["parent"]["chapter_sid"].indexOf(
                "new_parent"
              ) === 0
            ) {
              this.catalog.splice(pindex, 1);
              return;
            }
            RM_fetch.getDataJSON(
              "/api/course/catalog/deleteChapterByChapterSId",
              {
                chapterSId: this.catalog[pindex]["parent"]["chapter_sid"],
                type: "parent"
              }
            )
              .then(response => {
                this.$message.success("删除成功。");
                this.catalog.splice(pindex, 1);
              })
              .catch(response => {
                switch (response.status) {
                  case 403:
                    this.$message.error("删除失败，需要刷新页面后重试。");
                    break;
                }
              });
          }
        } else {
          if (
            this.catalog[pindex]["parent"]["chapter_sid"].indexOf(
              "new_parent"
            ) === 0
          ) {
            this.catalog.splice(pindex, 1);
            return;
          }
          RM_fetch.getDataJSON(
            "/api/course/catalog/deleteChapterByChapterSId",
            {
              chapterSId: this.catalog[pindex]["parent"]["chapter_sid"],
              type: "parent"
            }
          )
            .then(response => {
              this.$message.success("删除成功。");
              this.catalog.splice(pindex, 1);
            })
            .catch(response => {
              switch (response.status) {
                case 403:
                  this.$message.error("删除失败，需要刷新页面后重试。");
                  break;
              }
            });
        }
      }
    },
    appendChapter(pindex = null) {
      let randomID = Math.round(Math.random() * 100);
      if (pindex !== null) {
        this.catalog[pindex]["child"].push({
          chapter_sid: "new_child_" + randomID
        });
      } else {
        this.catalog.push({
          parent: {
            chapter_sid: "new_parent_" + randomID,
            chapter_name: ""
          },
          child: []
        });
      }
    },
    getCatalog() {
      RM_fetch.postDataJSON("/api/course/catalog/modify", {
        courseSId: this.courseSId,
        catalog: JSON.stringify(this.catalog)
      }).then(response => {
        this.$message.success("保存成功");
        this.catalog = response.data;
      });
    },
    drop(e) {
      var e = e || window.event;
      e.preventDefault();

      let sourceData = e.dataTransfer.getData("Array").split(",");
      let sourceParentIndex = sourceData[0];
      let sourceChindIndex = sourceData[1];

      let targetParentIndex = e.target.getAttribute("pindex");
      let targetType = e.target.getAttribute("araeType");
      if (sourceChindIndex && targetType == "child") {
        let targetChildIndex = e.target.getAttribute("cindex");
        if (
          targetParentIndex == sourceParentIndex &&
          targetChildIndex == sourceChindIndex
        ) {
          return false;
        }
        let sourceChild = this.catalog[sourceParentIndex]["child"][
          sourceChindIndex
        ];
        let targetChild = this.catalog[targetParentIndex]["child"][
          targetChildIndex
        ];
        sourceChild["chapter_up"] = this.catalog[targetParentIndex]["parent"][
          "chapter_sid"
        ];

        this.catalog[sourceParentIndex]["child"].splice(sourceChindIndex, 1);
        this.catalog[targetParentIndex]["child"].splice(
          targetChildIndex,
          0,
          sourceChild
        );
      } else if (!sourceChindIndex && targetType == "parent") {
        let sourceParent = this.catalog[sourceParentIndex];
        this.catalog.splice(sourceParentIndex, 1);
        this.catalog.splice(targetParentIndex, 0, sourceParent);
      }
    },
    drag(pindex, cindex) {
      var e = window.event;

      e.dataTransfer.setData("Array", [pindex, cindex]);
    },
    allowDrop(e) {
      var e = e || window.event;
      e.preventDefault();
    }
  }
};
</script>


