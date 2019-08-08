<style lang="less" scoped>
.course_publish_page {
  padding: 20px;
}
.course_publish_page .form_publish .form_title {
  font-size: 20px;
}
.course_publish_page .form_publish .form {
  margin-top: 30px;
}
</style>

<template>
  <div id="course_publish_page" class="course_publish_page">
    <div class="form_publish">
      <div class="form_title">发布视频</div>
      <a-row>
        <a-col :xs="24" :sm="24" :md="18" :lg="18" :xl="18">
          <a-form class="form" :form="publish_form" @submit="publishSubmit">
            <a-form-item label="课程封面" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-upload
                listType="picture"
                class="upload-list-inline"
                :remove="deleteCover"
                :fileList="form.fileList"
                accept=".png, .jpg"
                :customRequest="uploadCover"
              >
                <div v-if="form.fileList.length < 1">
                  <a-button>
                    <a-icon type="upload"/>上传封面
                  </a-button>
                </div>
              </a-upload>
              <a-modal :visible="form.previewVisible" :footer="null" @cancel="handleCancel">
                <img alt="example" style="width: 100%" :src="form.values.cover">
              </a-modal>
            </a-form-item>

            <a-form-item label="课程名称" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-input
                v-decorator="[
          'course_name',
          {rules: [{ required: false, message: '请输入课程名称!' }]}
        ]"
              />
            </a-form-item>

            <a-form-item label="课程来源地址链接" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-input
                v-decorator="[
          'course_source_link',
          {rules: [{ required: false, message: '请输入课程来源地址链接!' }]}
        ]"
              />
            </a-form-item>

            <a-form-item label="课程类型" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-cascader
                size="large"
                :options="form.catagory"
                placeholder="请选择课程类型"
                :fieldNames="{
                  label: 'label', value: 'id', children: 'children'
                }"
                v-decorator="[
          'course_type',
          {rules: [{ required: false, message: '请选择课程类型' }]}
        ]"
              />
            </a-form-item>

            <a-form-item label="发布日期" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-date-picker
                v-decorator="[
              'course_publish_date',
              {rules: [{ required: false, message: '请选择课程的发布日期' }]}
            ]"
              />
            </a-form-item>

            <a-form-item label="课程简介" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
              <a-textarea
                placeholder="请填写课程简介"
                :autosize="{ minRows: 4, maxRows: 6 }"
                v-decorator="[
              'course_introduction',
              {rules: [{ required: false, message: '请填写课程简介' }]}
              ]"
              />
            </a-form-item>

            <a-form-item label="课程介绍" :label-col="{ span: 5 }" :wrapper-col="{ span: 20,push:2 }">
              <Editor v-model="form.description" :init="editorInit" ></Editor>
            </a-form-item>

            <a-form-item :wrapper-col="{ span: 12, offset: 5 }">
              <a-button type="primary" html-type="submit">发布</a-button>
            </a-form-item>
          </a-form>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
          <h2>禁止发布盗版的视频课程！</h2>
          <p>一经发现将删除相关信息并且封号处理</p>
        </a-col>
      </a-row>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch.js";
import RM_cookie from "../../functions/cookie.js";

import tinymce from 'tinymce/tinymce'
import Editor from '@tinymce/tinymce-vue'
import 'tinymce/themes/silver/theme'
import "tinymce/plugins/image"; // 插入上传图片插件
import "tinymce/plugins/wordcount"; // 字数统计插件
import "tinymce/plugins/preview"; // 预览插件
import "tinymce/plugins/link"; // 链接插件
import "tinymce/plugins/codesample"; // 代码插件
import "tinymce/plugins/imagetools"; // 图片工具插件
import "tinymce/plugins/table"; // 表格插件
export default {
  created(){
  },
  data() {
    return {
      dateFormat: 'YYYY/MM/DD',
      editorInit: {
        language_url: '/tinymce/zh_CN.js',
        language: 'zh_CN',
        skin_url: '/tinymce/skins/lightgray',
        height: 300,
        browser_spellcheck: true, // 拼写检查
        branding: false, // 去水印
        elementpath: false, //禁用编辑器底部的状态栏
        statusbar: false, // 隐藏编辑器底部的状态栏
        paste_data_images: true, // 允许粘贴图像
        menubar: false, // 隐藏最上方menu,
        plugins: "wordcount preview image imagetools link codesample table",
        toolbar: [
          "undo redo | styleselect | bold italic | table | codesample link image preview |"
        ]
      },
      form: {
        catagory: [{
          id: 1,
          label: "前端",
          children: [{
              id: 2,
              label: "HTML/CSS"
            },
            {
              id: 3,
              label: "Javascript"
            }
          ]
        }],
        values: {
          cover: "",
          description: null
        },
        coverUploadIsOK: null,
        previewVisible: false,
        fileList: []
      },
      publish_form: this.$form.createForm(this)
    };
  },
  methods: {
    handleCancel() {
      this.previewVisible = false;
    },
    handlePreview(file) {
      this.form.values.cover = file.url || file.thumbUrl;
      this.form.previewVisible = true;
    },
    uploadCover(upload) {
      let {
        file
      } = upload;
      RM_fetch.postDataJSON("/api/course/course/uploadCover", {
        file
      }).then(response => {
        this.form.fileList = [{
          uid: '1',
          name: response['fileName'],
          status: 'done',
          response,
          url: this._G['STATICURL']+response['fileFolder'],
        }];
        this.form.values.cover = response.fileFolder;
        this.$message.success("封面图片上传成功。✔");
        this.form.coverUploadIsOK = true;
      }).catch(response=>{
        this.form.coverUploadIsOK = false;
          switch (response.code) {
            case 1:
              this.$message.warning(
                "😥图片太大了，请选择其他图片作为封面上传。"
              );
              break;
            case 2:
              this.$message.error(
                "🙅‍上传的文件类型不支持，请上传 png、jpeg、jpg类型图片上传。"
              );
              break;
            case 3:
              this.$message.error(
                "🔨抱歉，内部错误，正在修复中，请稍后再上传。"
              );
              break;
          }
        })
    },
    deleteCover(file) {
      return new Promise((resolve, reject) => {
        if (!this.form.coverUploadIsOK) {
          resolve(true);
          return;
        }
        file = file.response;

        RM_fetch.postDataJSON("/api/course/course/deleteCover", file)
          .then(response => {
            this.$message.success("删除成功。");
            this.form.values.cover = "";
            this.form.fileList=[];
            resolve();
            return;
          })
          .catch(response => {
            this.$message.warning("删除失败，请稍后重试。");
            reject(false);
          });
      });
    },
    publishSubmit(e) {
      e.preventDefault();

      if (RM_cookie.getCookies("uKey")) {
        var uKey = RM_cookie.getCookies("uKey");
      } else {
        this.$message.error("尚未登录，无法发布。");
        return;
      }

      this.publish_form.validateFields((err, values) => {
        if (!err) {
          let publishDate = new Date(values.course_publish_date._d).getTime();
          let postData = {
            course_publish_date: publishDate,
            course_name: values["course_name"],
            course_source_link: values["course_source_link"],
            course_introduction: values["course_introduction"],
            course_type: values["course_type"],
            course_cover: this.form.values.cover,
            course_description: this.form.description,
            course_user: this.$store.state.user.info.user_id,
            uKey
          };
          RM_fetch.postDataJSON("/api/course/course/publishCourse", postData)
            .then(response => {
              var redirectCourse = setTimeout(() => {
                this.$root.$router.push(`/course/${response.data}`);
              }, 3000);
              this.$notification.open({
                key: "redirectCourse_notification",
                placement: "bottomRight",
                message: "发布成功",
                description: "现在准备跳转到新发布课程页面。你可以点击下面按钮取消跳转，停留在当前页面。",
                btn: h => {
                  return h(
                    "a-button", {
                      props: {
                        type: "primary",
                        size: "small"
                      },
                      on: {
                        click: () => {
                          clearTimeout(redirectCourse);
                          this.$notification.close(
                            "redirectCourse_notification"
                          );
                        }
                      }
                    },
                    "取消跳转"
                  );
                }
              });
            })
            .catch(response => {
              switch (response.status) {
                case 403:
                  this.$message.warning(
                    "必填项未输入。请填入所有带红色 * 符号的输入项。"
                  );
                  break;
                case 401:
                  switch (response.reason.code) {
                    case 1:
                      this.$message.error("尚未登录，无法发布。");
                      break;
                    case 2:
                      this.$message.error("登录已过期，请重新登录。");
                      break;
                  }
                  break;
                case 500:
                  this.$message.error("服务器内部错误，正在修复，请稍后再试试。");
                  break;
              }
            });
        }
      });
    }
  },
  components: {
    Editor
  },
};
</script>
