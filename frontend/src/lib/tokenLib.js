function setToken(token) {
    let _token = typeof token === "string" ? token : token.data.token
    localStorage.setItem("token", token)
}
function getToken() {
    return localStorage.getItem("token")
}
function removeToken() {
    localStorage.removeItem("token")
}
export { setToken, getToken, removeToken }