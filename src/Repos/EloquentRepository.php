<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 5/30/2016
 * Time: 1:11 PM
 */

namespace Optimait\Laravel\Repos;


use Illuminate\Database\Eloquent\Model;
use Optimait\Laravel\Exceptions\EntityNotFoundException;

abstract class EloquentRepository
{
    protected $model;

    protected $attrToValidate;

    protected $attrToSave;

    /**
     * @param mixed $attrToSave
     */
    public function setAttrToSave($attrToSave)
    {
        $this->attrToSave = $attrToSave;
    }

    /**
     * @return mixed
     */
    public function getAttrToSave()
    {
        return $this->attrToSave;
    }

    /**
     * @param mixed $attrToValidate
     */
    public function setAttrToValidate($attrToValidate)
    {
        $this->attrToValidate = $attrToValidate;
    }

    /**
     * @return mixed
     */
    public function getAttrToValidate()
    {
        return $this->attrToValidate;
    }

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getAll($with = false)
    {
        if ($with) {
            return $this->model->with($with)->get();
        }
        return $this->model->all();
    }

    public function getAllPaginated($count)
    {
        return $this->model->paginate($count);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function requireById($id)
    {
        $model = $this->getById($id);

        if (!$model) {
            throw new EntityNotFoundException;
        }

        return $model;
    }

    public function getNew($attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    public function save($data)
    {
        if ($data instanceOf Model) {
            return $this->storeEloquentModel($data);
        } elseif (is_array($data)) {
            return $this->storeArray($data);
        }
    }

    public function delete($model)
    {
        return $model->delete();
    }

    protected function storeEloquentModel($model)
    {
        if ($model->getDirty()) {
            return $model->save();
        } else {
            return $model->touch();
        }
    }

    protected function storeArray($data)
    {
        $model = $this->getNew($data);
        return $this->storeEloquentModel($model);
    }


    public function simpleUpdate($id, $data)
    {

        $model = $this->getById($id);

        $model->fill($data);

        if ($model->save()) {

            return $model;
        }

        /*$leadModel->update($leadData);*/

        return false;

    }

    public function startTransaction()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
    }

    public function commitTransaction()
    {
        \Illuminate\Support\Facades\DB::commit();
    }

    public function rollbackTransaction()
    {
        \Illuminate\Support\Facades\DB::rollBack();
    }
}