<script setup>
import {UserUrl} from "@/api/url.js";
import {ref} from "vue";
import {ElMessageBox} from "element-plus";
import axios from "axios";

const username = ref("");
const nickname = ref("");
const password = ref("");
const confirm = ref("");
const email = ref("");
const emailCheck = ref("");
const formLoading = ref(false);

function registerHandler() {
  if (username.value.length && nickname.value.length &&
      password.value.length && confirm.value.length &&
      email.value.length && emailCheck.value.length) {
    // formLoading.value = true
    return;
    axios.post(UserUrl.loginUrl, {

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
      formLoading.value = false
    })
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
                    <el-button>发送验证码</el-button>
                  </el-col>
                </el-row>
              </el-form-item>
              <el-form-item label="邮箱验证码">
                <el-input type="text" v-model="emailCheck" placeholder="输入邮箱验证码" />
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