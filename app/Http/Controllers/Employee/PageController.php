<?php

namespace App\Http\Controllers\Employee;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller {

    /**
     * This function returns the view with the list of Employees
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEmployees() {

        try {
            $allEmployees = EmployeeService::getEmployees();

            return view('listEmployees')->with(
                [
                    'all_employees' => json_encode($allEmployees)
                ]);
        } catch (\Exception $e) {
            \Log::error(__CLASS__ . '-->' . __FUNCTION__ . ': ' . $e->getMessage());
            return view('listEmployees')->with(['all_employees' => json_encode([])]);
        }
    }

    /**
     *
     * This function enables the people to search Employees by their UUID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEmployee(Request $request)
    {
        Log::error($request->all());
        $validator = Validator::make($request->all(), [
            'search_parameter' => 'bail|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid UUID',
                'success' => false
            ]);
        }

        if (preg_match_all('/.+[0-9]+.+/m', $request->input('search_parameter'))) {
            $query = 'uuid';
        } else {
            $query = 'name';
        }

        if ($employee = Employee::where($query, $request->input('search_parameter'))->first()) {
            return response()->json([
                'employee' => json_encode($employee),
                'success' => true
            ]);
        }

        return response()->json([
            'employee' => 'Employee does not exist',
            'success' => false
        ]);
    }

}
