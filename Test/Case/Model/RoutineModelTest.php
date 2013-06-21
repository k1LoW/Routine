<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('RoutineModel', 'Routine.Model');
App::uses('ValidationException', 'Routine.Error');

class RoutinePost extends RoutineModel {
    public $name = 'RoutinePost';
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty',
            'required' => true
        ));

    /**
     * __construct
     *
     * @param
     */
    public function __construct($id = false, $table = null, $ds = null) {
        $this->actsAs = false;
        parent::__construct($id, $table, $ds);
    }

    /**
     * beforeValidate
     *
     */
    public function beforeValidate($options = array()){
        return true;
    }

    /**
     * beforeSave
     *
     */
    public function beforeSave($options = array()){
        return true;
    }

}

class RoutineModelTestCase extends CakeTestCase{

    public $fixtures = array('plugin.Routine.routine_post');

    public function setUp() {
        $this->RoutinePost = ClassRegistry::init('RoutinePost');
        $this->RoutinePostFixture = ClassRegistry::init('RoutinePostFixture');
    }

    public function tearDown() {
        unset($this->RoutinePost);
        unset($this->RoutinePostFixture);
    }

    /**
     * testView
     *
     */
    public function testView(){
        $result = $this->RoutinePost->view(1);
        unset($result['RoutinePost']['id']);
        $expect = $this->RoutinePostFixture->records[0];
        $this->assertIdentical($result['RoutinePost'], $expect);
    }

    /**
     * testViewNotFoundException
     *
     * @expectedException NotFoundException
     */
    public function testViewNotFoundException(){
        $result = $this->RoutinePost->view(999);
    }

    /**
     * testAddNull
     *
     */
    public function testAddNull(){
        $result = $this->RoutinePost->add(null);
        $this->assertIdentical($result, null);
    }

    /**
     * testAddValidationException
     *
     * @expectedException ValidationException
     */
    public function testAddValidationException(){
        $data = array('RoutinePost' => array(
                'body' => 'ValidationError'
            ));
        $result = $this->RoutinePost->add($data);
    }

    /**
     * testAdd
     *
     */
    public function testAdd(){
        $data = array('RoutinePost' => array(
                'title' => 'Test',
                'body' => 'OK'
            ));
        $result = $this->RoutinePost->add($data);
        $id = $this->RoutinePost->getLastInsertID();
        $result = $this->RoutinePost->view($id);
        $this->assertIdentical($result['RoutinePost']['body'], 'OK');
    }

    /**
     * testEdit
     *
     */
    public function testEdit(){
        $data = array('RoutinePost' => array(
                'title' => 'Test',
                'body' => 'OK'
            ));
        $result = $this->RoutinePost->add($data);
        $id = $this->RoutinePost->getLastInsertID();
        $data = array('RoutinePost' => array(
                'id' => $id,
                'title' => 'Test',
                'body' => 'Modified'
            ));
        $result = $this->RoutinePost->edit($id, $data);
        $result = $this->RoutinePost->view($id);
        $this->assertIdentical($result['RoutinePost']['body'], 'Modified');
    }

    /**
     * testDrop
     *
     */
    public function testDrop(){
        $data = array('RoutinePost' => array(
                'title' => 'Test',
                'body' => 'OK'
            ));
        $result = $this->RoutinePost->add($data);
        $id = $this->RoutinePost->getLastInsertID();
        $result = $this->RoutinePost->drop($id);
        $this->assertTrue($result);
    }

    /**
     * testViewWithCondition
     *
     * @expectedException NotFoundException
     */
    public function testViewWithCondition(){
        $result = $this->RoutinePost->view(1, array('RoutinePost.title' => 'Title2'));
    }

    /**
     * testEditWithCondition
     *
     * @expectedException NotFoundException
     */
    public function testEditWithCondition(){
        $data = array('RoutinePost' => array(
                'title' => 'Test',
                'body' => 'OK'
            ));
        $result = $this->RoutinePost->add($data);
        $id = $this->RoutinePost->getLastInsertID();
        $data = array('RoutinePost' => array(
                'id' => $id,
                'title' => 'Test',
                'body' => 'Modified'
            ));
        $conditions = array('RoutinePost.body' => 'NG');
        $result = $this->RoutinePost->edit($id, $data, $conditions);
    }

    /**
     * testDropWithCondition
     *
     * @expectedException NotFoundException
     */
    public function testDropWithCondition(){
        $data = array('RoutinePost' => array(
                'title' => 'Test',
                'body' => 'OK'
            ));
        $result = $this->RoutinePost->add($data);
        $id = $this->RoutinePost->getLastInsertID();
        $conditions = array('RoutinePost.body' => 'NG');
        $result = $this->RoutinePost->drop($id, $conditions);
    }
}