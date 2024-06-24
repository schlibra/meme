import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from "@/views/LoginView.vue";
import RegisterView from '@/views/RegisterView.vue';
import ForgetView from '@/views/ForgetView.vue';

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
      component:RegisterView,
      meta: { title: "注册账号" }
    },
    {
      path: "/forget",
      name: "forget",
      component:ForgetView,
      meta: { title: "忘记密码" }
    }
  ]
})
router.beforeEach((to, from, next) => {
  document.title = to.meta.title
  next()
})
export default router
