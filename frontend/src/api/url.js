const isDev = process.env.NODE_ENV === "development"
const baseUrl = isDev ? "http://127.0.0.1:8000" : location.origin


const userUrl = baseUrl + "/user"
const picsUrl = baseUrl + "/pics"
const CaptchaUrl = baseUrl + "/captcha"

const UserUrl = {
    loginUrl: userUrl + "/login",
    registerUrl: userUrl + "/register",
    sendCodeUrl: userUrl + "/sendCode",
    forgetUrl: userUrl + "/forget",
    infoUrl: userUrl + "/info",
    verifyUrl: userUrl + "/verify",
    passwordUrl: userUrl + "/password",
    logoutUrl: userUrl + "/logout",
    picsUrl: userUrl + "/pics",
    scoreUrl: userUrl + "/scores",
    commentUrl: userUrl + "/comments"
}

const PicsUrl = {
    picsUrl: picsUrl,
    uploadUrl: picsUrl + "/pic",
    listUrl: picsUrl + "/pic",
    scoreUrl: picsUrl + "/score",
    imageUrl: picsUrl + "/image",
    commentUrl: picsUrl + "/comment",
    randomUrl: picsUrl + "/random"
}

export { UserUrl, PicsUrl, CaptchaUrl }