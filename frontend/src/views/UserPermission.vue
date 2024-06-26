<script setup>
import UserSidebar from "@/components/UserSidebar.vue";
import UserTop from "@/components/UserTop.vue";
import {onMounted, ref} from "vue";
import {getToken} from "@/lib/tokenLib.js";
import router from "@/router/index.js";
import axios from "axios";
import {UserUrl} from "@/api/url.js";
import {alertError, axiosError} from "@/lib/requestAlert.js";
import {CircleCheckFilled, CircleClose, CircleCloseFilled} from "@element-plus/icons-vue";

const token = getToken()
const user = ref({})
const loading = ref(true)

onMounted(()=>{
  if (token) {
    axios.get(UserUrl.infoUrl, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).then(res=>{
      if (res.data.code === 200) {
        user.value = res.data.data
      } else {
        alertError(res, "数据获取失败", ()=>router.push("/login"))
      }
    }).catch(err=>{
      axiosError(err, "数据获取失败", location.reload)
    }).finally(()=>{
      loading.value = false
    })
  } else {
    router.push("/login")
  }
})
</script>

<template>
  <el-container>
    <el-aside style="width: unset">
      <UserSidebar default-active="2" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <UserTop title="用户权限" />
      </el-header>
      <el-main>
        <h2>用户权限</h2>
        <el-form v-loading="loading" label-width="auto">
          <el-form-item label="当前用户">
            <el-text type="info">{{ user["nickname"] }}（{{ user.username }}）</el-text>
          </el-form-item>
          <el-form-item label="用户组">
            <el-text type="info">{{ user["groupName"] }}</el-text>
          </el-form-item>
          <el-form-item label="管理员权限">
            <el-icon v-if="user['admin'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
          <el-form-item label="上传图片">
            <el-icon v-if="user['upload'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
          <el-form-item label="更新图片">
            <el-icon v-if="user['updatePic'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
          <el-form-item label="删除图片">
            <el-icon v-if="user['deletePic'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
          <el-form-item label="发送评论">
            <el-icon v-if="user['comment'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
          <el-form-item label="删除评论">
            <el-icon v-if="user['deleteComment'] === 'Y'" size="24" color="green"><CircleCheckFilled /></el-icon>
            <el-icon v-else size="24" color="red"><CircleCloseFilled /></el-icon>
          </el-form-item>
        </el-form>
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
h2 {
  margin-top: 0;
}
</style>