<?php

namespace App\Jobs;

use App\Api\Wrapper;
use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class HandleEmployees
 * @package App\Jobs
 */
class HandleEmployees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $api;

    /**
     * HandleEmployees constructor.
     * This job is used to add new employees which are not in the db
     * It's a cron job which can be run at a specified number of times
     */
    public function __construct() {
        if (!$this->api) {
            $this->api = new Wrapper();
        }
    }

    public function handle() {

        try {

            $allEmployees = $this->api->call('get', 'list');

            foreach ($allEmployees as $employee) {

                if (!Employee::where('uuid', $employee->uuid)->first()) {

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

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            exit();
        }

    }
}
