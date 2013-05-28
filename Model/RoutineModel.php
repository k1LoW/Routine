<?php
App::uses('AppModel', 'Model');
class RoutineModel extends AppModel {

    /**
     * view
     */
    public function view($id, $conditions = array()) {
        $conditions["{$this->alias}.{$this->primaryKey}"] = $id;
        $result = $this->find('first', array(
                'conditions' => $conditions
            ));
        if (empty($result)) {
            throw new NotFoundException(__('Invalid Access'));
        }
        return $result;
    }

    /**
     * add
     */
    public function add($data) {
        if (empty($data)) {
            return;
        }
        $this->create();
        $this->set($data);
        $result = $this->validates();
        if ($result === false) {
            throw new ValidationException();
        }
        $result = $this->save($data);
        if ($result !== false) {
            $this->data = array_merge($data, $result);
            return true;
        } else {
            throw new OutOfBoundsException(__('Could not save, please check your inputs.', true));
        }
        return;
    }

    /**
     * edit
     */
    public function edit($id, $data, $conditions = array()) {
        $conditions["{$this->alias}.{$this->primaryKey}"] = $id;
        $current = $this->find('first', array(
                'conditions' => $conditions
            ));

        if (empty($current)) {
            throw new NotFoundException(__('Invalid Access'));
        }

        if (!empty($data)) {
            $this->set($data);
            $result = $this->save(null, true);
            if ($result) {
                $this->data = $result;
                return true;
            } else {
                throw new ValidationException();
            }
        } else {
            return $current;
        }
    }

    /**
     * drop
     */
    public function drop($id, $conditions = array()){
        $conditions["{$this->alias}.{$this->primaryKey}"] = $id;
        $current = $this->find('first', array(
                'conditions' => $conditions
            ));

        if (empty($current)) {
            throw new NotFoundException(__('Invalid Access'));
        }
        // for SoftDeletable
        $this->set($current);
        $this->delete($id);
        $count = $this->find('count', array(
                'conditions' => array(
                    "{$this->alias}.{$this->primaryKey}" => $id,
                )));
        return ($count === 0);
    }
}