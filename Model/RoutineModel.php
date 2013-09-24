<?php
App::uses('AppModel', 'Model');
class RoutineModel extends AppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        $this->actsAs[] = 'Routine.Routine';
        parent::__construct($id, $table, $ds);
    }

    /**
     * view
     */
    public function view($id, $conditions = array()) {
        return $this->routineView($id, $conditions);
    }

    /**
     * add
     */
    public function add($data) {
        return $this->routineAdd($data);
    }

    /**
     * edit
     */
    public function edit($id, $data, $conditions = array()) {
        return $this->routineEdit($id, $data, $conditions);
    }

    /**
     * drop
     */
    public function drop($id, $conditions = array()){
        return $this->routineDrop($id, $conditions);
    }
}
