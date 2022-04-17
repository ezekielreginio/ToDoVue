<template>
  <div>
    <ToDoNavBar></ToDoNavBar>
    <div class="container">  
        <p id="todo-header">What do you want to do today?</p>
        <ToDoTaskInput @saveTask="onTaskSaved"></ToDoTaskInput>
        <ToDoTaskList :tasks="tasks" @checkTask="onTaskChecked" @hoverTask="onTaskHover" @deleteTask="onTaskDelete" @triggerEdit="onTriggerUpdate" @updateTask="onTaskUpdate"></ToDoTaskList>
        <div class="d-flex span-footer">
            <div>
                <span class="bold">{{taskRemainingCount}}</span>
                <span> tasks left</span>
            </div>
            <div class="pointer" style="margin-left: auto" @click="clearCompletedTasks();">
                <span>Clear <span class="bold">{{taskCompletedCount}}</span> completed tasks</span>
            </div>
        </div>
    </div>
    
  </div>
</template>

<script>
import ToDoTaskInput from "./ToDoTaskInput"
import ToDoTaskList from "./ToDoTaskList"
import ToDoNavBar from "./ToDoNavBar"

export default {
  name: 'ToDoApp',
  data(){
      return {
          tasks: []
      }
  },
  components: {
    ToDoTaskInput,
    ToDoTaskList,
    ToDoNavBar
  }, 
  methods: {
    async onTaskSaved(task){
      //Push to API
      await fetch(process.env.VUE_APP_API_URL+"task", {
        method: "POST",
        body: new URLSearchParams({
        'task': task,
        'isCompleted': 0,
        'receivers': this.$store.state.notificationReceivers
        })
      })
      .then(res=>res.json())
      .then(res=>{
        this.tasks.push({
          id: res.data.id,
          task: task,
          isCompleted: false,
          isHovered: false,
          isEditing: false
        })
      })
    },
    async onTaskChecked(isChecked, index, task, id){
      try{
        this.tasks[index].task = task
        this.tasks[index].isCompleted = isChecked

        //Update task status to API
        await fetch(process.env.VUE_APP_API_URL+"task/update/", {
          method: "POST",
          body: new URLSearchParams({
            'id' : id,
            'task': task,
            'isCompleted': Number(isChecked),
            'receivers': this.$store.state.notificationReceivers
          })
        })
        .then(res=>res.json())
        .then(res=>{
          if(res.status == 500)
            throw new Error("Internal Server Error")
        })

      }
      catch(e){
        alert("Invalid Task Input. Please Try Again")
      }
      this.tasks[index].isEditing = false
    },
    onTaskHover(hover, index){
      this.tasks[index].isHovered = hover
    },
    onTaskDelete(index, id){
      this.tasks[index].isCompleted = false
      this.tasks.splice(index, 1)
      fetch(process.env.VUE_APP_API_URL+"task/"+id, {
          method: "DELETE"
        })

    },
    onTaskUpdate(value, index){
      this.tasks[index].task = value
      this.tasks[index].isEditing = false
    },
    onTriggerUpdate(index, bool){
      this.tasks[index].isEditing = bool
    },
    async requestTaskList(){
      try{
          let tasks = await fetch(process.env.VUE_APP_API_URL+"task", {
            method: "GET"
          })
          .then(res => res.json())
          .then(res=>{
            let tasksRes = JSON.parse(JSON.stringify(res.data))
            let taskArr = [];
            for(let i = 0; i < tasksRes.length; i++){
              tasksRes[i].isCompleted = !!parseInt(tasksRes[i].isCompleted);
              tasksRes[i].isHovered = false;
              tasksRes[i].isEditing = false;
              taskArr.push(tasksRes[i])
            }
            return taskArr;
          })
          return tasks;
        }
        catch(e){
          alert("Could not connect to API. Please Check if the API is served")
        }
    },
    clearCompletedTasks(){
      for(let i=0;i<this.tasks.length;i++){
        if(this.tasks[i].isCompleted){
          fetch(process.env.VUE_APP_API_URL+"task/"+this.tasks[i].id, {
            method: "DELETE"
          })
        }
      }

      this.tasks = this.tasks.filter(task=> (task.isCompleted ==false));
    }
  },
  async beforeMount(){
    this.tasks = await this.requestTaskList();
  },
  computed:{
    
    taskCount:{
      get: function(){
        return this.tasks.length
      }
    },
    taskRemainingCount:{
      get: function(){
        try{
          let completedTaskCount = 0
          this.tasks.forEach(task => {
            if(task.isCompleted){
              completedTaskCount = completedTaskCount+1;
            }
          });
          return this.tasks.length - completedTaskCount
        }
        catch{
          return 0;
        }
      }
    },
    taskCompletedCount: {
      get: function() {
        return this.taskCount- this.taskRemainingCount
      }
    }
  }
}


</script>

