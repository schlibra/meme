<script setup>
import router from "@/router/index.js";

const prop = defineProps(["title"])
const title = "用户中心 - " + prop.title

function gotoHome() {
  router.push({
    path: "/"
  })
}
</script>

<template>
  <el-page-header class="header" title="返回首页" @back="gotoHome" :content="title" />
</template>

<style scoped lang="scss">
.header {
  margin-top: 8px;
}
</style>