import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import LoginView from "@/views/LoginView.vue";
import RegisterView from '@/views/RegisterView.vue';
import ForgetView from '@/views/ForgetView.vue';
import UserBasic from "@/views/UserBasic.vue";
import UserPermission from "@/views/UserPermission.vue";
import UserSecurity from "@/views/UserSecurity.vue";
import UserPicture from "@/views/UserPicture.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: "IURT memes 2.0" }
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: { title: "登录账号" }
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
      meta: { title: "注册账号" }
    },
    {
      path: "/forget",
      name: "forget",
      component: ForgetView,
      meta: { title: "忘记密码" }
    },
    {
      path: "/user/basic",
      name: "userBasic",
      component: UserBasic,
      meta: { title: "用户中心 - 基本设置" }
    },
    {
      path: "/user/security",
      name: "userSecurity",
      component: UserSecurity,
      meta: { title: "用户中心 - 安全设置" }
    },
    {
      path: "/user/permission",
      name: "userPermission",
      component: UserPermission,
      meta: { title: "用户中心 - 用户权限" }
    },
    {
      path: "/user/picture",
      name: "userPicture",
      component: UserPicture,
      meta: { title: "用户中心 - 图片列表" }
    }
  ]
})
router.beforeEach((to, from, next) => {
  document.title = to.meta["title"]
  next()
})
export default router
