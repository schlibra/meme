<script setup>
import {onMounted, ref} from "vue";
import router from "@/router/index.js";
import axios from "axios";
import {PicsUrl, UserUrl} from "@/api/url.js";
import {alertError, alertSuccess, axiosError} from "@/lib/requestAlert.js";
import {getToken, removeToken} from "@/lib/tokenLib.js";
import confirm from "@/lib/confirmLib.js";
import displayUtil from "@/lib/displayUtil.js";

const token = ref(getToken())
const userInfo = ref({})
const strings = ref(["IURT meme 2.0"])
const currentPage = ref(1)
const pageSize = ref(20)
const picList = ref([])
const imgList = ref([]);
const totalCount = ref(0);
const mainLoading = ref(true)
const showDrawer = ref(false)
const imgDetail = ref({})
const imgDetailScore = ref(0)
const detailLoading = ref(false)

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
  axios.get(PicsUrl.listUrl, {
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
      alertError(res, "数据获取失败", ()=>location.reload())
    }
  }).catch(err=>{
    axiosError(err, "数据获取失败", ()=>location.reload())
  }).finally(() => mainLoading.value = false)
})
function reload() {
  mainLoading.value = true
  axios.get(PicsUrl.listUrl + `?pageSize=${pageSize.value}&pageNum=${currentPage.value}`, {
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
    axiosError(err, "数据获取失败", ()=>location.reload())
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
  imgDetail.value = picList.value[index]
  showDrawer.value = true
  console.log(imgDetail)
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
      alertSuccess(res, "上传成功", ()=>{
        reload()
        uploadFile.value["files"] = [];
        uploadName.value = ""
        uploadDescription.value = ""
      })
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
      mainLoading.value = true
      axios.post(UserUrl.logoutUrl, {}, {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      }).finally(()=>{
        mainLoading.value = false
        token.value = ""
        userInfo.value = {}
        confirm("已退出登录，是否前往登录页面", "前往登录", {
          confirm() {
            gotoLogin()
          },
          cancel: reload,
          close: reload
        })
      })
    }
  })
}
function submitScore() {
  if (!token.value) {
    alertError("没有登录无法评分", "评分失败")
    return
  }
  let score = imgDetailScore.value
  let pic = imgDetail.value["id"]
  if (score) {
    confirm(`确定为图片“${imgDetail.value.name}”打${score}分吗`, "打分确认", {
      confirm() {
        detailLoading.value = true
        axios.post(PicsUrl.scoreUrl, {
          score,
          pic
        }, {
          headers: {
            Authorization: `Bearer ${token.value}`
          }
        }).then(res=>{
          if (res.data.code === 200) {
            alertSuccess(res, "评分成功", () => {
              reload()
              imgDetail.value["scored"] = "Y"
              imgDetail.value["myScore"] = score
            })
          } else {
            alertError(res, "评分失败")
          }
        }).catch(err=>{
          axiosError(err, "评分失败")
        }).finally(()=>detailLoading.value = false)
      }
    })
  } else {
    alertError("请先选择评分再点击提交", "评分失败")
  }
}
</script>

<template>
  <div class="typed">
    <vuetyped :strings="strings" :fade-out="true" :loop="true" cursor-char="_">
      <h1 class="typing"></h1>
    </vuetyped>
    <h3 class="center" v-if="token">欢迎用户：{{ userInfo["nickname"] }}（{{ userInfo["username"] }} - {{ userInfo["groupName"] }}）</h3>
  </div>
  <div class="buttons hidden-xs-only">
    <el-button type="primary" @click="randomImg">随机梗图</el-button>
    <el-button type="info" v-if="!token" @click="gotoLogin">登录账号</el-button>
    <el-button type="primary" v-if="token" @click="gotoUser">个人中心</el-button>
    <el-button type="primary" v-if="token && userInfo['upload'] === 'Y'" @click="uploadDialog = true">上传图片</el-button>
    <el-button type="warning" v-if="userInfo['admin'] === 'Y'" @click="gotoAdmin">系统设置</el-button>
    <el-button type="danger" v-if="token" @click="logout">退出登录</el-button>
  </div>
  <div class="buttons hidden-sm-and-up">
    <el-button size="small" type="primary" @click="randomImg">随机梗图</el-button>
    <el-button size="small" type="info" v-if="!token" @click="gotoLogin">登录账号</el-button>
    <el-button size="small" type="primary" v-if="token" @click="gotoUser">个人中心</el-button>
    <el-button size="small" type="primary" v-if="token && userInfo['upload'] === 'Y'" @click="uploadDialog = true">上传图片</el-button>
    <el-button size="small" type="warning" v-if="userInfo['admin'] === 'Y'" @click="gotoAdmin">系统设置</el-button>
    <el-button size="small" type="danger" v-if="token" @click="logout">退出登录</el-button>
  </div>
  <el-divider />
  <el-scrollbar :height="displayUtil.isXs ? token ? 'calc(100vh - 420px)' : 'calc(100vh - 360px)' : token ? 'calc(100vh - 340px)' : 'calc(100vh - 280px)'">
    <el-row :gutter="8" class="main" v-loading="mainLoading">
      <el-col  :xs="24" :sm="12" :md="8" :lg="6" :xl="6" v-for="(img, index) in picList" :key="img.id">
        <el-card class="img-card">
          <template #header>
            <el-text truncated>{{ img.name }}</el-text>
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
              fit="contain">
              <template #error>
                <el-empty description="图片加载失败咯" />
              </template>
            </el-image>
          </template>
          <template #footer>
            <el-space direction="vertical">
              <el-rate
                v-model="img.score"
                disabled
                show-score
                text-color="#ff9900"
                :score-template="img.score === 0 ? '暂无评分' : `${img.score}分`" />
              <el-button
                  type="primary"
                  @click="openDetail(index)">查看详情</el-button>
            </el-space>
          </template>
        </el-card>
      </el-col>
    </el-row>
  </el-scrollbar>
  <div class="center">
    <el-pagination
        :total="totalCount" :page-size="20" :page-sizes="[20, 40, 80, 100]"
        :layout="displayUtil.isXs ? 'sizes, prev, pager, next, total' : 'sizes, prev, pager, next, total, jumper'" :size="displayUtil.isXs ? 'small' : 'default'"
        @size-change="reload" @current-change="reload"
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
  <el-drawer :size="displayUtil.isXs ? '75%' : '50%'" v-model="showDrawer">
    <template #header>
      <span>图片详情</span>
    </template>
    <template #default>
      <el-form label-width="auto" v-loading="detailLoading">
        <el-form-item label="上传者">
          <el-text type="info">{{ imgDetail.nickname }}</el-text>
        </el-form-item>
        <el-form-item label="图片描述">
          <el-text type="info">{{ imgDetail.description }}</el-text>
        </el-form-item>
        <el-form-item label="我的评分" v-if="imgDetail['scored'] === 'Y'">
          <el-rate
              v-model="imgDetail['myScore']" allow-half disabled
              score-template="{value} 分"/>
        </el-form-item>
        <el-form-item label="为图片评分" v-else>
          <el-space>
            <el-rate
                v-model="imgDetailScore" allow-half :disabled="!token"
                :texts="['极差', '差', '一般', '好', '很好']" show-text/>
            <el-button v-if="token" type="primary" @click="submitScore">提交评分</el-button>
          </el-space>
        </el-form-item>
      </el-form>
    </template>
    <template #footer>
      <el-space>
        <el-input placeholder="输入评论内容" />
        <el-button type="primary">发送评论</el-button>
      </el-space>
    </template>
  </el-drawer>
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
  margin-left: calc(50vw - 150px);
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
  height: 21vw;
}
.el-pagination {
  margin-top: 16px;
  margin-bottom: 32px;
  margin-left: 16px;
}
</style>