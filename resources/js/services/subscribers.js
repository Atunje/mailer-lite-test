import Vue from "vue";
import * as ENDPOINTS from "./endpoints";

export default class SubscriberService {


    static async getSubscribers(filters) {

        const { data } = await Vue.http.post(ENDPOINTS.SUBSCRIBERS, filters);
        return data;

    }


    static async createSubscriber(subscriber_data) {

        const { data } = await Vue.http.post(ENDPOINTS.CREATE_SUBSCRIBER, subscriber_data);
        return data;

    }


    static async getSubscriber(id) {

        const { data } = await Vue.http.get(ENDPOINTS.GET_SUBSCRIBER(id));
        return data;

    }

    static async updateSubscriber(id, subcriber_data) {

        const { data } = await Vue.http.put(ENDPOINTS.UPDATE_SUBSCRIBER(id), subcriber_data);
        return data;

    }

    static async deleteSubscriber(id) {

        const { data } = await Vue.http.delete(ENDPOINTS.DELETE_SUBSCRIBER(id));
        return data;

    }


    static async changeState(subscriber_ids, new_state) {

        const { data } = await Vue.http.post(ENDPOINTS.CHANGE_STATE, { state: new_state, subscribers: subscriber_ids });
        return data;

    }


    static async deleteSubscribers(subscriber_ids) {

        const { data } = await Vue.http.post(ENDPOINTS.BULK_DELETE, { subscribers: subscriber_ids });
        return data;

    }



}