<style lang="less" scoped>
.publish-page-wrapper {
  margin: 20px 0 40px;
  padding: 10px;
  min-width: 100%;
  min-height: 100%;
  background: white;
  box-sizing: border-box;
}
.base-info {
  display: grid;
  grid-template-columns: 350px 3fr;
  grid-column-gap: 20px;
}
.work-cover-preview {
  position: relative;
  height: 230px;
  line-height: 230px;
  width: 100%;
  text-align: center;
  background: #f0f0f0;
  user-select: none;
  overflow: hidden;
  .no-cover-text {
    color: #999;
  }
  .work-cover-preview-img {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
.form-bottom-border {
  .form-el {
    padding: 5px;
    width: inherit;
    outline: none;
    border: none;
    box-sizing: border-box;
  }
  .form-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }
  .item-title {
    min-width: 70px;
    padding-right: 5px;
    font-size: 14px;
  }
  .item-el {
    width: 80%;
    border-bottom: 1px solid #eee;
  }
  .form-el-file {
    font-size: 12px;
  }
  .form-el-textatea {
    width: 100%;
    min-height: 30px;
    resize: vertical;
  }
}
.work-content {
  margin: 20px 0;
  height: 500px;
}
.form-submit {
  margin-top:50px;
}
</style>

<template>
  <div id="workPublishPage" class="publish-page page-width">
    <div class="publish-page-wrapper">
      <form @submit.prevent="submitForm()" class="publish-form form-bottom-border">
        <div class="base-info">
          <div class="work-cover-preview">
            <span class="no-cover-text">封面呢</span>
            <img :src="workCover" class="work-cover-preview-img" />
          </div>
          <div class="info">
            <label class="form-item">
              <div class="item-title">作品名称：</div>
              <div class="item-el">
                <input type="text" name id class="form-el form-el-input" />
              </div>
            </label>
            <label class="form-item">
              <div class="item-title">作品封面：</div>
              <div class="item-el">
                <input type="file" name id class="form-el form-el-file" @change="previewCover" />
              </div>
            </label>
            <label class="form-item">
              <div class="item-title">作品类型：</div>
              <div class="item-el">
                <select class="form-el form-el-select">
                  <option value>123456</option>
                </select>
              </div>
            </label>
            <label class="form-item">
              <div class="item-title">作品介绍：</div>
              <div class="item-el">
                <textarea class="form-el form-el-textatea" name id></textarea>
              </div>
            </label>
          </div>
        </div>
        <div class="work-content">
          <input
            type="file"
            style="display: none;"
            id="quillUploadFile"
            @change="uploadEditorFile($event)"
            accept="image/gif, image/jpeg, image/jpg, image/png"
          />
          <div
            class="quill-editor"
            :content="content"
            v-quill:myQuillEditor="editorOption"
          ></div>
        </div>
        <div class="form-item form-submit">
          <input type="submit" value="发布">
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Axios from "@nuxtjs/axios";
export default {
  data() {
    const self=this;
    return {
      workCover:
        "https://mir-s3-cdn-cf.behance.net/projects/max_808/e72d1279088683.Y3JvcCwxNjE2LDEyNjQsMCww.jpg",
      content: "",
      editorOption: {
        // some quill options
        theme: "snow",
        placeholder: "可以输入问题、插入图片等",
        modules: {
          toolbar: {
            container: [
              ["bold", "italic", "underline", "strike"], // toggled buttons
              ["blockquote"],
              ["link", "image"],

              [{ header: 1 }, { header: 2 }], // custom button values
              [{ list: "ordered" }, { list: "bullet" }],
              [{ script: "sub" }, { script: "super" }], // superscript/subscript
              [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
              [{ direction: "rtl" }], // text direction

              [{ size: ["small", false, "large", "huge"] }], // custom dropdown
              [{ header: [1, 2, 3, 4, 5, 6, false] }],

              [{ color: [] }, { background: [] }], // dropdown with defaults from theme
              [{ font: [] }],
              [{ align: [] }],

              ["clean"] // remove formatting button
            ],
            handlers: {
              image: function() {
                this.quill.format("image", false); // 禁用quill内部上传图片方法
                self.imgHandler(this);
              }
            }
          }
        }
      }
    };
  },
  methods: {
    previewCover() {
      console.log(1);
    },
    imgHandler(handle) {
      this.quill = handle.quill;
      let uploadFileDOM = document.getElementById("quillUploadFile");
      uploadFileDOM.click();
    },
    uploadEditorFile(DOM){
      let uploadFile=DOM.target.files[0];
      this.quill.insertEmbed(1, 'image', 'https://mir-s3-cdn-cf.behance.net/projects/max_808/e72d1279088683.Y3JvcCwxNjE2LDEyNjQsMCww.jpg' )
    },
    submitForm(){
      alert("Submit");
    }
  },
  components: {}
};
</script>


