const isDev = process.env.NODE_ENV === "development"
const baseUrl = isDev ? "http://127.0.0.1:8000" : location.origin


const userUrl = baseUrl + "/user"
const picsUrl = baseUrl + "/pics"


const UserUrl = {
    loginUrl: userUrl + "/login",
    registerUrl: userUrl + "/register",
    sendCodeUrl: userUrl + "/sendCode",
    forgetUrl: userUrl + "/forget",
    infoUrl: userUrl + "/info",
    verifyUrl: userUrl + "/verify",
    passwordUrl: userUrl + "/password",
    logoutUrl: userUrl + "/logout",
    picsUrl: userUrl + "/pics"
}

const PicsUrl = {
    picsUrl: picsUrl,
    listUrl: picsUrl + "/list",
    scoreUrl: picsUrl + "/score",
    imageUrl: picsUrl + "/image"
}

export { UserUrl, PicsUrl }