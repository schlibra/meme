<script setup>
const prop = defineProps(["defaultActive"])
const defaultActive = prop.defaultActive
</script>

<template>
  <el-menu :default-active="defaultActive" class="menu">
    <el-menu-item disabled>
      <h3>用户中心</h3>
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
  </el-menu>
</template>

<style scoped lang="scss">
.menu {
  height: 100vh;
}
</style>