<script setup>
import {ChatLineRound, Expand, Fold, Key, Lock, Star, User} from "@element-plus/icons-vue";
import {onMounted, ref} from "vue";
import displayUtil from "@/lib/displayUtil.js";
import router from "@/router/index.js";

const prop = defineProps(["defaultActive"])
const defaultActive = prop.defaultActive
const expand = ref(false)

onMounted(()=> expand.value = displayUtil.isXs)

function gotoBasic() {
  router.push("/admin/basic")
}
/*
function gotoSecurity() {
  router.push("/user/security")
}
function gotoPermission() {
  router.push("/user/permission")
}
function gotoPicture() {
  router.push("/user/picture")
}
function gotoScore() {
  router.push("/user/score")
}

*/
</script>

<template>
  <el-menu :default-active="defaultActive" class="menu" :collapse="expand">
    <el-menu-item disabled>
      <h3 v-if="expand"><el-icon><User /></el-icon></h3>
      <h3 v-else>后台管理中心</h3>
    </el-menu-item>
    <el-menu-item index="1" @click="gotoBasic">
      <el-icon><Menu /></el-icon>
      <span>基本信息</span>
    </el-menu-item>
    <el-menu-item index="2" @click="gotoBasic">
      <el-icon><Lock /></el-icon>
      <span>安全设置</span>
    </el-menu-item>
    <el-menu-item index="3" @click="gotoBasic">
      <el-icon><Key /></el-icon>
      <span>用户组管理</span>
    </el-menu-item>
    <el-menu-item index="4" >
      <el-icon><User /></el-icon>
      <span>用户管理</span>
    </el-menu-item>
    <el-menu-item index="5" @click="gotoBasic">
      <el-icon><Picture /></el-icon>
      <span>图片管理</span>
    </el-menu-item>
    <el-menu-item index="6" @click="gotoBasic">
      <el-icon><Star /></el-icon>
      <span>评分管理</span>
    </el-menu-item>
    <el-menu-item index="7">
      <el-icon><ChatLineRound /></el-icon>
      <span>评论管理</span>
    </el-menu-item>
    <el-menu-item @click="expand = !expand">
      <el-icon v-if="expand"><Expand /></el-icon>
      <el-icon v-else><Fold /></el-icon>
      <span>收缩侧栏</span>
    </el-menu-item>
  </el-menu>
</template>

<style scoped lang="scss">
.menu {
  height: 100vh;
  .el-menu-item {
    padding-right: 32px;
  }
}
</style>