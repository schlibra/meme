<script setup>
import {UserUrl} from "@/api/url.js";
import {ref} from "vue";
import {ElMessageBox} from "element-plus";
import axios from "axios";
import router from "@/router/index.js";

const username = ref("");
const password = ref("");
const formLoading = ref(false)

function loginHandler() {
  if (username.value.length && password.value.length) {
    formLoading.value = true
    axios.post(UserUrl.loginUrl, {
      username: username.value,
      password: password.value
    }).then(async res=>{
      if (res.data.code===200) {
        localStorage.setItem("token", res.data.token)
        await ElMessageBox.alert("登录成功", "登录成功")
        await router.push({
          path: "/"
        })
      } else {
        await ElMessageBox.alert(res.data["msg"], "登录失败")
      }
    }).catch(err=>{
      console.log(err)
      ElMessageBox.alert("接口响应异常", "登录失败");
    }).finally(() => {
      formLoading.value = false
    })
  } else {
    ElMessageBox.alert("账号密码不能为空", "登录失败")
  }
}
</script>

<template>
  <main>
    <el-row justify="center" align="middle" class="row">
      <el-col :span="12">
        <el-card v-loading="formLoading">
          <template #header>
            <h1>登录</h1>
          </template>
          <template #default>
            <el-form label-width="auto" label-position="top">
              <el-form-item label="用户名">
                <el-input v-model="username" placeholder="输入账号或邮箱" />
              </el-form-item>
              <el-form-item label="密码">
                <el-input type="password" v-model="password" placeholder="输入密码" />
              </el-form-item>
              <el-row class="down-row">
                <el-col :span="12">
                  <el-link>忘记密码</el-link>
                </el-col>
                <el-col :span="12" class="right">
                  <el-link href="/register">注册账号</el-link>
                </el-col>
              </el-row>
            </el-form>
          </template>
          <template #footer>
            <el-button class="login" type="primary" @click="loginHandler" size="large">登录</el-button>
          </template>
        </el-card>
      </el-col>
    </el-row>
  </main>
</template>

<style scoped lang="scss">
main {
  height: 100vh;
  background: url("@/assets/bg.jpg") round;
  .row {
    height: 100vh;
  }
  .login {
    margin-top: 16px;
  }
  .down-row {
    margin: 0 16px;
    .right{
      text-align: right;
    }
  }
}
</style>