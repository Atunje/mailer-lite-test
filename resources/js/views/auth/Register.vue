<template>

    <b-form
        @submit.prevent="submit"
        class="text-left"
        autocomplete="off">

        <b-row class="w-50 shadow-lg border p-4" style="margin:auto">
            <b-col class="text-left mb-4" cols="12">
                <h2 class="text-primary font-weight-bold">Let's<br />get started</h2>
            </b-col>
            <b-col cols="12">
                <b-alert variant="warning" :show="status_mesg!=''">{{ status_mesg }}</b-alert>
                <b-alert variant="success" :show="register_success">Registration Successful! <router-link to="login">Login</router-link></b-alert>
            </b-col>
            <b-col cols="12" class="pr-md-2 mb-3">
                <b-form-input
                    class="rounded-lg p-4"
                    type="text"
                    v-model="form.name"
                    placeholder="Full Name"
                    trim
                    required>
                </b-form-input>
            </b-col>
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <b-form-input
                    class="rounded-lg p-4"
                    type="email"
                    v-model="form.email"
                    placeholder="Email Address"
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
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-4">
                <b-form-input
                    class="rounded-lg p-4"
                    type="password"
                    v-model="form.confirmpassword"
                    placeholder="Confirm Password"
                    trim
                    required>
                </b-form-input>
            </b-col>
            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-3">
                <StatefulButton
                    :loading="submitting"
                    loadingText="Processing..."
                    text="Signup"
                    elementClass="w-100 rounded-lg py-2"
                />
            </b-col>

            <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-4">
                <small>Already have an account?</small> <router-link to="/login" class="small text-secondary text-weight-bold">Login</router-link>
            </b-col>
        </b-row>

    </b-form>

</template>

<script>

    import StatefulButton from "../../components/actions/StatefulButton.vue";
    import AuthService from '../../services/auth';


    export default {
        name: "register",
        components: {
            StatefulButton
        },
        data(){
            return {
                form: {},
                submitting: false,
                status_mesg:'',
                register_success:false
            }
        },
        methods : {
            async submit() {

                if(this.form.password != this.form.confirmpassword) {
                    this.status_mesg = 'Password provided do not match';
                } else {
                    this.submitting = true;
                    this.status_mesg = '';
                    AuthService.register(this.form).then((response) => {
                        this.submitting = false;
                        if(response.status) {
                            this.register_success = true;
                            this.form = {};
                        } else {
                            this.status_mesg = response.message;
                        }
                    }, (error) => {
                        console.log(error)
                        this.status_mesg = error.message;
                    });
                }

            },
        },
       
    }
    
</script>

<style scoped>

   
    
</style>