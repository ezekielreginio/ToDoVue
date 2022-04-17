<template>
    <div>
        <div class="modal fade" id="modal-notif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Set Notification Receivers</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="task-list py-2" v-for="(user, index) in users" :key="index">
                            <div class="d-flex" style="width: 100%">
                                <div style="width: 5%">
                                    <input type="checkbox" @change="receiverSelected($event.target.checked, user.id)"> 
                                </div>
                                <div style="width: 95%; word-wrap: break-word;">
                                    <h6>{{ user.name }} <span v-if="user.id == userID">(You)</span></h6>
                                </div>
                                
                            </div> 
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ModalNotificationReceivers',
        methods:{
            receiverSelected(isSelected, userID){
                this.$store.commit('insertReceiver', {
                    isSelected: isSelected,
                    userID: userID
                })
            }
        },
        computed: {
            users() {
                return this.$store.state.users;
            },
            userID(){
                return this.$store.state.sessionData.userID;
            }
        }
    }
</script>