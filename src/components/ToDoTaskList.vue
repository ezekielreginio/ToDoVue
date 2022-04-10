<template>
    <div id="div-task">
        <div>
            <div class="task-list" v-for="(task, index) in tasks" :key="index" @mouseover="onTaskHovered(true, index)" @mouseleave="onTaskHovered(false, index)">
                <div class="d-flex" style="width: 100%">
                    <div style="width: 10%">
                        <input type="checkbox" @change="onTaskUpdate($event.target.checked, task.task, index)">
                    </div>
                    <div style="width: 60%; word-wrap: break-word; text-align: left">
                        <label :class="{'task-done': task.isCompleted}" v-if="task.isEditing == false">{{task.task}}</label>
                        <input type="text" class="edit-task" :value="task.task" v-if="task.isEditing == true" @keyup.enter="onTaskUpdate(task.isChecked, $event.target.value, index)">
                    </div>
                    <div v-if="task.isHovered" class="ml-auto">
                        <i class="fa-solid fa-pen-to-square pointer mr-1" @click="onTaskEdit(!task.isEditing, index)" v-if="task.isCompleted == false"></i>
                        <i class="fa-solid fa-delete-left pointer" @click="onTaskDelete(index)"></i>
                    </div>
                </div>
                <hr class="hr-task"> 
            </div>
        </div>
    </div>
</template>

<script>
export default{
    name: "ToDoTaskList",
    props: ['tasks'],
    data: function(){
        return{
            isEditing: true
        }
    },
    methods:{
        onTaskUpdate(isChecked, task, index){
            this.$emit('updateTask', isChecked, task, index)
        },
        onTaskHovered(isHovered, index){
            this.$emit('hoverTask', isHovered, index)
        },
        onTaskEdit(isEditing, index){
            this.$emit('editTask', isEditing, index)
        },
        onTaskDelete(index){
            this.$emit('deleteTask', index)
        }
    }
}
</script>
