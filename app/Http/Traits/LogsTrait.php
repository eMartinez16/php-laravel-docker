<?php
namespace App\Http\Traits;
use App\Models\Log as Logs;
use Illuminate\Support\Facades\Log;

/**this trait is used to store in the table log, the registry of all the events in the application */
trait LogsTrait {
    
    /**
     * function to store one registry in the log table (created)
     * @param string $entity
     * @param int    $entityId
     * @param string $message
     */
    public function creates($entity,$entityId, $message){
        $log = new Logs();
        $log->entity= $entity;
        $log->entity_id = $entityId;
        $log->event =  'Agregar';
        $log->user_id =  auth()->user()->id;
        $log->user =  auth()->user()->name;
        $log->email =  auth()->user()->email;
        $log->rol =  auth()->user()->role;
        $log->message = $message;
        $log->date = now();
        Log::info('Nuevo log: '.$log);
    
        if(!$log->save()) return Log::error('Error al crear log');
    }

    /**
     * function to store one registry in the log table (updated)
     * @param string $entity
     * @param int    $entityId
     * @param string $message
     */
    public function updates($entity,$entityId, $message){
        $log = new Logs();
        $log->entity= $entity;
        $log->entity_id = $entityId;
        $log->message =  $message;
        $log->user_id =  auth()->user()->id;
        $log->user =  auth()->user()->name;
        $log->email =  auth()->user()->email;
        $log->rol =  auth()->user()->role;
        $log->event = 'Modificar';
        $log->date = now();
        Log::info('Nuevo log: '.$log);
    
        if(!$log->save()) return Log::error('Error al crear log');
    }

    /**
     * function to store one registry in the log table (deleted)
     * @param string $entity
     * @param int    $entityId
     * @param string $message
     */
    public function deletes($entity,$entityId, $message){
        $log = new Logs();
        $log->entity= $entity;
        $log->entity_id = $entityId;
        $log->message =  $message;
        $log->user_id =  auth()->user()->id;
        $log->user =  auth()->user()->name;
        $log->email =  auth()->user()->email;
        $log->rol =  auth()->user()->role;
        $log->event = 'Eliminar';
        $log->date = now();
        Log::info('Nuevo log: '.$log);
    
        if(!$log->save()) return Log::error('Error al crear log');
    }

    /**
     * function to store one mix registry in the log table
     * @param string $entity
     * @param int    $entityId
     * @param string $message
     * @param string $event
     */
    public function general($entity, $entityId, $message, $event){
        $log = new Logs();
        $log->entity= $entity;
        $log->entity_id = $entityId;
        $log->message =  $message;
        $log->user_id =  auth()->user()->id;
        $log->user =  auth()->user()->name;
        $log->email =  auth()->user()->email;
        $log->rol =  auth()->user()->role;
        $log->event = $event;
        $log->date = now();
        Log::info('Nuevo log: '.$log);
    
        if(!$log->save()) return Log::error('Error al crear log');
    }
  

}