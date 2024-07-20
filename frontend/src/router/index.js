import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import LoginView from "@/views/LoginView.vue";
import RegisterView from '@/views/RegisterView.vue';
import ForgetView from '@/views/ForgetView.vue';
import UserBasic from "@/views/UserBasic.vue";
import UserPermission from "@/views/UserPermission.vue";
import UserSecurity from "@/views/UserSecurity.vue";
import AboutView from "@/views/AboutView.vue";
import UserPicture from "@/views/UserPicture.vue";
import UserScore from "@/views/UserScore.vue";
import AdminBasic from "@/views/AdminBasic.vue";
import UserComment from "@/views/UserComment.vue";
import AdminSecurity from "@/views/AdminSecurity.vue";
import AdminGroup from "@/views/AdminGroup.vue";
import AdminUser from "@/views/AdminUser.vue"

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
    },
    {
      path: "/user/score",
      name: "userScore",
      component: UserScore,
      meta: { title: "用户中心 - 评分列表" }
    },
    {
      path: "/user/comment",
      name: "userComment",
      component: UserComment,
      meta: { title: "用户中心 - 评论列表" }
    },
    {
      path: "/about",
      name: "about",
      component: AboutView,
      meta: { title: "关于页面" }
    },
    {
      path: "/admin/basic",
      name: "adminBasic",
      component: AdminBasic,
      meta: { title: "后台管理中心 - 基本设置" }
    },
    {
      path: "/admin/security",
      name: "adminSecurity",
      component: AdminSecurity,
      meta: { title: "后台管理中心 - 安全设置" }
    },
    {
      path: "/admin/group",
      name: "adminGroup",
      component: AdminGroup,
      meta: { title: "后台管理中心 - 用户组管理" }
    },
    {
      path: "/admin/user",
      name: "adminUser",
      component: AdminUser,
      meta: { title: "后台管理中心 - 用户管理" }
    }
  ]
})
router.beforeEach((to, from, next) => {
  document.title = to.meta["title"]
  next()
})
export default router
