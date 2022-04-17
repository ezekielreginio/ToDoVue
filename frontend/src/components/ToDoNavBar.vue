<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">To Do App</a>
                <div v-if="isLoggedIn == false">
                    <button class="btn btn-outline-light me-1" type="button" @click="openLogin()">Login</button>
                    
                </div>
                <div v-if="isLoggedIn == true">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-white text-decoration-none" href="#" role="button" id="dropdown-options" data-bs-toggle="dropdown" aria-expanded="false" @click="toggleDropdown">
                            Welcome {{sessionData.name}}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#" @click="openNotif">Notification Settings</a></li>
                            <li><a class="dropdown-item" href="#" @click="logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <ModalLogin></ModalLogin>
            <ModalNotificationReceivers></ModalNotificationReceivers>
        </nav>
        
    </div>
</template>

<script>
    import ModalLogin from './modals/ModalLogin'
    import ModalNotificationReceivers from './modals/ModalNotificationsReceivers'
    import {Dropdown, Modal} from 'bootstrap'

    export default {
        name: 'ToDoLogin',
        components:{
            ModalLogin,
            ModalNotificationReceivers
        },
        methods:{
            openLogin(){
                this.$store.commit("changeMode", "Login")
                this.loginModal.toggle()
            },
            openNotif(){
                this.notifModal.toggle()
            },
            toggleDropdown(){
                let dropdown =  new Dropdown(document.getElementById("dropdown-options"))
                dropdown.toggle();
            },
            logout(){
                this.$store.dispatch('logout')
            }
        },
        computed:{
            isLoggedIn(){
                return this.$store.state.sessionData.isLoggedIn
            },
            sessionData(){
                return this.$store.state.sessionData
            },
            loginModal(){
                return this.$store.state.elements.loginModal
            },
            notifModal(){
                return this.$store.state.elements.notifModal
            }
        },
        mounted(){
            this.$store.commit('setLoginModal', new Modal(document.getElementById("modal-login")))
            this.$store.commit('setNotifModal', new Modal(document.getElementById("modal-notif")))
        },
        beforeCreate(){
            if(window.localStorage.getItem("sessionData") != null){
                this.$store.commit("setSessionData", JSON.parse(window.localStorage.getItem("sessionData")))
                this.$store.dispatch("GetUsers")
            }
        }
    }
</script>
