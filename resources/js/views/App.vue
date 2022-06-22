<template>
    <div id="app" class="bg-color">

        <div v-if="appLoading && !loadingError">
          <div class="text-center" style="margin-top:48vh">
            <b-spinner variant="success" type="grow" label="Spinning"></b-spinner>
          </div>
        </div>

        <div v-else-if="loadingError" class="loading-app">
          <div style="margin-top:35vh">
            <b-button @click="loadUser" variant="light" class="w-100">
              Network error, click to retry
            </b-button>
          </div>
        </div>

        <component :is="layout" v-else>
          <router-view />
        </component>

    </div>
</template>

<script>

import { mapActions } from "vuex";

export default {
    name: "app",
    data() {
        return {
            appLoading: false,
            loadingError:false
        };
    },
    async created() {
        await this.setState();
    },
    computed: {
      layout() {
        return (this.$route.meta.layout || 'public') + '-layout'
      }
    },
    methods: {
      ...mapActions({
        attempt: "auth/attempt"
      }),

      async setState() {
        this.loadingError = false;
        this.appLoading = true;
        try {
          await this.attempt();
        } catch (error) {
          if (
            error.name == "NetworkException" &&
            ![401, 403].includes(error.status)
          )
            this.loadingError = true;
        } finally {
          this.appLoading = false;
        }
      }
    }

};
</script>

<style>

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

</style>
