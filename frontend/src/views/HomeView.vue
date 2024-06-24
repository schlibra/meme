<script setup>
import {onMounted, ref} from "vue";
import router from "@/router/index.js";
import axios from "axios";
import {UserUrl} from "@/api/url.js";

const token = ref(localStorage.getItem("token"))
const userInfo = ref({})
const admin = ref(false)
const strings = ref(["IURT meme 2.0"])
const currentPage = ref(1)
const pageSize = ref(20)
const value = ref(5.0)
const isShow = ref(false)

onMounted(()=>{
  if (token) {
    axios.post(UserUrl.getInfoUrl, {}, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    }).then(res=>{
      if (res.data.code === 200) {
        userInfo.value = res.data.data
      } else {
        token.value = "";
        localStorage.removeItem("token")
      }
    }).catch(err=>{
      console.log(err)
      token.value = ""
      localStorage.removeItem("token")
    })
  }
})

function sizeChange() {

}
function pageChange() {

}
function randomImg() {

}
function gotoLogin() {
  router.push({
    path: "/login"
  })
}
function gotoUser() {

}
function uploadImg() {

}
function gotoAdmin() {

}
</script>

<template>
  <div class="typed">
    <vuetyped :strings="strings" :fade-out="true" :loop="true" cursor-char="_">
      <h1 class="typing"></h1>
    </vuetyped>
    <h3 class="center" v-if="token">欢迎用户：{{ userInfo["nickname"] }}</h3>
  </div>
  <div class="buttons">
    <el-button type="primary" @click="randomImg">随机梗图</el-button>
    <el-button type="info" v-if="!token" @click="gotoLogin">登录账号</el-button>
    <el-button type="primary" v-if="token" @click="gotoUser">个人中心</el-button>
    <el-button type="primary" v-if="token" @click="uploadImg">上传图片</el-button>
    <el-button type="warning" v-if="admin" @click="gotoAdmin">系统设置</el-button>
  </div>
  <el-divider />
  <el-scrollbar height="60vh">
    <el-row :gutter="8">
      <el-col :span="6" v-for="i in 20">
        <el-card class="img-card">
          <template #default>
            <el-image src="https://liukaili.netlify.app/favicon1.ico"></el-image>
          </template>
          <template #footer>
            <el-text :type="'primary'">梗图描述</el-text>
            <br />
            <el-link>查看详情</el-link>
            <br />
            <el-rate
              v-model="value"
              disabled
              show-score
              text-color="#ff9900"
              score-template="{value} points"
            />
            <br />
            <el-button type="primary" style="margin-left: 3px" @click="isShow = true">
              点我评分
            </el-button>
            
            <br />
            <el-drawer v-model="isShow" title="请在这里评分" >
              <el-rate
              v-model="value" 
              size="large" 
              allow-half 
              :texts="['使', '就这', '假搜搜', '哟西', '像啊很像啊（赞赏']" 
              show-text 
              clearable
              />
            </el-drawer>
            
          </template>
        </el-card>
      </el-col>
    </el-row>
  </el-scrollbar>
  <div class="center">
    <el-pagination
        :total="200" :page-size="20" :page-sizes="[20, 40, 80, 100]"
        layout="sizes, prev, pager, next, total, jumper" size="large"
        @size-change="sizeChange" @current-change="pageChange"
        v-model:current-page="currentPage" v-model:page-size="pageSize"/>
  </div>
</template>

<style scoped lang="scss">
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
</style>