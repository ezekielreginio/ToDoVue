import Vue from 'vue'
import App from './App.vue'
import OneSignalVue from 'onesignal-vue'
import store from '@/store/store'
import UAParser from 'ua-parser-js'
import { DeviceUUID } from './js/deviceUUID'

Vue.config.productionTip = false
Vue.use(OneSignalVue);
new Vue({
  render: h => h(App),
  store: store,
  beforeMount() {
    this.$OneSignal.init({ appId: process.env.VUE_APP_ONESIGNAL_APP_ID });
    this.$OneSignal.getUserId()
    .then(userID=>{
      window.localStorage.setItem("OneSignalID", userID)
      
    })
    .catch(err=>{alert("Failed to Secure Local OneSignal ID : "+err)})

    var uuid = new DeviceUUID().get()
    var parser = new UAParser();

    window.localStorage.setItem("uuid", parser.getResult().browser.name +" "+ uuid)

    console.log(window.localStorage.getItem("uuid"))
    console.log(parser.getResult())
  }
}).$mount('#app')
