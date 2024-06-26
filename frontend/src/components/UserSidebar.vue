<script setup>
import {Expand, Fold, User} from "@element-plus/icons-vue";
import {onMounted, ref} from "vue";
import displayUtil from "@/lib/displayUtil.js";

const prop = defineProps(["defaultActive"])
const defaultActive = prop.defaultActive
const expand = ref(false)

onMounted(()=> expand.value = displayUtil.value.isXs)
</script>

<template>
  <el-menu :default-active="defaultActive" class="menu" :collapse="expand">
    <el-menu-item disabled>
      <h3 v-if="expand"><el-icon><User /></el-icon></h3>
      <h3 v-else>用户中心</h3>
    </el-menu-item>
    <el-menu-item index="1">
      <el-icon><Menu /></el-icon>
      <span>基本设置</span>
    </el-menu-item>
    <el-menu-item index="2">
      <el-icon><Lock /></el-icon>
      <span>用户权限</span>
    </el-menu-item>
    <el-menu-item index="3">
      <el-icon><Picture /></el-icon>
      <span>图片列表</span>
    </el-menu-item>
    <el-menu-item index="4">
      <el-icon><ChatLineRound /></el-icon>
      <span>评论列表</span>
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