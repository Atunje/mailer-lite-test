import Vue from "vue";
import * as ENDPOINTS from "./endpoints";

export default class FieldService {


    static async getFields() {

        const { data } = await Vue.http.get(ENDPOINTS.FIELDS);
        return data;

    }


    static async createField(field_data) {

        const { data } = await Vue.http.post(ENDPOINTS.CREATE_FIELD, field_data);
        return data;

    }


    static async updateField(id, field_data) {

        const { data } = await Vue.http.put(ENDPOINTS.UPDATE_FIELD(id), field_data);
        return data;

    }

}