import {ElMessageBox} from "element-plus";

function alertSuccess(res, title="操作成功", callback=_=>_) {
    let message = typeof res === "string" ? res : res.data["msg"]
    ElMessageBox.alert(message, title, {
        type: "success"
    }).then(callback)
}
function alertError(res, title="操作失败", callback=_=>_) {
    let message = typeof res === "string" ? res : res.data["msg"]
    ElMessageBox.alert(message, title, {
        type: "error"
    }).then(callback)
}
function axiosError(err, title="请求失败", callback=_=>_) {
    let message;
    if (err.request.status > 0) {
        message = JSON.parse(err.request.responseText)["message"]
    } else {
        message = err.message
    }
    ElMessageBox.alert(message, title, {
        type: "error"
    }).then(callback)
}

export { alertSuccess, alertError, axiosError }