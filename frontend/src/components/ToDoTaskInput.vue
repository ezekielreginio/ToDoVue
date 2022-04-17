<template>
    <div>
        <input type="text" name="input-task" id="input-task" v-model="taskname" v-on:keyup.enter="save($event)">
    </div>
</template>

<script>
export default {
  name: 'ToDoTaskInput',
  props: ['task'],
  methods: {
      save(event){
          this.$emit('saveTask', this.taskname)
          this.$emit('updateCounter')
          event.target.value=""
      }
  },
  data(){
      return{
          taskname: ""
      }
  }
}
</script>