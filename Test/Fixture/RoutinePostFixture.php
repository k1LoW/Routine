<?php
class RoutinePostFixture extends CakeTestFixture {
    public $name = 'RoutinePost';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
        'title' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'body' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    );

    public $records = array(
        array(
            'title' => 'Title',
            'body' => 'RoutineModel Test',
            'created' => '2012-08-23 17:44:58',
            'modified' => '2012-08-23 12:05:02'
        ),
        array(
            'title' => 'Title2',
            'body' => 'RoutineModel Test2',
            'created' => '2012-08-23 17:44:58',
            'modified' => '2012-08-23 12:05:02'
        ),
    );
}