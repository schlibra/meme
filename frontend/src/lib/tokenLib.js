function setToken(token) {
    let _token = typeof token === "string" ? token : token.data.token
    localStorage.setItem("token", _token)
}
function getToken() {
    return localStorage.getItem("token")
}
function removeToken() {
    localStorage.removeItem("token")
}
globalThis.getToken = getToken
export { setToken, getToken, removeToken }