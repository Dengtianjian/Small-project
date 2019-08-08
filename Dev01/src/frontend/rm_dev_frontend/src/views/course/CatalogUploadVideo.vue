<style lang="less" scoped>
.upload_video {
  padding: 20px;
}
.video_preview_container {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 20px;
  width: 100%;
  height: 500px;
  overflow: hidden;
}
.video_preview_container video {
  max-height: 100%;
}
</style>

<template>
  <div class="upload_video">
    <a-upload-dragger
      :multiple="false"
      :customRequest="uploadVideo"
      :fileList="videoList"
      :remove="removeVideo"
      accept=".mp4"
    >
      <div>
        <p class="ant-upload-drag-icon">
          <a-icon type="inbox"/>
        </p>
        <p class="ant-upload-text">点击这里或者拖拽视频到这里就可上传</p>
        <p class="ant-upload-hint">仅支持 mp4 格式视频文件</p>
      </div>
    </a-upload-dragger>
    <div class="video_preview_container" v-if="videoURL">
      <video
        class="video"
        :src="_G['STATICURL']+videoURL"
        controls="controls"
      >抱歉， 您的浏览器不支持HTML5 video标签， 请升级浏览器后再重试。</video>
    </div>
  </div>
</template>

<script>
import RM_fetch from "../../functions/fetch.js";
export default {
  created() {
    let routerParams = this.$route.params;
    this.courseID = routerParams.courseSId;
    this.chapterID = routerParams.chapterSId;

    this.getVideo();
  },
  data() {
    return {
      courseID: null,
      chapterID: null,
      videoURL: null,
      videoList: [],
      isUpload: false
    };
  },
  methods: {
    getVideo() {
      RM_fetch.getDataJSON("/api/course/catalog/getVideoByChapter", {
        courseSId: this.courseID,
        chapterSId: this.chapterID
      })
        .then(response => {
          response = response.data;
          let fileInfo = response.video_fileinfo;

          this.isUpload = true;
          this.videoList.push({
            uid: "-1",
            name: fileInfo["fileName"],
            status: "done",
            url: fileInfo["fileFolder"],
            thumbUrl: "",
            response: fileInfo
          });
          this.videoURL = fileInfo["fileFolder"];
        })
        .catch(response => {
          this.$message.warning("当前章节尚未上传视频。", 1);
        });
    },
    uploadVideo(upload) {
      let { file } = upload;
      RM_fetch.postDataJSON("/api/course/catalog/uploadVideo", {
        courseSId: this.courseID,
        chapterSId: this.chapterID,
        file
      })
        .then(response => {
          let fileInfo = response.data["video_fileinfo"];

          this.videoList = [
            {
              uid: "-1",
              name: fileInfo["fileName"],
              status: "done",
              url: this._G["STATICURL"] + fileInfo["fileFolder"]
            }
          ];
          this.videoURL = fileInfo["fileFolder"];
          this.videoList = [upload.file];
          this.$message.success("上传成功");
          this.isUpload = true;
        })
        .catch(response => {
          switch (response.status) {
            case 403:
              let code = Number(response.reason.code);
              switch (code) {
                case 1:
                  this.$message.error("上传失败，5秒后返回课程页。");
                  setTimeout(() => {
                    this.$router.push({
                      path: `/course/${this.courseID}`
                    });
                  }, 5000);
                  break;
                case 2:
                  this.$message.warning("上传失败，所选视频文件太大。");
                  break;
                case 3:
                  this.$message.warning(
                    "上传失败，所选视频文件类型不在允许范围内，请上传mp4类型文件。"
                  );
                  break;
              }
              break;
            case 500:
              this.$message.error(
                "上传失败，服务器内部错误，请稍后再重新试试。"
              );
              break;
            default:
              this.$message.warning("上传失败，请稍后再重新试试。");
          }
          this.videoList = [];
          this.isUpload = false;
          return;
        });
    },
    removeVideo() {
      return new Promise((resolve, reject) => {
        if (this.isUpload == false) {
          resolve(true);
        }
        let file = this.videoList[0].response["data"];
        RM_fetch.postDataJSON("/api/course/catalog/removeVideoByChapter", {
          chapterSID: this.chapterID,
          fileInfo: JSON.stringify(file)
        })
          .then(response => {
            this.videoList = [];
            this.videoURL = null;
            this.isUpload = false;
            resolve(true);
            return;
          })
          .catch(response => {
            reject(false);
          });
      });
    }
  }
};
</script>


