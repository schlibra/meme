const width = window.innerWidth
const displayUtil = {
    width,
    isXs: width <= 767,
    isSm: width >= 768 && width <= 991,
    isMd: width >= 992 && width <= 1199,
    isLg: width >= 1200
}

export default displayUtil