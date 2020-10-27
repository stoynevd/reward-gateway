<?php

namespace App\Services;

use App\Api\Wrapper;
use App\Employee;

class EmployeeService {

    /**
     * @return Employee[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     *
     * This function returns the Employees for the list that needs to be displayed
     * Instead of querying the API every time we can just get the expected information
     * from the DB
     *
     * In case of more records the DB query shoyld be chunked in order for the system to be
     * able to handle all the information that incomes
     *
     */
    public static function getEmployees() {

        if (!Employee::all()->count()) {
            $api = new Wrapper();

            $allEmployees = $api->call('get', 'list');

            foreach ($allEmployees as $employee) {

                Employee::create([
                    'uuid' => $employee->uuid,
                    'company' => $employee->company,
                    'bio' => !empty($employee->bio) ? strip_tags($employee->bio) : null,
                    'name' => $employee->name,
                    'title' => $employee->title,
                    'avatar' => !empty($employee->avatar) ? $employee->avatar : null,
                ]);

            }
        }

        return Employee::all();
    }

}
