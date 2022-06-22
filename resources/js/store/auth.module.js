import { AuthService } from "../services";

export default {
    namespaced: true,

    state: {
        user: null,
        token: null,
    },

    getters: {
        authenticated(state) {
            return state.token != null ? true : false;
        },

        user(state) {
            return state.user;
        },
    },

    mutations: {
        SET_TOKEN(state, data) {
            state.token = data;
        },
        SET_USER(state, data) {
            state.user = data;
        },
    },

    actions: {

        async login({ dispatch }, { email, password }) {
            let { data } = await AuthService.login({ email, password });

            //get the user info
            return dispatch('attempt', data.access_token);
        },

        async attempt({ commit, state }, token) {

            if (token != undefined) {

                //set the token if token is not null
                if (token) commit('SET_TOKEN', token);
                localStorage.setItem('authToken', token);

                //quit if there is no token in the state
                if (!state.token) return;

                //get user info
                try {
                    const { data } = await AuthService.getProfile();
                    commit('SET_USER', data.user);

                    return true;

                } catch (e) {
                    commit('SET_USER', null);
                    commit('SET_TOKEN', null);

                    return false;
                }
            }
        },

        logout({ commit }) {
            AuthService.logout().then(() => {
                commit('SET_USER', null);
                commit('SET_TOKEN', null);
                localStorage.clear();
            })
        }
    }
};