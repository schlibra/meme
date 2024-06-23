import { createApp } from 'vue'
import ElementPlus from 'element-plus'
import vuetyped from 'vue3typed'
import "element-plus/dist/index.css"
import "element-plus/theme-chalk/dark/css-vars.css"
import zhCn from "element-plus/es/locale/lang/zh-cn"
import App from './App.vue'
import router from './router'
import { useDark } from "@vueuse/core"

const app = createApp(App)
const isDark = useDark()
if (isDark.value) {
    document.body.classList.add("dark")
}

app.use(router)
app.use(ElementPlus, {
    locale: zhCn
})
app.use(vuetyped)

app.mount('#app')
