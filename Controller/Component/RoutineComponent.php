<?php
class RoutineComponent extends Component {

    public $components = array('Session');
    public $redirect;

    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->Controller = $collection->getController();

        $defaults = array(
            'redirect' => array(
                'add' => array(array($this, 'redirect'), array(array('action' => 'index'))),
                'edit' => array(array($this, 'redirect'), array(array('action' => 'index'))),
                'delete' => array(array($this, 'redirect'), array(array('action' => 'index'))),
            )
        );

        $settings = Hash::merge($defaults, $settings);

        // configure.
        $this->_set($settings);

        parent::__construct($collection, $settings);

    }

    public function startup(Controller $Controller) {
        // not load
        return true;
    }

    /**
     * index
     *
     */
    public function index(){
        $this->Controller->set(
            Inflector::pluralize(Inflector::variable($this->Controller->modelClass)),
            $this->Controller->paginate()
        );
    }

    /**
     * search
     *
     */
    public function search(){
        $this->Controller->Prg->commonProcess();
        $conditions = $this->Controller->{$this->Controller->modelClass}->parseCriteria($this->Controller->passedArgs);
        $this->Controller->set(
            Inflector::pluralize(Inflector::variable($this->Controller->modelClass)),
            $this->Controller->Paginator->paginate($conditions)
        );
    }

    /**
     * view
     *
     */
    public function view($id = null){
        $this->Controller->set(
            Inflector::variable($this->Controller->modelClass),
            $this->Controller->{$this->Controller->modelClass}->view($id)
        );
    }

    /**
     * add
     *
     */
    public function add(){
        try {
            $result = $this->Controller->{$this->Controller->modelClass}->add($this->Controller->request->data);
            if ($result === true) {
                $this->Controller->Session->setFlash(
                    __('The %s has been created', __($this->Controller->modelClass)),
                    $this->Controller->setFlashElement['success'],
                    $this->Controller->setFlashParams['success']);
                $id = $this->Controller->{$this->Controller->modelClass}->getLastInsertID();
                $this->addRedirect($id);
            }
        } catch (ValidationException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->Controller->setFlashElement['error'],
                $this->Controller->setFlashParams['error']);
        } catch (OutOfBoundsException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->Controller->setFlashElement['error'],
                $this->Controller->setFlashParams['error']);
        }
        if (!empty($this->Controller->{$this->Controller->modelClass}->belongsTo)) {
            foreach ($this->Controller->{$this->Controller->modelClass}->belongsTo as $modelClass => $value) {
                $this->Controller->set(Inflector::pluralize(Inflector::variable($modelClass)), $this->Controller->{$this->Controller->modelClass}->{$modelClass}->find('list'));
            }
        }
    }

    /**
     * addRedirect
     *
     */
    protected function addRedirect($id){
        $func = $this->redirect['add'][0];
        $param = $this->redirect['add'][1];
        $param[] = $id;
        call_user_func_array($func, $param);
    }

    /**
     * edit
     *
     */
    public function edit($id = null){
        try {
            $result = $this->Controller->{$this->Controller->modelClass}->edit($id, $this->Controller->request->data);
            if ($result === true) {
                $this->Controller->Session->setFlash(
                    __('The %s has been modified', __(Inflector::humanize($this->Controller->modelClass))),
                    $this->Controller->setFlashElement['success'],
                    $this->Controller->setFlashParams['success']);
                $this->editRedirect($id);
            } else {
                $this->Controller->request->data = $result;
            }
        } catch (ValidationException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->Controller->setFlashElement['error'],
                $this->Controller->setFlashParams['error']);
        } catch (OutOfBoundsException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->Controller->setFlashElement['error'],
                $this->Controller->setFlashParams['error']);
        }
        if (!empty($this->Controller->{$this->Controller->modelClass}->belongsTo)) {
            foreach ($this->Controller->{$this->Controller->modelClass}->belongsTo as $modelClass => $value) {
                $this->Controller->set(Inflector::pluralize(Inflector::variable($modelClass)), $this->Controller->{$this->Controller->modelClass}->{$modelClass}->find('list'));
            }
        }
    }

    /**
     * editRedirect
     *
     */
    protected function editRedirect($id = null){
        $func = $this->redirect['edit'][0];
        $param = $this->redirect['edit'][1];
        $param[] = $id;
        call_user_func_array($func, $param);
    }

    /**
     * delete
     *
     */
    public function delete($id = null){
        if (!$this->Controller->request->is('post')) {
            throw new OutOfBoundsException(__('Invalid Access'));
        }
        try {
            if($this->Controller->{$this->Controller->modelClass}->drop($id)) {
                $this->Controller->Session->setFlash(
                    __('The %s has been deleted', __($this->Controller->modelClass)),
                    $this->Controller->setFlashElement['success'],
                    $this->Controller->setFlashParams['success']);
                $this->deleteRedirect($id);
            }
        } catch (ValidationException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->Controller->setFlashElement['error'],
                $this->Controller->setFlashParams['error']);
            $this->deleteRedirect($id);
        } catch (OutOfBoundsException $e) {
            $this->Controller->Session->setFlash($e->getMessage(),
                $this->setFlashElement['error'],
                $this->setFlashParams['error']);
            $this->deleteRedirect($id);
        }
    }

    /**
     * deleteRedirect
     *
     */
    protected function deleteRedirect($id = null){
        $func = $this->redirect['delete'][0];
        $param = $this->redirect['delete'][1];
        $param[] = $id;
        call_user_func_array($func, $param);
    }

    /**
     * login
     *
     */
    public function login(){
        if ($this->Controller->request->is('post')) {
            if ($this->Controller->Auth->login()) {
                $this->Controller->redirect($this->Controller->Auth->redirect(), null, true);
            } else {
                $this->Controller->Session->setFlash(
                    __('Username or password is incorrect'),
                    $this->Controller->setFlashElement['error'],
                    $this->Controller->setFlashParams['error'],
                    'auth');
            }
        }
    }

    /**
     * logout
     *
     */
    public function logout(){
        $this->Controller->redirect($this->Controller->Auth->logout());
    }

    /**
     * redirect
     *
     * @param $url
     */
    private function redirect($url){
        $this->Controller->redirect($url);
    }
}

