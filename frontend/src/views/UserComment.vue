<script setup>
import UserSidebar from "@/components/UserSidebar.vue";
import UserTop from "@/components/UserTop.vue";
import {onMounted, ref} from "vue";
import {Get} from "@/lib/axiosLib.js";
import {UserUrl} from "@/api/url.js";
import {alertError, axiosError} from "@/lib/requestAlert.js";
import router from "@/router/index.js";

const userInfo = ref({});
const mainLoading = ref(true)
const commentLoading = ref(true)
const pageSize = ref(20)
const pageNum = ref(1)
const total = ref(0);
const commentList = ref([])

onMounted(()=>{
  Get(UserUrl.infoUrl, {}, {
    ok(_, data) {
      userInfo.value = data
    },
    bad(res) {
      alertError(res, "用户信息获取失败", ()=>router.push("/login"))
    },
    error(err) {
      axiosError(err, "数据获取失败", ()=>location.reload())
    },
    final() {
      mainLoading.value = false
    }
  })
  getList()
})
function getList() {
  commentLoading.value = true
  Get(UserUrl.commentUrl, {
    pageSize: pageSize.value,
    pageNum: pageNum.value
  }, {
    ok(res, data) {
      total.value = res.count
      commentList.value = data
    },
    bad(res) {
      alertError(res, "数据获取失败")
    },
    error(err) {
      axiosError(err, "数据获取失败")
    },
    final() {
      commentLoading.value = false
    }
  })
}
function editComment(index) {

}
function deleteComment(index) {

}
function restoreComment(index) {

}
</script>

<template>
  <el-container>
    <el-aside style="width: unset">
      <UserSidebar default-active="6" />
    </el-aside>
    <el-container>
      <el-header style="height: 30px;">
        <UserTop title="评论列表" />
      </el-header>
      <el-main>
        <h2>评论列表</h2>
        <el-form label-position="top" v-loading="mainLoading">
          <el-form-item label="当前用户">
            <el-text type="info">{{ userInfo.nickname }}（{{ userInfo.username }}）</el-text>
          </el-form-item>
          <el-form-item label="评论列表">
            <el-table :data="commentList">
              <el-table-column label="id" prop="commentId" />
              <el-table-column label="图片预览">
                <template #default="scope">
                  <el-image :src="commentList[scope.$index].url" />
                </template>
              </el-table-column>
              <el-table-column label="图片名称" prop="name" />
              <el-table-column label="评论内容" prop="comment" />
              <el-table-column label="评论状态">
                <el-tag type="success">正常</el-tag>
              </el-table-column>
              <el-table-column label="操作">
                <template #default="scope">
                  <el-button type="primary" @click="editComment(scope.$index)">编辑</el-button>
                  <el-button type="danger" @click="deleteComment(scope.$index)">删除</el-button>
                  <el-button type="warning" @click="restoreComment(scope.$index)">还原</el-button>
                </template>
              </el-table-column>
            </el-table>
          </el-form-item>
        </el-form>
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped lang="scss">
h2 {
  margin-top: 0;
}
</style>