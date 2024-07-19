<script setup>
import AdminSidebar from "@/components/AdminSidebar.vue";
import AdminTop from "@/components/AdminTop.vue";
import { onMounted, ref } from "vue";
import {Get} from "@/lib/axiosLib.js";
import {AdminUrl} from "@/api/url.js";
import {CircleCheckFilled, CircleCloseFilled} from "@element-plus/icons-vue";

const groupList = ref([])

onMounted(()=>{
  Get(AdminUrl.groupUrl, {}, {
    ok(_, data) {
      groupList.value = data
    }
  })
})
</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <AdminSidebar default-active="3" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <AdminTop title="用户组管理" />
      </el-header>
      <el-scrollbar height="calc(100vh - 30px)">
      <el-main>
        <h2>用户组管理</h2>
        <el-form label-position="top">
          <el-form-item>
            <el-button>添加用户组</el-button>
          </el-form-item>
          <el-form-item label="用户组列表">
            <el-table :data="groupList">
              <el-table-column label="id" prop="groupId" />
              <el-table-column label="用户组名称" prop="groupName" />
              <el-table-column label="用户数量" prop="userCount" />
              <el-table-column label="是否管理员" align="center">
                <template #default="scope">
                  <el-icon color="green" v-if="groupList[scope.$index]['admin'] === 'Y'"><CircleCheckFilled /></el-icon>
                  <el-icon color="red" v-else><CircleCloseFilled /></el-icon>
                </template>
              </el-table-column>
              <el-table-column label="编辑">
                <template>
                  <el-button>编辑</el-button>
                  <el-button>删除</el-button>
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