import axios from "axios";
import router from "./router";

export const http = {
    install(Vue) {
        Vue.prototype.$http = Vue.http = axios.create({
            baseURL: "http://127.0.0.1:8000/api/",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        });
    }
};

export { router };