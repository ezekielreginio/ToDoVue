<?php 

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    protected $task;

    public function __construct(Task $task){
        $this->task = $task;
    }

    
    public function save($data)
    {
        $task = new $this->task;
        $task->task = $data['task'];
        $task->isCompleted = $data['isCompleted'];

        $task->save();

        return $task->fresh();
    }

    public function getAllTask(){
        return $this->task->get();
    }

    public function update($data){
        $task = $this->task->find($data['id']);

        $task->task = $data['task'];
        $task->isCompleted = $data['isCompleted'];

        $task->update();

        return $task;
    }

    public function delete($id){
        $task = $this->task->find($id);
        $task->delete();

        return $task;
    }
}

?>