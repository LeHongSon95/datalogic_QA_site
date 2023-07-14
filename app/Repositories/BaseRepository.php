<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryInterface;
abstract class BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 6;
    /**
     * @var $model
     */
    protected $model;
    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }
    /**
     * set reponsitory instant
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }
    /**
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }
    /**
     * @param string $orderBy
     * @param $toArray
     * @param array $columns
     * @return mixed
     */
    public function all($orderBy = 'id desc', $toArray = false, $columns = array('*'))
    {
        $data = $this->model->orderByRaw($orderBy);
        if ($toArray === null) {
            return $data;
        }
        if ($toArray) {
            return $data->paginate(self::PAGE_SIZE)->getCollection()->toArray();
        }
        return $data->paginate(self::PAGE_SIZE);
    }
    /**
     * @param int $perPage Per page
     * @param array $columns Array Columns
     * @return array Return data paginate
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }
    /**
     * @param array $data Array data
     * @return void
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    /**
     * @param array $data Array data
     * @param $id ID
     * @param string $attribute Attribute
     * @return void
     */
    public function updateMultiColumm(array $data, array $columns)
    {
        return $this->model->where($columns)->update($data);
    }
    /**
     * @param array $data Array data
     * @param $id ID
     * @param string $attribute Attribute
     * @return void
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }
    /**
     * @param $id ID
     * @return void
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    /**
     * @param $id ID
     * @param array $columns Columns
     * @return array Return array
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }
    /**
     * @param $attribute Attribute
     * @param $value Value
     * @param array $columns Columns
     * @return array Array
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
    public function findByReturnArray($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->get($columns);
    }
    public function findByReturnArrayPlug($attribute, $value, $pluck, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->get($columns)->pluck($pluck);
    }
    public function findByMultiConditionsLimit($whereData = array(), $columns = array('*'))
    {
        return $this->model->where($whereData)->first($columns);
    }
    /**
     * @param $tableName Table name
     * @param array $whereData Array where data
     * @return array Return array
     */
    public function findByMultiConditions($tableName, $whereData = array())
    {
        return DB::table($tableName)->where($whereData)->get();
    }
    /**
     * @param $tableName Table name
     * @param array $whereData Array where data
     * @return array Return array
     */
    public function pagingWithMultiConditions($tableName, $whereData = array(), $perPage = 15, $orderBy = 'id desc', $columns = array('*'))
    {
        return DB::table($tableName)
            ->where($whereData)
            ->orderByRaw($orderBy)
            ->paginate($perPage, $columns);
    }
    public function delByMultiConditions($tableName, $whereData = array())
    {
        return DB::table($tableName)
            ->where($whereData)
            ->delete();
    }
    /**
     * @param array $whereData
     * @return mixed
     */
    public function delByMultiConditionsModel($whereData = array())
    {
        return $this->model
            ->where($whereData)
            ->delete();
    }
    /**
     * update records by multi conditions
     *
     * @param $tableName Table name
     * @param array $data Array data
     * @param array $whereData Array where data
     * @return void
     */
    public function updateByMultiConditionsModel(array $data, $whereData = array())
    {
        return $this->model
            ->where($whereData)
            ->update($data);
    }
    /**
     * @param $tableName
     * @param array $whereData
     * @return mixed
     */
    public function countByMultiConditions($tableName, $whereData = array())
    {
        return DB::table($tableName)
            ->where($whereData)
            ->count();
    }
    /**
     * @param array $whereData
     * @return mixed
     */
    public function countByMultiConditionsModel($whereData = array())
    {
        return $this->model
            ->where($whereData)
            ->count();
    }
    /**
     * @param array $whereData
     * @param string $orderBy
     * @param array $select
     * @return mixed
     */
    public function getByMultiConditionsModel($whereData = array(), $orderBy = "id desc", $select = array("*"))
    {
        return $this->model
            ->select($select)
            ->where($whereData)
            ->orderByRaw($orderBy)
            ->get();
    }
    public function getByMultiConditionsPagination($tableName, $whereData = array(), $select = array("*"), $orderBy = "id desc", $perpage = 10)
    {
        return DB::table($tableName)
            ->select($select)
            ->where($whereData)
            ->orderByRaw($orderBy)
            ->paginate($perpage);
    }
    public function getByInConditions($tableName, $field, $data = array(), $select = array("*"), $orderBy = "id desc")
    {
        return DB::table($tableName)
            ->select($select)
            ->whereIn($field, $data)
            ->orderByRaw($orderBy)
            ->get();
    }
    public function getManyWhereOrder($whereData = array(), $select = array("*"), $orderBy = "id asc")
    {
        return $this->model
            ->where($whereData)
            ->select($select)
            ->orderByRaw($orderBy)
            ->get();
    }
    public function getManyWhereOrderLimitNumber($whereData = array(), $limit = 5, $select = array("*"), $orderBy = "id desc")
    {
        return $this->model
            ->where($whereData)
            ->select($select)
            ->orderByRaw($orderBy)
            ->limit($limit)
            ->get();
    }
    public function getManyWhereOrderLimit($whereData = array(), $select = array("*"), $orderBy = "id desc")
    {
        return $this->model
            ->where($whereData)
            ->select($select)
            ->orderByRaw($orderBy)
            ->first();
    }
    public function getManyWhereOrderPagination($whereData = array(), $select = array("*"), $orderBy = "id desc", $perpage = 10)
    {
        return $this->model
            ->where($whereData)
            ->select($select)
            ->orderByRaw($orderBy)
            ->paginate($perpage);
    }
    public function searchItemInStringList($stringList, $columnSearch, $select = array("*"))
    {
        return $this->model
            ->select($select)
            ->whereRaw("find_in_set(" . $columnSearch . ", '" . $stringList . "')")
            ->get();
    }
    public function searchItemInStringListUnequi($stringList, $columnSearch, $select = array("*"), $whereData = array())
    {
        return $this->model
            ->select($select)
            ->distinct()
            ->where($whereData)
            ->whereRaw("find_in_set(" . $columnSearch . ", '" . $stringList . "')")
            ->get();
    }
    public function deleteByMultiConditions($whereData = array())
    {
        return $this->model
            ->where($whereData)
            ->delete();
    }
    public function findByMultiConditionsModelLimit($whereData = array(), $orderBy = "id asc")
    {
        return $this->model
            ->where($whereData)
            ->orderByRaw($orderBy)
            ->first();
    }
}
