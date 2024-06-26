import {ElMessageBox} from "element-plus";

function confirm(message, title, action={confirm: _=>_, cancel: _=>_, close: _=>_}) {
    ElMessageBox.confirm(message, title, {
        type: "info"
    }).then(_action => {
        if (_action === "confirm") {
            action.confirm()
        } else if (_action === "cancel") {
            action.cancel()
        } else if (_action === "close") {
            action.close()
        }
    })
}

export default confirm