<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\OneSignalAccessTokenService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Ladumor\OneSignal\OneSignal;
use Exception;

class TaskController extends Controller
{

    protected $taskService;
    protected $oneSignalAccessTokenService;
    protected $output;

    public function __construct(TaskService $taskService, OneSignalAccessTokenService $oneSignalAccessTokenService){
        $this->taskService = $taskService;
        $this->oneSignalAccessTokenService = $oneSignalAccessTokenService;
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ['status' => 200];
        try{
            $result['data'] = $this->taskService->getAll();

        }
        catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'task',
            'isCompleted',
            'receivers'
        ]);

        $result = ['status' => 200];
        try{
            $result['data'] = $this->taskService->saveTaskData($data);

            $data['receivers'] = explode (",", $data['receivers']); 
            $data["message"]="Task ".$data["task"]." has beed added!";
            $this->oneSignalAccessTokenService->pushNotification($data);
        } catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
       
        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        //Update Request Sample: http://127.0.0.1:8000/api/task/2?task=Lunch&isCompleted=0
        $data = $request->only([
            'id',
            'task',
            'isCompleted',
            'receivers'
        ]);

        $result = ['status' => 200];

        try{
            $result['data'] = $this->taskService->updateTask($data);
        }catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        $data['receivers'] = explode (",", $data['receivers']); 
        $data["message"]="Task ".$data["task"]." has beed updated!";
        $this->oneSignalAccessTokenService->pushNotification($data);
        return response()->json($result, $result['status']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try{
            $result['data'] = $this->taskService->deleteByID($id);
        }catch(Exception $e){
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        
        return response()->json($result, $result['status']);
    }
}
