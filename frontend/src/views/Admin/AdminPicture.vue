<script setup>
import AdminSidebar from "@/components/AdminSidebar.vue";
import AdminTop from "@/components/AdminTop.vue";
import { onMounted, ref } from 'vue'
import {Get} from "@/lib/axiosLib.js";
import {AdminUrl} from "@/api/url.js";
import {alertError, axiosError} from "@/lib/requestAlert.js";

const pic = ref([])
const mainLoading = ref(true)

const reload = () => location.reload()

onMounted(()=>{
  Get(AdminUrl.pictureUrl, {}, {
    ok(_, data) {
      pic.value = data
    },
    bad(res) {
      alertError(res, "图片获取失败", reload)
    },
    error(err) {
      axiosError(err, "图片获取失败", err)
    },
    final() {
      mainLoading.value = false
    }
  })
})
</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <AdminSidebar default-active="6" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <AdminTop title="图片管理" />
      </el-header>
      <el-main v-loading="mainLoading">
        <el-scrollbar height="calc(100vh - 30px)">
          <h2>图片管理</h2>
          <el-form label-position="top">
            <el-form-item label="图片列表">
              <el-table :data="pic">
                <el-table-column label="图片id" prop="picId" />
                <el-table-column label="图片名称" prop="name" />
                <el-table-column label="图片描述" prop="description" />
                <el-table-column label="图片">
                  <template #default="scope">
                    <el-image :src="pic[scope.$index].url" />
                  </template>
                </el-table-column>
                <el-table-column label="上传用户">
                  <template #default="scope">
                    <el-text>{{ pic[scope.$index].nickname }}（{{ pic[scope.$index].userId }}）</el-text>
                  </template>
                </el-table-column>
              </el-table>
            </el-form-item>
          </el-form>
        </el-scrollbar>
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
h2 {
  margin-top: 0;
}
</style>