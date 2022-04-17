import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    message: "Test",
    users: null,
    sessionData: {
      userID: null,
      email: null,
      name: null,
      isLoggedIn: false,
      
    },
    notificationReceivers:[],
    elements: {
      loginModal: null,
      notifModal: null
    },
    mode: "Login"
  },
  mutations: {
    setSessionData(state, payload) {
      state.sessionData = payload;
      window.localStorage.setItem("sessionData", JSON.stringify(state.sessionData))
    },
    setUsersList(state, payload){
      state.users = payload;
    },
    insertReceiver(state, payload){
      if(payload.isSelected)
        state.notificationReceivers.push(payload.userID);
      else
        state.notificationReceivers = state.notificationReceivers.filter(receiver=> (receiver !=payload.userID));
    },
    clearSessionData(state) {
      state.sessionData = {
        userID: null,
        email: null,
        name: null,
        isLoggedIn: false
      }
      window.localStorage.removeItem("sessionData")
    },
    checkSessionData(state) {
      state.sessionData = JSON.parse(window.localStorage.getItem("sessionData"))
    },
    changeMode(state, payload) {
      state.mode = payload
    },
    setLoginModal(state, payload){
      state.elements.loginModal = payload
    },
    setNotifModal(state, payload){
      state.elements.notifModal = payload
    }
  },
  actions: {
    async SubmitForm(context, credentials) {
      const res = await fetch(process.env.VUE_APP_API_URL + credentials.mode, {
        method: "POST",
        body: new URLSearchParams(credentials.data)
      })
      let status = res.ok
      const data = await res.json();
      return new Promise((resolve, reject) => {
          if(!status){
            return reject(data)
          }
          let payload = {
            userID: data.token.tokenable_id,
            name: data.user.name,
            email: data.user.email,
            isLoggedIn: true
          }
          context.commit('setSessionData', payload)
          return resolve()
      })
    },
    async GetUsers(context){
      await fetch(process.env.VUE_APP_API_URL + 'users', {
        method: "GET"
      })
      .then(res => res.json())
      .then(res => {
        context.commit('setUsersList', res)
      })
    },
    async logout(context) {
      await fetch(process.env.VUE_APP_API_URL + 'logout', {
        method: "POST",
        body: new URLSearchParams({
          onesignal_token: window.localStorage.getItem("OneSignalID"),
          device_uuid: window.localStorage.getItem("uuid")
        })
      });
      context.commit('clearSessionData')
    }
  },
  getters: {
    message(state) {
      return state.message.toUpperCase()
    }
  }
})

export default store;