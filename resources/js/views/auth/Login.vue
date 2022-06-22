<template>

    <b-form
        @submit.prevent="submit"
        autocomplete="off">

        <b-row class="w-50 shadow-lg border p-4" style="margin:auto">
            <b-col class="text-left mb-4 mt-3" cols="12" sm="12" md="12" lg="12" xl="12">
                <h2 class="text-primary font-weight-bold">Login<br />to continue</h2>
            </b-col>
            <b-col cols="12">
                <b-alert variant="warning" :show="status_mesg!=''">{{ status_mesg }}</b-alert>
            </b-col>
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <b-form-input
                    class="rounded-lg p-4"
                    type="email"
                    v-model="form.email"
                    placeholder="Enter email address"
                    trim
                    required>
                </b-form-input>
            </b-col>
            
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <b-form-input
                    class="rounded-lg p-4"
                    type="password"
                    v-model="form.password"
                    autocomplete="new-password"
                    placeholder="Enter Password"
                    trim
                    required>
                </b-form-input>
            </b-col>
            
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <StatefulButton
                    :loading="submitting"
                    loadingText="Processing..."
                    text="Login"
                    elementClass="w-100 rounded-lg py-2"
                />
            </b-col>

            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <small>Don't have an account? <router-link to="/register" class="small text-secondary text-weight-bold">Create one</router-link></small>
            </b-col>
        </b-row>

    </b-form>
</template>


<script>

    import { mapActions } from "vuex";
    import StatefulButton from "../../components/actions/StatefulButton.vue"

    export default {
        name: "Login-Page",
        components: {
            StatefulButton
        },
        data(){
            return {
                form: {
                    email: "",
                    password: ""
                },
                submitting: false,
                status_mesg:''
            }
        },
        methods : {
            ...mapActions({
                login: "auth/login"
            }),
            async submit() {
                this.submitting = true;
                this.status_mesg = '';
                this.login({ ...this.form }).then((status) => {
                    if(status) this.$router.replace({ name: 'dashboard' });
                    this.submitting = false;
                }).catch(() => {
                    this.submitting = false;
                    this.status_mesg = "Email or Password is invalid!";
                });
            },
        }
    }
</script>

<style scoped>

   @media only screen and (max-width: 600px) {

       .login-c {
        padding:20px
       }
   }

</style>