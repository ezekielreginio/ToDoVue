<?php 

namespace App\Services;

use App\Repositories\TaskRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TaskService
{
    protected $taskRepository;
    
    public function __construct(TaskRepository $taskRepository){
        $this->taskRepository = $taskRepository;
    }

    public function saveTaskData($data)
    {
        $validator = Validator::make($data, [
            'task' => 'required',
            'isCompleted' => 'required'
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->taskRepository->save($data);

        return $result;
    }

    public function getAll(){
        return $this->taskRepository->getAllTask();
    }

    public function updateTask($data){
        $validator = Validator::make($data, [
            'id' => 'required',
            'task' => 'required',
            'isCompleted' => 'required'
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try{
            $task = $this->taskRepository->update($data);
        }
        catch (Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update task data');
        }

        DB::commit();

        return $task;
    }

    public function deleteByID($id)
    {
        DB::beginTransaction();

        try{
            $task = $this->taskRepository->delete($id);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete task data from service');
        }

        DB::commit();

        return $task;
    }
}

?>