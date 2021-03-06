<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class TasksModel extends Model
{
    /**
     * table name
     */
    protected $table = 'tasks';
    /**
     * @param $allInputs
     * @return mixed
     */
    public function insertRow($allInputs)
    {
        return TasksModel::insertGetId(
            [
                'name' => $allInputs['name'],
                'employee_id' => $allInputs['employee_id'],
                'duration' => $allInputs['duration'],
                'is_active' => 1,
                'created_at' => Carbon::now()
            ]
        );
    }

    /**
     * @param $id
     * @param $allInputs
     * @return mixed
     */
    public function updateRow($id, $allInputs)
    {
        return TasksModel::where('id', '=', $id)->update(
            [
                'name' => $allInputs['name'],
                'employee_id' => $allInputs['employee_id'],
                'duration' => $allInputs['duration'],
                'is_active' => 1,
                'updated_at' => Carbon::now()
            ]);
    }

    /**
     * @param $rulesType
     * @return array
     */
    public function getRules($rulesType)
    {
        switch ($rulesType) {
            case 'STORE':
                return [
                    'name' => 'required',
                    'employee_id' => 'required',
                    'duration' => 'required'
                ];
        }
    }

    /**
     * @param $id
     * @param $activeType
     * @return bool
     */
    public function setActive($id, $activeType)
    {
        $findTasksById = TasksModel::where('id', '=', $id)->update(
            [
                'is_active' => $activeType
            ]);

        if ($findTasksById) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param $id
     * @param $completeType
     * @return bool
     * @internal param $activeType
     */
    public function setCompleted($id, $completeType)
    {
        $findTasksById = TasksModel::where('id', '=', $id)->update(
            [
                'completed' => $completeType
            ]);

        if ($findTasksById) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @return int
     */
    public static function countTasks()
    {
        return TasksModel::all()->count();
    }

    /**
     * @param $type
     * @param $value
     * @param int $paginationLimit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function trySearchTasksByValue($type, $value, $paginationLimit = 10)
    {
        return TasksModel::where($type, 'LIKE', '%' . $value . '%')->paginate($paginationLimit);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employees()
    {
        return $this->belongsTo(EmployeesModel::class, 'employee_id');
    }

    /**
     * @param $isCompleted
     * @return mixed
     */
    public static function getAllCompletedAndUncompletedTasks($isCompleted)
    {
        $tasks = TasksModel::where('completed', '=', $isCompleted)->count();

        $taskAll = TasksModel::all()->count();

        $percentage = round(($tasks / $taskAll) * 100);

        return $tasks . ' (' . $percentage .  '%)';
    }
}
