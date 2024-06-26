<script setup>
import router from "@/router/index.js";

const prop = defineProps(["title"])
const title = "用户中心 - " + prop.title
const shortTitle = prop.title

function gotoHome() {
  router.push({
    path: "/"
  })
}
</script>

<template>
  <el-page-header class="header" title="返回首页" @back="gotoHome">
    <template #content>
      <span class="hidden-xs-only">{{ title }}</span>
      <span class="hidden-sm-and-up">{{ shortTitle }}</span>
    </template>
    <template #title>
      <span class="hidden-xs-only">返回首页</span>
    </template>
  </el-page-header>
</template>

<style scoped lang="scss">
.header {
  margin-top: 8px;
}
</style>