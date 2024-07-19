const isDev = process.env.NODE_ENV === "development"
const baseUrl = isDev ? "http://127.0.0.1:8000" : location.origin

const apiUrl = baseUrl + "/api"
const userUrl = apiUrl + "/user"
const picsUrl = apiUrl + "/pics"
const CaptchaUrl = apiUrl + "/captcha"

const UserUrl = {
    loginUrl: userUrl + "/login",
    registerUrl: userUrl + "/register",
    sendCodeUrl: userUrl + "/sendCode",
    forgetUrl: userUrl + "/forget",
    infoUrl: userUrl + "/info",
    verifyUrl: userUrl + "/verify",
    passwordUrl: userUrl + "/password",
    logoutUrl: userUrl + "/logout",
    picsUrl: userUrl + "/pic",
    scoreUrl: userUrl + "/score",
    commentUrl: userUrl + "/comment"
}

const PicsUrl = {
    picsUrl: picsUrl,
    picUrl: picsUrl + "/pic",
    scoreUrl: picsUrl + "/score",
    imageUrl: picsUrl + "/image",
    commentUrl: picsUrl + "/comment",
    randomUrl: picsUrl + "/random"
}

export { UserUrl, PicsUrl, CaptchaUrl }