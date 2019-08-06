import EditNews from './components/EditNews';
import NewsPage from "./components/NewsPage";
import NewsItem from "./components/NewsItem";

//创建路由
const routes=[
  {
    path:"/",
    component:NewsPage
  },
  {
    path:"/publish/:id?",
    component:EditNews
  },
  {
    path:"/item/:id",
    component:NewsItem
  }
]

export default routes;