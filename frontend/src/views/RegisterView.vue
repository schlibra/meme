<script setup>
import {UserUrl} from "@/api/url.js";
import {ref} from "vue";
import { ElMessageBox } from "element-plus";
import axios from "axios";
import router from "@/router/index.js";

const username = ref("");
const nickname = ref("");
const password = ref("");
const confirm = ref("");
const email = ref("");
const code = ref("");
const formLoading = ref(false);

let emailToken;

function sendCodeHandler() {
  if (email.value.length) {
    formLoading.value = true
    axios.post(UserUrl.sendCodeUrl, {
      email: email.value
    }).then(res=>{
      if (res.data.code === 200) {
        emailToken = res.data.token
        ElMessageBox.alert("发送成功，请及时查收", "发送成功")
      } else {
        ElMessageBox.alert(res.data["msg"], "发送失败")
      }
    }).catch(err=>{
      console.log(err)
      ElMessageBox.alert("接口请求异常", "发送失败")
    }).finally(()=>{
      formLoading.value = false
    })
  } else {
    ElMessageBox.alert("邮箱地址不能为空", "发送失败")
  }
}

function registerHandler() {
  if (username.value.length && nickname.value.length &&
      password.value.length && confirm.value.length &&
      email.value.length && code.value.length) {
    if (password.value === confirm.value) {
      formLoading.value = true
      axios.post(UserUrl.registerUrl, {
        username: username.value,
        nickname: nickname.value,
        password: password.value,
        email: email.value,
        code: code.value
      }, {
        headers: {
          Authorization: `Bearer ${emailToken}`
        }
      }).then(async res => {
        if (res.data.code === 200) {
          await ElMessageBox.alert("注册成功", "注册成功")
          await router.push({
            path: "/login"
          })
        } else {
          await ElMessageBox.alert(res.data["msg"], "注册失败")
        }
      }).catch(err => {
        console.log(err)
        ElMessageBox.alert("接口响应异常", "注册失败");
      }).finally(() => {
        formLoading.value = false
      })
    } else {
      ElMessageBox.alert("两次密码不一致", "注册失败")
    }
  } else {
    ElMessageBox.alert("字段不能为空", "注册失败")
  }
}
</script>

<template>
  <main >
  
    <el-row justify="center" align="middle" class="row">
      <el-col :span="12">
        <el-card v-loading="formLoading">
          <template #header>
            <h1>注册</h1>
          </template>
          <template #default>
            <el-form label-width="auto" label-position="top">
              <el-form-item label="用户名">
                <el-input v-model="username" placeholder="输入用户名" />
              </el-form-item>
              <el-form-item label="昵称">
                <el-input v-model="nickname" placeholder="输入昵称" />
              </el-form-item>
              <el-form-item label="密码">
                <el-input type="password" v-model="password" placeholder="输入密码" />
              </el-form-item>
              <el-form-item label="确认密码">
                <el-input type="password" v-model="confirm" placeholder="再次输入密码" />
              </el-form-item>
              <el-form-item label="邮箱">
                <el-row style="width: 100%;" :gutter="8">
                  <el-col :span="20">
                    <el-input type="text" v-model="email" placeholder="输入邮箱" />
                  </el-col>
                  <el-col :span="4">
                    <el-button type="info" @click="sendCodeHandler">发送验证码</el-button>
                  </el-col>
                </el-row>
              </el-form-item>
              <el-form-item label="邮箱验证码">
                <el-input type="text" v-model="code" placeholder="输入邮箱验证码" />
              </el-form-item>
              <div class="right">
                <el-link href="/login">已有账号，立刻登录</el-link>
              </div>
            </el-form>
          </template>
          <template #footer>
            <el-button class="register" type="primary" @click="registerHandler" size="large">注册</el-button>
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
  .register {
    margin-top: 16px;
  }
  .right{
    text-align: right;
  }
}
</style>