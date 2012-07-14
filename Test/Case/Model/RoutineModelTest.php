<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('RoutineModel', 'Routine.Model');
App::uses('ValidationException', 'Routine.Error');

class RoutinePost extends RoutineModel {
    public $name = 'RoutinePost';
    public $validate = array('title' => array('rule' => 'notEmpty',
                                              'required' => true));
}

class RoutineModelTestCase extends CakeTestCase{

    public $fixtures = array('plugin.Routine.routine_post');

    function setUp() {
        $this->RoutinePost = new RoutinePost();
        $this->RoutinePostFixture = ClassRegistry::init('RoutinePostFixture');
    }

    function tearDown() {
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
        $data = array('RoutinePost' => array('body' => 'ValidationError'));
        $result = $this->RoutinePost->add($data);
    }

    /**
     * testAdd
     *
     */
    public function testAdd(){
        $data = array('RoutinePost' => array('title' => 'Test',
                                             'body' => 'ValidationError'));
        $result = $this->RoutinePost->add($data);
    }
}