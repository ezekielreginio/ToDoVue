<template>
  <div class="container">
    <p id="todo-header">What do you want to do today?</p>
    <ToDoTaskInput @createTask="onTaskCreate"></ToDoTaskInput>
    <ToDoTaskList :tasks="tasks" @updateTask="onTaskUpdate" @hoverTask="onTaskHover" @editTask="onTaskEdit" @deleteTask="onTaskDelete"></ToDoTaskList>
  </div>
</template>

<script>
import ToDoTaskInput from "./components/ToDoTaskInput"
import ToDoTaskList from "./components/ToDoTaskList"

export default {
  name: 'App',
  data: function(){
    return {
      task: "",
      tasks: [
        {
          task: "breakfast",
          isCompleted: false,
          isHovered: false,
          isEditing: false
        },
        {
          task: "lunch",
          isCompleted: false,
          isHovered: false,
          isEditing: false
        },
        {
          task: "dinner",
          isCompleted: false,
          isHovered: false,
          isEditing: false
        }
      ]
    }
  },
  methods:{
    onTaskCreate(task){
      this.tasks.push({
          task: task,
          isCompleted: false,
          isHovered: false,
          isEditing: false
      })
      console.log(this.tasks)
    },
    onTaskUpdate(isChecked, task, index){
      this.tasks[index].isCompleted = isChecked;
      this.tasks[index].task = task;
      this.tasks[index].isEditing = false;
      console.log(this.tasks)
    },
    onTaskHover(isHovered, index){
      this.tasks[index].isHovered = isHovered;
    },
    onTaskEdit(isEditing, index){
      this.tasks[index].isEditing = isEditing;
    },
    onTaskDelete(index){
      this.tasks.splice(index, 1);
    }
  },
  components:{
    ToDoTaskInput,
    ToDoTaskList
  }
}
</script>

<style>
</style>
