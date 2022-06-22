const actions = {
    async updateProfile({ commit }, profileData) {
        commit("updateUser", profileData, { root: true });
    }
};

export default {
    namespaced: true,
    state: {},
    mutations: {},
    actions
};