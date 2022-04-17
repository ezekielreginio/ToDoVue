<template>
    <div id="div-tasks">
        <div>
            <div class="task-list" v-for="(task, index) in tasks" :key="index" @mouseover="hoverTask(true, index)" @mouseleave="hoverTask(false, index)" >
                <div class="d-flex" style="width: 100%">
                    <div style="width: 10%">
                        <input type="checkbox" :v-model="task.isCompleted" @change="checkTask($event.target.checked, index, task.task, task.id)" :checked="task.isCompleted"> 
                    </div>
                    <div style="width: 60%; word-wrap: break-word;"> 
                        
                        <input type="text" style="padding: 10px; font-size: 20px;" :value="task.task" v-on:keyup.enter="checkTask(task.isCompleted, index, $event.target.value, task.id)" v-if="task.isEditing == true"> 
                        <label :class="{'task-done': task.isCompleted}" v-if="task.isEditing == false">{{ task.task }}</label><br>
                    </div>
                    <div style="margin-left: auto" v-if="task.isHovered">
                        <i class="fa-solid fa-pen-to-square pointer mr-1" @click="triggerEdit(index, !tasks.isEditing)" v-if="task.isCompleted == false"></i>
                        <i class="fa-solid fa-delete-left pointer" @click="deleteTask(index, task.id)"></i>
                    </div>
                </div>
            
                <hr class="hr-task"> 
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: 'ToDoTaskList',
  props: ['tasks'],
  methods: {
    checkTask(isChecked, index, task, id){
        this.$emit('checkTask', isChecked, index, task, id)
        this.$emit('updateCounter')
    },
    hoverTask(hover,index){
        this.$emit('hoverTask', hover, index)
    },
    deleteTask(index, id){
        this.$emit('deleteTask', index, id)
        this.$emit('updateCounter')
    },
    updateTask(event, index){
        this.$emit('updateTask', event.target.value, index)
        this.$emit('updateCounter')
    },
    triggerEdit(index, bool){
        this.$emit('triggerEdit', index, bool)
    }
  }
}
</script>
