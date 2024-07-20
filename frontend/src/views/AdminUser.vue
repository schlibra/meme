<script setup>
import AdminSidebar from "@/components/AdminSidebar.vue";
import AdminTop from "@/components/AdminTop.vue";
import {onMounted, ref} from "vue";
import {Get} from "@/lib/axiosLib.js";
import {AdminUrl} from "@/api/url.js";
import {alertError, axiosError} from "@/lib/requestAlert.js";
import {CircleCheckFilled, CircleCloseFilled} from "@element-plus/icons-vue";

const user = ref([])
const dataLoading = ref(true)

onMounted(()=>{
  loadList()
})
function loadList() {
  Get(AdminUrl.userUrl, {}, {
    ok(_, data) {
      user.value = data
    },
    bad(res) {
      alertError(res, "数据获取失败", ()=>router.push("/"))
    },
    error(err) {
      axiosError(err, "数据获取失败", ()=>location.reload())
    },
    final() {
      dataLoading.value = false
    }
  })
}
</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <AdminSidebar default-active="4" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <AdminTop title="用户管理" />
      </el-header>
      <el-scrollbar height="calc(100vh - 30px)">
        <el-main>
          <h2>用户管理</h2>
          <el-form label-position="top">
            <el-form-item label="">
              <el-button type="primary">新增用户</el-button>
            </el-form-item>
            <el-form-item label="用户列表">
              <el-table :data="user">
                <el-table-column label="id" prop="userId" />
                <el-table-column label="用户名" prop="username" />
                <el-table-column label="昵称" prop="nickname" />
                <el-table-column label="是否管理员" align="center">
                  <template #default="scope">
                    <el-icon color="green" v-if="user[scope.$index]['admin'] === 'Y'"><CircleCheckFilled /></el-icon>
                    <el-icon color="red" v-else><CircleCloseFilled /></el-icon>
                  </template>
                </el-table-column>
                <el-table-column label="用户组">
                  <template #default="scope">
                    <el-tag :type="user[scope.$index].admin === 'Y' ? 'success' : 'primary'">{{ user[scope.$index].groupName }}</el-tag>
                  </template>
                </el-table-column>
                <el-table-column label="用户状态">
                  <template #default="scope">
                    <el-tag v-if="user[scope.$index]['ban'] === 'Y'" type="danger">已封禁</el-tag>
                    <el-tag v-else-if="user[scope.$index]['verified'] !== 'Y'" type="warning">邮箱未验证</el-tag>
                    <el-tag v-else type="success">正常</el-tag>
                  </template>
                </el-table-column>
                <el-table-column label="注册时间" prop="create" />
                <el-table-column label="操作">
                  <template #default="scope">
                    <el-button type="primary">编辑</el-button>
                    <el-button type="danger">删除</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </el-form-item>
          </el-form>
        </el-main>
      </el-scrollbar>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
h2 {
  margin-top: 0;
}
</style>