<script setup>
import {ref} from "vue";

const strings = ref(["IURT meme 2.0"])
const currentPage = ref(1)
const pageSize = ref(20)
const value = ref(5.0)
const isShow = ref(false)
function sizeChange() {

}
function pageChange() {

}
</script>

<template>
  <div class="typed">
    <vuetyped :strings="strings" :fade-out="true" :loop="true" cursor-char="_">
      <h1 class="typing"></h1>
    </vuetyped>
  </div>
  <div class="buttons">
    <el-button type="primary">随机梗图</el-button>
    <el-button type="info">登录账号</el-button>
    <el-button type="primary">个人中心</el-button>
    <el-button type="primary">上传图片</el-button>
    <el-button type="warning">系统设置</el-button>
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
main {
  width: 100vw;
  padding: 24px;
}
.typing {
  margin-left: 35vw;
  font-size: 40px;
  height: 64px;
}

//.typed {
//  width: 100vw;
//}
.buttons {
  text-align: center;
}
.img-card {
  margin-bottom: 8px;
}
</style>