<template>
    <div class="modal fade" id="modal-login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{mode}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form>
                <div class="mb-3" v-if="mode == 'Register'">
                    <label for="exampleInputEmail1" class="form-label">Name</label>
                    <input type="text" class="form-control" v-model="name">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" v-model="email">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label" >Password</label>
                    <input type="password" class="form-control" v-model="password">
                </div>
                <div class="mb-3" v-if="mode == 'Register'">
                    <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" v-model="password_confirmation">
                </div>
                <p v-if="mode == 'Login'">Don't have an account? <span class="text-primary pointer" @click="openRegister()">Register Here</span></p>
                <p v-if="mode == 'Register'">Already have an account? <span class="text-primary pointer" @click="openLogin()">Login Here</span></p>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="SubmitForm()">{{mode}}</button>
        </div>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        name: 'ModalLogin',
        data: ()=>{
            return{
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                message: ""
            }
        },
        methods:{
            SubmitForm(){
                let formdata = {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password,
                    onesignal_token: window.localStorage.getItem("OneSignalID"),
                    device_uuid: window.localStorage.getItem("uuid")
                }
                if(this.mode == "Register"){
                    formdata["name"] = this.name
                    formdata["password_confirmation"] = this.password_confirmation
                }
                this.$store.dispatch('SubmitForm',{
                    mode: this.mode.toLowerCase(),
                    data: formdata
                }).then(()=>{
                    // Login/Registration Success

                    //Login Modal Instantiation
                    this.loginModal.hide()

                    //Get Users List for Notifications Receiver
                    this.$store.dispatch("GetUsers")
                }, errors=>{
                    // Login/Registration Failed
                    if(this.mode == "Register"){
                        alert("Registration Failed. \nErrors: \n"+errors.errors.join('\n'))
                    }
                    else{
                        alert("Invalid Email/Password. Please Try Again.")
                    }
                })
            },
            openRegister(){
                this.$store.commit("changeMode", "Register")
            },
            openLogin(){
                this.$store.commit("changeMode", "Login")
            }
        },
        computed:{
            mode(){
                return this.$store.state.mode;
            },
            loginModal(){
                return this.$store.state.elements.loginModal
            }
        }
    }
</script>
