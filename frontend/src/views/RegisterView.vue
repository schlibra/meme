<script setup>
import {UserUrl} from "@/api/url.js";
import {ref} from "vue";
import {ElMessageBox} from "element-plus";
import axios from "axios";

const username = ref("");
const password = ref("");
const confirm = ref("");
const loginLoading = ref(false);
const email = ref("");
const dickname = ref("");
const emailCheck = ref("");

function loginHandler() {
  if (username.value.length && password.value.length) {
    loginLoading.value = true
    axios.post(UserUrl.loginUrl, {
      username: username.value,
      password: password.value
    }).then(res=>{
      if (res.data.code===200) {
        ElMessageBox.alert("注册成功", "注册成功")
      } else {
        ElMessageBox.alert(res.data["msg"], "注册失败")
      }
    }).catch(err=>{
      console.log(err)
      ElMessageBox.alert("接口响应异常", "注册失败");
    }).finally(() => {
      loginLoading.value = false
    })
  } else {
    ElMessageBox.alert("账号密码不能为空", "注册失败")
  }
}
</script>

<template>
  <main >
  
    <el-row justify="center" align="middle" class="row">
      <el-col :span="12">
        <el-card v-loading="loginLoading">
          <template #header>
            <h1>注册</h1>
          </template>
          <template #default>
            <el-form label-width="auto">
              <el-form-item label="用户名">
                <el-input v-model="username" placeholder="输入用户名" />
              </el-form-item>
              <el-form-item label="昵称">
                <el-input v-model="dickname" placeholder="输入昵称" />
              </el-form-item>
              <el-form-item label="密码">
                <el-input type="password" v-model="password" placeholder="输入密码" />
              </el-form-item>
              <el-form-item label="确认密码">
                <el-input type="password" v-model="confirm" placeholder="再次输入密码" />
              </el-form-item>
              <el-form-item label="邮箱">
                <el-input type="text" v-model="email" placeholder="输入邮箱" />
              </el-form-item>
              <el-form-item label="邮箱验证码">
                <el-input type="text" v-model="emailCheck" placeholder="输入邮箱验证码" />
              </el-form-item>
              <el-row class="down-row">
                <el-col :span="12">
                  <el-link>不知道干什么用的</el-link>
                </el-col>
                <el-col :span="12" class="right">
                  <el-link>已有账号，立刻登录</el-link>
                </el-col>
              </el-row>
            </el-form>
          </template>
          <template #footer>
            <el-button class="register" type="primary" @click="loginHandler" size="large">注册</el-button>
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
  .down-row {
    margin: 0 16px;
    .right{
      text-align: right;
    }
  }
}
</style>