import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      name: "default",
      path: '/',
      component: () => import(/* Default */ './views/course/Courses.vue')
    },
    {
      name: "courses",
      path: '/courses',
      component: () => import(/* Courses */ './views/course/Courses.vue')
    },
    {
      name:"course_publish",
      path:"/course/publish",
      component:()=>import(/* CoursePublish */ './views/course/Publish.vue')
    },
    {
      name:"course",
      path:"/course/:courseSId(\\w+)",
      component:() => import(/* Course */ './views/course/Course.vue')
    },
    {
      name:"course_catalog",
      path:"/course/:courseSId/catalog",
      component:()=>import(/* CourseCatalog */ './views/course/Catalog.vue')
    },
    {
      name:"course_catalog_uploadvideo",
      path:"/course/:courseSId/catalog/:chapterSId",
      component:()=>import(/* CourseCatalogUpload */ './views/course/CatalogUploadVideo')
    },
    {
      name:"course_catalog_chapter",
      path:"/course/:courseSId/:chapterSId(\\w+)",
      component:()=>import(/* CourseCatalogChapter */ './views/course/CourseChapter.vue')
    },
    {
      name:"user_login",
      path:"/login",
      components:{
        fullscreen_view:()=>import(/* UserLogin */ './views/user/Login.vue')
      }
    },
    {
      name:"user_register",
      path:"/register",
      components:{
        fullscreen_view:()=>import(/* UserLogin */ './views/user/Register.vue')
      }
    }
  ]
})