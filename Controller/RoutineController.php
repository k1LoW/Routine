<?php
App::uses('AppController', 'Controller');
class RoutineController extends AppController {

    protected $setFlashElement = array('success' => null,
        'error' => null,
    );
    protected $setFlashParams = array('success' => array(),
        'error' => array(),
    );

    /**
     * index
     *
     */
    public function index(){
        $this->{$this->modelClass}->recursive = 0;
        $this->Routine = $this->Components->load('Routine.Routine');
        $this->Routine->index();
    }

    /**
     * view
     *
     */
    public function view($id = null) {
        $this->Routine = $this->Components->load('Routine.Routine');
        $this->Routine->view($id);
    }

    /**
     * add
     *
     */
    public function add() {
        $this->Routine = $this->Components->load('Routine.Routine');
        $this->Routine->add();
    }

    /**
     * edit
     *
     */
    public function edit($id = null) {
        $this->Routine = $this->Components->load('Routine.Routine');
        $this->Routine->edit($id);
    }

    /**
     * delete
     *
     */
    public function delete($id = null){
        $this->Routine = $this->Components->load('Routine.Routine');
        $this->Routine->delete($id);
    }
}
