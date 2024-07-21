<script setup>

import AdminSidebar from "@/components/AdminSidebar.vue";
import AdminTop from "@/components/AdminTop.vue";
import {ref} from "vue";

const setting = ref({
  enableSckur: false,
  sckurApiKey: "",
  enableGitee: false,
  enableGithub: false,
  enableGitlab: false,
  enableSoftware: false
})
</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <AdminSidebar default-active="3" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <AdminTop title="第三方平台" />
      </el-header>
      <el-scrollbar height="calc(100vh - 30px)">
        <el-main>
          <h2>第三方平台</h2>
          <el-form label-position="top">
            <el-form-item label="开启思刻通行证">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enableSckur" />
            </el-form-item>
            <el-form-item label="思刻通行证ApiKey" v-if="setting.enableSckur">
              <el-input v-model="setting.sckurApiKey" />
            </el-form-item>
            <el-form-item label="开启Gitee">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enableGitee" />
            </el-form-item>
            <el-form-item label="开启Github">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enableGithub" />
            </el-form-item>
            <el-form-item label="开启Gitlab">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enableGitlab" />
            </el-form-item>
            <el-form-item label="开启微软账号">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enableSoftware" />
            </el-form-item>
          </el-form>
        </el-main>
      </el-scrollbar>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">

</style>