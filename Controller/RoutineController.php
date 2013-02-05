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
        $this->_index();
    }

    /**
     * _index
     *
     */
    protected function _index(){
        $this->set(Inflector::pluralize(Inflector::variable($this->modelClass)), $this->paginate());
    }

    /**
     * _search
     *
     */
    protected function _search(){
        $this->Prg->commonProcess();
        $conditions = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
        $this->set(Inflector::pluralize(Inflector::variable($this->modelClass)), $this->Paginator->paginate($conditions));
    }

    /**
     * view
     *
     */
    public function view($id = null) {
        $this->_view($id);
    }

    /**
     * _view
     *
     */
    protected function _view($id = null){
        $this->set(Inflector::variable($this->modelClass), $this->{$this->modelClass}->view($id));
    }

    /**
     * add
     *
     */
    public function add() {
        $this->_add();
    }

    /**
     * _add
     *
     */
    protected function _add(){
        try {
            $result = $this->{$this->modelClass}->add($this->request->data);
            if ($result === true) {
                $this->Session->setFlash(
                    __('The %s has been created', __($this->modelClass)),
                    $this->setFlashElement['success'],
                    $this->setFlashParams['success']);
                $this->_addRedirect();
            }
        } catch (ValidationException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
        } catch (OutOfBoundsException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
        }
        if (!empty($this->{$this->modelClass}->belongsTo)) {
            foreach ($this->{$this->modelClass}->belongsTo as $modelClass => $value) {
                $this->set(Inflector::pluralize(Inflector::variable($modelClass)), $this->{$this->modelClass}->{$modelClass}->find('list'));
            }
        }
    }

    /**
     * _addRedirect
     *
     */
    protected function _addRedirect(){
        $this->redirect(array('action' => 'index'));
    }

    /**
     * edit
     *
     */
    public function edit($id = null) {
        $this->_edit($id);
    }

    /**
     * _edit
     *
     */
    protected function _edit($id = null){
        try {
            $result = $this->{$this->modelClass}->edit($id, $this->request->data);
            if ($result === true) {
                $this->Session->setFlash(
                    __('The %s has been modified', __(Inflector::humanize($this->modelClass))),
                    $this->setFlashElement['success'],
                    $this->setFlashParams['success']);
                $this->_editRedirect($id);
            } else {
                $this->request->data = $result;
            }
        } catch (ValidationException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
        } catch (OutOfBoundsException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
        }
        if (!empty($this->{$this->modelClass}->belongsTo)) {
            foreach ($this->{$this->modelClass}->belongsTo as $modelClass => $value) {
                $this->set(Inflector::pluralize(Inflector::variable($modelClass)), $this->{$this->modelClass}->{$modelClass}->find('list'));
            }
        }
    }

    /**
     * _editRedirect
     *
     */
    protected function _editRedirect($id = null){
        $this->_addRedirect();
    }

    /**
     * delete
     *
     */
    public function delete($id = null){
        $this->_delete($id);
    }

    /**
     * _delete
     *
     */
    protected function _delete($id = null){
        if (!$this->request->is('post')) {
            throw new OutOfBoundsException(__('Invalid Access'));
        }
        try {
            if($this->{$this->modelClass}->drop($id)) {
                $this->Session->setFlash(
                    __('The %s has been deleted', __($this->modelClass)),
                    $this->setFlashElement['success'],
                    $this->setFlashParams['success']);
                $this->_deleteRedirect();
            }
        } catch (ValidationException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
            $this->_deleteRedirect();
        } catch (OutOfBoundsException $e) {
            $this->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
            $this->_deleteRedirect();
        }
    }

    /**
     * _deleteRedirect
     *
     */
    protected function _deleteRedirect(){
        $this->_addRedirect();
    }

    /**
     * _login
     *
     */
    protected function _login(){
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect(), null, true);
            } else {
                $this->Session->setFlash(
                    __('Username or password is incorrect'),
                    $this->setFlashElement['error'],
                    $this->setFlashParams['error'],
                    'auth');
            }
        }
    }

    /**
     * _logout
     *
     */
    protected function _logout(){
        $this->redirect($this->Auth->logout());
    }
}
