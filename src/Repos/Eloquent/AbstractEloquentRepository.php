<?php
/**
 * Created by PhpStorm.
 * User: drudge
 * Date: 9/10/17
 * Time: 11:44 AM
 */

namespace Optimait\Laravel\Repos\Eloquent;

/**
 * Class AbstractEloquentRepository
 * @package Optimait\Laravel\Repos\Eloquent
 * @author Rajendra Sharma <drudge.rajan@gmail.com>
 */
abstract class AbstractEloquentRepository
{

    abstract public function setAttrToSave($attrToSave);
    abstract public function getAttrToSave();
    abstract public function setAttrToValidate($attrToValidate);
    abstract public function getAttrToValidate();
    abstract public function getAll($with = false);
    abstract public function getAllPaginated($count);
    abstract public function getById($id);
    abstract public function requireById($id);
    abstract public function save($data);
    abstract public function delete($model);
    abstract public function storeEloquentModel($model);
    abstract public function storeArray($data);
    abstract public function startTransaction();
    abstract public function commitTransaction();
    abstract public function rollbackTransaction();
    abstract public function findOneBy($filter=[], $callback = null);
    abstract public function findBy($filter=[], $callback = null);
    abstract public function checkDuplicates($filter=[], $id = []);
    abstract public function getDataWithRelationModelById(array $with, $id = null);
    abstract public function getAllByWhereAndRelationModel(array $where, array $with);


}