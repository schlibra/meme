import axios from "axios";
import {axiosOk} from "@/lib/requestAlert.js";

function Get(url, params={}, action={ok: _=>_, bad: _=>_, error: _=>_, final:_=>_}) {
    let paramList = [];
    let config = {};
    let token = getToken()
    if (token) {
        config["headers"] = {
            Authorization: `Bearer ${token}`
        }
    }
    Object.keys(params).forEach(key=>{
        let value = params[key]
        paramList.push(`${key}=${value}`)
    })
    axios.get(url + "?" + paramList.join("&"), config).then(res=>{
        if (axiosOk(res)) {
            action.ok(res, res.data.data)
        } else {
            action.bad(res)
        }
    }).catch(err=>action.error(err)).finally(()=>action.final())
}
function Delete(url, params={}, action={ok: _=>_, bad: _=>_, error: _=>_, final:_=>_}) {
    let paramList = [];
    let config = {};
    let token = getToken()
    if (token) {
        config["headers"] = {
            Authorization: `Bearer ${token}`
        }
    }
    console.log(config)
    Object.keys(params).forEach(key=>{
        let value = params[key]
        paramList.push(`${key}=${value}`)
    })
    axios.delete(url + "?" + paramList.join("&"), config).then(res=>{
        if (axiosOk(res)) {
            action.ok(res, res.data.data)
        } else {
            action.bad(res)
        }
    }).catch(err=>action.error(err)).finally(()=>action.final())
}
function Patch(url, params={}, action={ok: _=>_, bad: _=>_, error: _=>_, final:_=>_}) {
    let config = {};
    let token = getToken()
    if (token) {
        config["headers"] = {
            Authorization: `Bearer ${token}`
        }
    }
    axios.patch(url, params, config).then(res=>{
        if (axiosOk(res)) {
            action.ok(res, res.data.data)
        } else {
            action.bad(res)
        }
    }).catch(err=>action.error(err)).finally(()=>action.final())
}

export { Get, Delete, Patch }