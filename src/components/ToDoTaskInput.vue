<template>
    <div>
        <input type="text" name="input-task" id="input-task" v-model="task" v-on:keyup.enter="createTask()">
    </div>
</template>

<script>
export default{
    name: "ToDoTaskInput",
    data: function(){
        return {
            task: ""
        }
    },
    methods: {
        createTask(){
            this.$emit('createTask', this.task)
        }
    }
}
</script>
