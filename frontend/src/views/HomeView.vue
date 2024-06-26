<script setup>
import {onMounted, ref} from "vue";
import router from "@/router/index.js";
import axios from "axios";
import {PicsUrl, UserUrl} from "@/api/url.js";
import {alertError, alertSuccess, axiosError} from "@/lib/requestAlert.js";
import {getToken, removeToken} from "@/lib/tokenLib.js";
import {confirm} from "@/lib/confirmLib.js";

const token = ref(getToken())
const userInfo = ref({})
const strings = ref(["IURT meme 2.0"])
const currentPage = ref(1)
const pageSize = ref(20)
const picList = ref([])
const imgList = ref([]);
const totalCount = ref(0);
const mainLoading = ref(true)

const uploadDialog = ref(false);
const uploadName = ref("");
const uploadDescription = ref("");
const uploadFile = ref(null);
const uploadLoading = ref(false)

onMounted(()=>{
  if (token.value) {
    axios.get(UserUrl.infoUrl, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    }).then(res=>{
      if (res.data.code === 200) {
        userInfo.value = res.data.data
      } else {
        token.value = "";
        removeToken()
      }
    }).catch(() => removeToken())
  }
  axios.get(PicsUrl.picsUrl, {
    headers: token.value ? {
      Authorization: `Bearer ${token.value}`
    } : {}
  }).then(res => {
    if (res.data.code === 200) {
      imgList.value = [];
      picList.value = res.data.data
      totalCount.value = res.data.total
      for (const index in res.data.data) {
        const item = res.data.data[index]
        imgList.value.push(item.url)
      }
    } else {
      alertError(res, "数据获取失败")
    }
  }).catch(err=>{
    axiosError(err, "数据获取失败", location.reload)
  }).finally(() => mainLoading.value = false)
})

function sizeChange() {
  mainLoading.value = true
  axios.get(PicsUrl.picsUrl + `?pageSize=${pageSize.value}&pageNum=${currentPage.value}`, {
    headers: token.value ? {
      Authorization: `Bearer ${token.value}`
    } : {}
  }).then(res=>{
    if (res.data.code === 200) {
      imgList.value = [];
      picList.value = res.data.data
      totalCount.value = res.data.total
      for (const index in res.data.data) {
        const item = res.data.data[index]
        imgList.value.push(item.url)
      }
    } else {
      alertError(res, "数据获取失败")
    }
  }).catch(err=>{
    axiosError(err, "数据获取失败", location.reload)
  }).finally(() => mainLoading.value = false)
}
function pageChange() {
  mainLoading.value = true
  axios.get(PicsUrl.picsUrl + `?pageSize=${pageSize.value}&pageNum=${currentPage.value}`, {
    headers: token.value ? {
      Authorization: `Bearer ${token.value}`
    } : {}
  }).then(res=>{
    if (res.data.code === 200) {
      imgList.value = [];
      picList.value = res.data.data
      totalCount.value = res.data.total
      for (const index in res.data.data) {
        const item = res.data.data[index]
        imgList.value.push(item.url)
      }
    } else {
      alertError(res, "数据获取失败")
    }
  }).catch(err=>{
    axiosError(err, "数据获取失败", location.reload)
  }).finally(()=> mainLoading.value = false)
}
function randomImg() {

}
function gotoLogin() {
  router.push("/login")
}
function gotoUser() {
  router.push("/user/basic")
}
function gotoAdmin() {

}
function openDetail(index) {

}
function uploadSubmit() {
  let image = uploadFile.value["files"][0];
  let name = uploadName.value
  let description = uploadDescription.value
  uploadLoading.value = true
  axios.post(PicsUrl.picsUrl, {
    image,
    name,
    description
  }, {
    headers: {
      Authorization: `Bearer ${token.value}`,
      "Content-Type": "multipart/form-data"
    }
  }).then(res=>{
    if (res.data.code === 200) {
      alertSuccess(res, "上传成功", location.reload)
    } else {
      alertError(res, "上传失败")
    }
  }).catch(err=>{
    axiosError(err, "上传失败")
  }).finally(()=> uploadLoading.value = false)
}
function logout() {
  confirm("是否退出当前账号", "退出账号", {
    confirm() {
      removeToken()
      token.value = ""
      userInfo.value = {}
      confirm("已退出登录，是否前往登录页面", "前往登录", {
        confirm() {
          gotoLogin()
        }
      })
    }
  })
}
</script>

<template>
  <div class="typed">
    <vuetyped :strings="strings" :fade-out="true" :loop="true" cursor-char="_">
      <h1 class="typing"></h1>
    </vuetyped>
    <h3 class="center" v-if="token">欢迎用户：{{ userInfo["nickname"] }}（{{ userInfo["username"] }} - {{ userInfo["groupName"] }}）</h3>
  </div>
  <div class="buttons">
    <el-button type="primary" @click="randomImg">随机梗图</el-button>
    <el-button type="info" v-if="!token" @click="gotoLogin">登录账号</el-button>
    <el-button type="primary" v-if="token" @click="gotoUser">个人中心</el-button>
    <el-button type="primary" v-if="token && userInfo['upload'] === 'Y'" @click="uploadDialog = true">上传图片</el-button>
    <el-button type="warning" v-if="userInfo['admin'] === 'Y'" @click="gotoAdmin">系统设置</el-button>
    <el-button type="danger" v-if="token" @click="logout">退出登录</el-button>
  </div>
  <el-divider />
  <el-scrollbar height="60vh">
    <el-row :gutter="8" class="main" v-loading="mainLoading">
      <el-col :span="6" v-for="(img, index) in picList" :key="img.id">
        <el-card class="img-card">
          <template #header>
            <span>{{ img.name }}</span>
          </template>
          <template #default>
            <el-image
              class="item-img"
              :src="img.url"
              :zoom-rate="1.2"
              :max-scale="7"
              :min-scale="0.2"
              :preview-src-list="imgList"
              :initial-index="index"
              fit="contain" />
          </template>
          <template #footer>
            <el-rate
              v-model="img.score"
              disabled
              show-score
              text-color="#ff9900"
              score-template="{value} 分" />
            <br />
            <el-button
                type="primary"
                style="margin-left: 3px"
                @click="openDetail(index)">查看详情</el-button>
          </template>
        </el-card>
      </el-col>
    </el-row>
  </el-scrollbar>
  <div class="center">
    <el-pagination
        :total="totalCount" :page-size="20" :page-sizes="[20, 40, 80, 100]"
        layout="sizes, prev, pager, next, total, jumper" size="large"
        @size-change="sizeChange" @current-change="pageChange"
        v-model:current-page="currentPage" v-model:page-size="pageSize"/>
  </div>

  <el-dialog v-model="uploadDialog" v-loading="uploadLoading">
    <template #header>
      <span>上传图片</span>
    </template>
    <template #default>
      <el-form label-position="top">
        <el-form-item label="图片名称">
          <el-input v-model="uploadName" />
        </el-form-item>
        <el-form-item label="图片描述">
          <el-input type="textarea" v-model="uploadDescription" />
        </el-form-item>
        <el-form-item label="上传图片">
          <input type="file" ref="uploadFile" />
        </el-form-item>
      </el-form>
    </template>
    <template #footer>
      <el-button @click="uploadDialog = false">取消</el-button>
      <el-button @click="uploadSubmit" type="primary">提交</el-button>
    </template>
  </el-dialog>
</template>

<style scoped lang="scss">
.main {
  padding-left: 16px;
  padding-right: 16px;
}
.center {
  text-align: center;
}
.typing {
  margin-left: 35vw;
  font-size: 40px;
  height: 64px;
}
.buttons {
  text-align: center;
}
.img-card {
  margin-bottom: 8px;
}
.item-img {
  width: 20vw;
  height: 20vw;
}
</style>