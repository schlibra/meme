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

const token = getToken()
const edit = ref(false)
const user = ref({sex: "", birth: 0})
const nowYear = new Date().getFullYear()
const mainLoading = ref(true)

const birth = computed({
  get() {
    let date = new Date()
    date.setFullYear(user.value.birth)
    return date
  },
  set(date) {
    user.value.birth = date.getFullYear()
  }
})

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

function update() {
  edit.value = false
  loading.value = true
  axios.put(UserUrl.infoUrl, user.value, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }).then(res=>{
    if (res.data.code === 200) {
      alertSuccess(res, "更新成功")
    } else {
      alertError(res, "更新失败")
    }
  }).catch(err=>{
    axiosError(err, "更新失败");
  }).finally(()=> loading.value = false)
}


</script>

<template>
  <el-container>
    <el-aside style="width: unset;">
      <UserSidebar default-active="1" />
    </el-aside>
    <el-container>
      <el-header height="30px">
        <UserTop title="基本信息" />
      </el-header>
      <el-scrollbar height="calc(100vh - 30px)">
        <el-main>
          <h2>基本信息</h2>
          <el-form label-position="top">

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