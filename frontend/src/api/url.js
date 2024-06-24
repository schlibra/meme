const isDev = process.env.NODE_ENV === "development"
const baseUrl = isDev ? "http://127.0.0.1:8000" : location.origin


const userUrl = baseUrl + "/user"


const UserUrl = {
    loginUrl: userUrl + "/login",
    registerUrl: userUrl + "/register"
}

export { UserUrl }