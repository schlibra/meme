<script setup>
import { UserUrl, CaptchaUrl } from "@/api/url.js";
import {ref} from "vue";
import axios from "axios";
import router from "@/router/index.js";
import {alertError, alertSuccess, axiosError} from "@/lib/requestAlert.js";
import {setToken} from "@/lib/tokenLib.js";
import {GoogleSignInButton} from "vue3-google-signin";

const username = ref("");
const password = ref("");
const captcha = ref("")
const captchaUrl = ref(CaptchaUrl)
const usernameRef = ref(null)
const passwordRef = ref(null)
const captchaRef = ref(null)
const formLoading = ref(false)

function loginHandler() {
  if (username.value.length && password.value.length) {
    formLoading.value = true
    axios.post(UserUrl.loginUrl, {
      username: username.value,
      password: password.value,
      captcha: captcha.value
    }).then(res=>{
      if (res.data.code===200) {
        setToken(res)
        alertSuccess(res, "登录成功", () => router.push("/"))
      } else {
        alertError(res, "登录失败")
      }
    }).catch(err=>{
      axiosError(err, "登录失败");
    }).finally(() => formLoading.value = false)
  } else {
    alertError("账号密码不能为空", "登录失败")
  }
}
function enterHandler() {
  if (username.value.length === 0) {
    usernameRef.value.focus()
  } else if (password.value.length === 0) {
    passwordRef.value.focus()
  } else if (captcha.value.length === 0) {
    captchaRef.value.focus()
  }else {
    loginHandler()
  }
}
function reloadCaptcha() {
  captchaUrl.value = `${CaptchaUrl}?${Math.random()}`
}
function googleLoginSuccess(response) {
  console.log(response)
}
</script>


<template>
  <main>
    <el-row justify="center" align="middle" class="row">
      <el-col :xs="22" :sm="18" :md="14" :lg="10" :xl="6">
        <el-card v-loading="formLoading">
          <template #header>
            <h1>登录</h1>
          </template>
          <template #default>
            <el-form label-width="auto" label-position="top">
              <el-form-item label="用户名">
                <el-input autofocus ref="usernameRef" v-model="username" placeholder="输入账号或邮箱" @keydown.enter="enterHandler" />
              </el-form-item>
              <el-form-item label="密码">
                <el-input ref="passwordRef" type="password" v-model="password" placeholder="输入密码" @keydown.enter="enterHandler" />
              </el-form-item>
              <el-form-item label="图片验证码">
                <el-row :gutter="8">
                  <el-col :span="14">
                    <el-input v-model="captcha" ref="captchaRef" @keydown.enter="enterHandler" />
                  </el-col>
                  <el-col :span="10">
                    <el-image :src="captchaUrl" @click="reloadCaptcha" class="pointer" />
                  </el-col>
                </el-row>
              </el-form-item>
<!--              <el-row>-->
<!--                <el-col :span="24">-->
<!--                  <GoogleSignInButton @success="googleLoginSuccess" />-->
<!--                </el-col>-->
<!--              </el-row>-->
              <el-row class="down-row">
                <el-col :span="12">
                  <el-link href="/forget">忘记密码</el-link>
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
  .pointer {
    cursor: pointer;
  }
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