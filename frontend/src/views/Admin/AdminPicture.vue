<script setup>
import AdminSidebar from "@/components/AdminSidebar.vue";
import AdminTop from "@/components/AdminTop.vue";
import { onMounted, ref } from 'vue'
import {Get} from "@/lib/axiosLib.js";
import {AdminUrl} from "@/api/url.js";
import {alertError, axiosError} from "@/lib/requestAlert.js";

const pic = ref([])
const picList = ref([])
const userList = ref([])
const mainLoading = ref(true)
const pageSize = ref(20)
const pageNum = ref(1)
const pageTotal = ref(0)
const selectedUser = ref("")

const reload = () => location.reload()

onMounted(()=>{
  getUserList()
  getList()
})
function getUserList() {
  Get(AdminUrl.userUrl, {}, {
    ok(_, data) {
      data.forEach(item=>{
        userList.value.push({
          text: item.nickname,
          value: item.userId
        })
      })
    },
    bad(res) {
      alertError(res, "用户列表获取失败", ()=>location.reload())
    },
    error(err) {
      axiosError(err, "用户列表获取失败", ()=>location.reload())
    }
  })
}
function getList() {
  Get(AdminUrl.pictureUrl, {
    pageSize: 20,
    pageNum: 1
  }, {
    ok(res, data) {
      picList.value = []
      pic.value = data
      pageTotal.value = res.data.count
      pic.value.forEach(item=>{
        picList.value.push(item.url)
      })
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
}
function userFilter(value, row) {
  return row.userId === value
}
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
        <h2>图片管理</h2>
        <el-form label-position="top">
          <el-form-item label="图片列表">
            <el-table :data="pic" max-height="calc(100vh - 210px)">
              <el-table-column fixed label="图片id" prop="picId" width="100" />
              <el-table-column fixed label="图片名称" prop="name" width="200" />
              <el-table-column label="图片描述" prop="description" width="300" />
              <el-table-column label="图片" width="150">
                <template #default="scope">
                  <div style="display: flex; align-items: center">
                    <el-image :src="pic[scope.$index].url" :preview-src-list="picList" preview-teleported :initial-index="scope.$index" />
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="图片压缩方式" width="150">
                <template #default="scope">
                  <el-tag type="warning">未压缩</el-tag>
                </template>
              </el-table-column>
              <el-table-column label="上传用户" :filters="userList" :filter-method="userFilter" width="200">
                <template #default="scope">
                  <el-text>{{ pic[scope.$index].nickname }}（{{ pic[scope.$index].userId }}）</el-text>
                </template>
              </el-table-column>
              <el-table-column label="图片状态">
                <el-tag type="success">正常</el-tag>
              </el-table-column>
              <el-table-column label="评论数量" prop="commentCount" />
              <el-table-column label="图片评分" width="200">
                <template #default="scope">
                  <el-rate v-model="pic[scope.$index].score" show-score :score-template="pic[scope.$index].score ? pic[scope.$index].score + '分' : '无评分'" disabled />
                </template>
              </el-table-column>
              <el-table-column label="操作" width="200" fixed="right">
                <template #default="scope">
                  <el-button type="warning">编辑</el-button>
                  <el-button type="danger">删除</el-button>
                </template>
              </el-table-column>
            </el-table>
          </el-form-item>
        </el-form>
        <el-pagination :total="pageTotal"
                       v-model:page-size="pageSize" v-model:current-page="pageNum"
                       :page-sizes="[20, 40, 60]" layout="sizes, prev, pager, next, jumper, total"
                       @current-change="reload" @size-change="getList" />
      </el-main>
    </el-container>
  </el-container>
  <el-dialog :model-value="false">
    <template #header>
      <span>编辑图片</span>
    </template>
    <template #default>
      <el-form>
        <el-form-item label="图片id">
          <el-input model-value="1" disabled />
        </el-form-item>
      </el-form>
    </template>
    <template #footer>
      <el-button>取消</el-button>
      <el-button>保存</el-button>
    </template>
  </el-dialog>
</template>

<style scoped lang="scss">
h2 {
  margin-top: 0;
}
</style>