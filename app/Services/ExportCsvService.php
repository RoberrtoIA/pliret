<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SplFileObject;
use Illuminate\Support\Str;

class ExportCsvService
{
    public function upload($executions): string
    {
        $list = array(
            ['program_id', 'program', 'user_name', 'email', 'module_id', 'module', 'score', 'homework_grade', 'interview_grade'],

        );

        foreach ($executions as $execution) {
            foreach ($execution->assignments as $assignments) {
                foreach ($assignments->user->myExecutionsAsTrainee as $myExecutionsAsTrainee) {
                    array_push($list, [
                        $execution->program->id, $execution->program->title, $assignments->user->name,
                        $assignments->user->email, $assignments->module->id,
                        $assignments->module->title, $myExecutionsAsTrainee->enrollment->score,
                        $assignments->homework_grade, $assignments->interview_grade
                    ]);
                }
            }
        }

        $uuid = Str::uuid();

        $filePath = 'reports/Trainee_Progress' . $uuid . '.csv';
        $created = Storage::put('public/reports/Trainee_Progress' . $uuid . '.csv', 'w');

        if (!$created) {
            return $created;
        }

        $file = new SplFileObject(public_path('storage/' . $filePath), 'w');

        foreach ($list as $fields) {
            $file->fputcsv($fields);
        }

        return $filePath;
    }
}
