import Vue from "vue";
import * as ENDPOINTS from "./endpoints";

export default class AuthService {


    static async register(userdata) {

        const { data } = await Vue.http.post(ENDPOINTS.REGISTER, userdata);
        return data;

    }


    static async login({ email, password }) {

        const { data } = await Vue.http.post(ENDPOINTS.LOGIN, { email, password });
        return data;

    }


    static async getProfile() {

        const { data } = await Vue.http.get(ENDPOINTS.PROFILE);
        return data;

    }


    static async logout() {
        return await Vue.http.get(ENDPOINTS.LOGOUT);
    }


}