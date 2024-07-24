<script setup>
import UserSidebar from "@/components/AdminSidebar.vue";
import {computed, onMounted, ref} from "vue";
import router from "@/router/index.js";
import axios from "axios";
import {UserUrl} from "@/api/url.js";
import UserTop from "@/components/AdminTop.vue";
import {alertError, alertSuccess, axiosError} from "@/lib/requestAlert.js";
import {getToken} from "@/lib/tokenLib.js";
import {Get} from "@/lib/axiosLib.js";
import {InfoFilled} from "@element-plus/icons-vue";

const token = getToken()
const user = ref({sex: "", birth: 0})
const setting = ref({
  siteName: 'IURT meme',
  siteLogo: "",
  enableHomeTyping: "Y",
  enableGravatarCDN: "Y",
  gravatarCDNAddress: 'https://cdn.tsinbei.com/gravatar',
  enablePicCompress: "N",
  picCompressType: "no",
  enablePictureVerify: "N",
  enableCommentVerify: "N",
  enableCaptcha: "Y",
  enableUserLog: "Y",
  enableAdminLog: "Y",
})
const mainLoading = ref(true)

onMounted(()=>{
  if (token) {
    Get(UserUrl.infoUrl, {}, {
      ok(res) {
        
      },
      bad(res) {
        alertError(res, "数据获取失败", () => router.push("/login"))
      },
      error(err) {
        axiosError(err, "数据获取失败", () => location.reload())
      },
      final() {
        mainLoading.value = false
      }
    })
  } else {
    router.push("/login")
  }
})

</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <UserSidebar default-active="1" />
    </el-aside>
    <el-container>
      <el-header height="30px">
        <UserTop title="基本设置" />
      </el-header>
      <el-scrollbar height="calc(100vh - 30px)">
        <el-main>
          <h2>基本设置</h2>
          <el-form label-position="top">
            <el-form-item label="站点名称">
              <el-input v-model="setting.siteName" />
            </el-form-item>
            <el-form-item label="站点logo">
              <el-input v-model="setting.siteLogo" />
            </el-form-item>
            <el-form-item label="开启首页打字效果">
              <el-switch disabled v-model="setting.enableHomeTyping" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="启用头像CDN">
              <el-switch v-model="setting.enableGravatarCDN" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="头像CDN地址">
              <el-input :disabled="!setting.enableGravatarCDN" v-model="setting.gravatarCDNAddress" model-value="https://cdn.tsinbei.com/gravatar" />
            </el-form-item>
            <el-form-item label="开启图片压缩">
              <el-switch active-text="开启" inactive-text="关闭" v-model="setting.enablePicCompress" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="图片压缩方式">
              <el-select v-model="setting.picCompressType">
                <el-option label="选择压缩方式" value="no" disabled />
                <el-option label="Gzip压缩" value="gzip" />
                <el-option label="Bzip2压缩" value="bz2" />
              </el-select>
            </el-form-item>
            <el-form-item label="开启图片审核">
              <el-switch v-model="setting.enablePictureVerify" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="开启评论审核">
              <el-switch v-model="setting.enableCommentVerify" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="开启图形验证码">
              <el-space wrap direction="vertical" alignment="normal">
                <el-switch v-model="setting.enableCaptcha" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
                <el-text type="info"><ElIcon><InfoFilled /></ElIcon>开启该功能需要开启扩展gd（或php_gd）</el-text>
              </el-space>
            </el-form-item>
            <el-form-item label="记录用户日志">
              <el-switch v-model="setting.enableUserLog" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-form-item label="记录管理员日志">
              <el-switch v-model="setting.enableAdminLog" active-text="开启" inactive-text="关闭" active-value="Y" inactive-value="N" />
            </el-form-item>
            <el-button>编辑</el-button>
            <el-button>保存</el-button>
          </el-form>
        </el-main>
      </el-scrollbar>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
.el-form {
  .el-text {
    margin-left: 8px;
    font-size: 12px;
  }
}
h2 {
  margin-top: 0;
}
.el-scrollbar {
  width: 100%;

}
</style>