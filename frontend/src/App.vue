<template>
  <div>
    <ToDoApp/>
  </div>
</template>

<script>
import ToDoApp from './components/ToDoApp'


export default {
  name: 'App',
  components: {
    ToDoApp
  },
  beforeCreate() {
    this.$OneSignal.showSlidedownPrompt()
  }
}
</script>

<style>
</style>
