import {ref} from "vue";

const width = ref(window.innerWidth)
const displayUtil = ref({
    width: width.value,
    isXs: width.value <= 767,
    isSm: width.value >= 768 && width.value <= 991,
    isMd: width.value >= 992 && width.value <= 1199,
    isLg: width.value >= 1200
})

export default displayUtil