<script setup>
import { onMounted, ref } from 'vue'

const vars = ref({
  thirdPartyLoginUsername: "",
  thirdPartyLoginToken: "",
})
const thirdPartyLoginAction = localStorage.getItem("thirdPartyLoginAction")
const thirdPartyLoginName = localStorage.getItem("thirdPartyLoginName")

onMounted(()=>{
  vars.value = VARS

})
</script>

<template>

</template>

<style scoped lang="scss">

</style>