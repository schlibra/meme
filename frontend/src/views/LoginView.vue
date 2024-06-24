<script setup>
import {UserUrl} from "@/api/url.js";
import {ref} from "vue";
import {ElMessageBox} from "element-plus";
import axios from "axios";

const username = ref("");
const password = ref("");
const loginLoading = ref(false)

function loginHandler() {
  if (username.value.length && password.value.length) {
    loginLoading.value = true
    axios.post(UserUrl.loginUrl, {
      username: username.value,
      password: password.value
    }).then(res=>{
      if (res.data.code===200) {
        ElMessageBox.alert("登录成功", "登录成功")
      } else {
        ElMessageBox.alert(res.data["msg"], "登录失败")
      }
    }).catch(err=>{
      console.log(err)
      ElMessageBox.alert("接口响应异常", "登录失败");
    }).finally(() => {
      loginLoading.value = false
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
        <el-card v-loading="loginLoading">
          <template #header>
            <h1>登录</h1>
          </template>
          <template #default>
            <el-form label-width="auto">
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
                  <el-link>注册账号</el-link>
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