<template>

    <div class="d-flex justify-content-center" style="border-bottom: solid 1px #888">

        <div class="w-100 top-bar-container container" style="margin:0" v-if="user!=null">
            
            <div class="bg-white top-bar d-flex justify-content-between">
                <h5 class="font-weight-bold mt-3">
                    <img :src="logo" width="100" />
                </h5>
                <div class="account">
                    <b-dropdown class="dropdown" toggle-class="text-decoration-none" variant="none" no-caret menu-class="w-100">
                        <template #button-content>
                            <div class="d-flex justify-content-between">
                                <div class="mr-2 text-right font-weight-bold mt-2" style="line-height:1">{{ user.name  }}<br /><small>Administrator</small></div>
                                <img :src="avatar" width="45" height="45" class="d-block" />
                            </div>
                        </template>
                        <b-dropdown-item class="dropitem" to="profile"><b-icon icon="person" font-scale="1" class="icon" /> Profile</b-dropdown-item>
                        <b-dropdown-item class="dropitem" @click.prevent="logOut"><b-icon icon="power" class="icon" /> Log Out</b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>

        </div>

        <div class="nav" style="margin-top:120px; margin-left:50px">
            <router-link to="/">
                <span class="pl-3">Dashboard</span> 
            </router-link> 
            <router-link to="/fields">
                <span class="pl-3">Fields</span> 
            </router-link>
        </div>

    </div>

</template>

<script>

    import { mapActions, mapGetters } from 'vuex';

    export default {
        name:'topbar',
        methods: {
            ...mapActions({
                logoutAction: "auth/logout"
            }),
            goBack() {
                window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
            }, 
            logOut() {
                this.logoutAction().then(() => {
                    this.$router.replace({
                        name: 'login'
                    })
                })
            },
            getIcon(icon) {
                return this.icons('./' + icon + ".png")
            },
        },
        data() {
            return {
                avatar: require("../assets/icons/avatar.svg"),
                logo: require("../assets/icons/logo.png"),
                dashboard_ic: require("../assets/icons/dashboard.png"),
                fields_ic: require("../assets/icons/fields.png"),
            }
        },
        computed: {
            ...mapGetters({
                user: "auth/user",
            })
        }
        
    };

</script>

<style scoped>

    .back {
        font-family: 'Circular Std';
        font-weight: 600;
        cursor: pointer;
    }

    .top-bar-container {
        position:fixed;
        top:0; 
        z-index:1; 
        padding-top: 30px;
        background-color:rgba(252, 250, 255, 0.90);
    }

    .top-bar {
        padding:5px 20px 3px 30px;
        box-shadow: 0 4px 24px 0 rgb(34 41 47 / 10%);
		border-bottom:solid 1px #ebeff7;
        border-radius: 0.428rem;
        transition: all .3s ease,background 0s;
    }


    .back_btn:hover {
        background-color: #ebeff7;
        cursor: pointer;
    }

    .nav img {
       width: 25px;
       height:25px;
       opacity: .6;
   }

</style>