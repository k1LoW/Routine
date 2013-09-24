<?php
class RoutineBehavior extends ModelBehavior {

    /**
     * routineView
     *
     */
    public function routineView(Model $model, $id, $conditions = array()){
        $conditions["{$model->alias}.{$model->primaryKey}"] = $id;
        $result = $model->find('first', array(
                'conditions' => $conditions
            ));
        if (empty($result)) {
            throw new NotFoundException(__('Invalid Access'));
        }
        return $result;
    }

    /**
     * routineAdd
     *
     */
    public function routineAdd(Model $model, $data){
        if (empty($data)) {
            return;
        }
        $model->create();
        $model->set($data);
        $result = $model->validates();
        if ($result === false) {
            throw new ValidationException();
        }
        $result = $model->save($data);
        if ($result !== false) {
            $model->data = array_merge($data, $result);
            return true;
        } else {
            throw new OutOfBoundsException(__('Could not save, please check your inputs.', true));
        }
        return;
    }

    /**
     * routineEdit
     *
     */
    public function routineEdit(Model $model, $id, $data, $conditions = array()){
        $conditions["{$model->alias}.{$model->primaryKey}"] = $id;
        $current = $model->find('first', array(
                'conditions' => $conditions
            ));

        if (empty($current)) {
            throw new NotFoundException(__('Invalid Access'));
        }

        if (!empty($data)) {
            $model->set($data);
            $result = $model->save(null, true);
            if ($result) {
                $model->data = $result;
                return true;
            } else {
                throw new ValidationException();
            }
        } else {
            return $current;
        }
    }

    /**
     * routineDrop
     *
     */
    public function routineDrop(Model $model, $id, $conditions = array()){
        $conditions["{$model->alias}.{$model->primaryKey}"] = $id;
        $current = $model->find('first', array(
                'conditions' => $conditions
            ));

        if (empty($current)) {
            throw new NotFoundException(__('Invalid Access'));
        }
        // for SoftDeletable
        $model->set($current);
        $model->delete($id);
        $count = $model->find('count', array(
                'conditions' => array(
                    "{$model->alias}.{$model->primaryKey}" => $id,
                )));
        return ($count === 0);
    }
}

