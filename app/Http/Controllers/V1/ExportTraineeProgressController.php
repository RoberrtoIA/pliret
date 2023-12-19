<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Execution;
use App\Services\ExportCsvService;
use Illuminate\Support\Facades\Storage;
use SplFileObject;
use Illuminate\Support\Str;

class ExportTraineeProgressController extends Controller
{
    public function __construct(protected ExportCsvService $exportCsvService)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        $executions = Execution::findOrfail($id)
            ->with('program')
            ->with('assignments.module')
            ->with(['assignments.user.myExecutionsAsTrainee' => function ($query) use ($id) {
                $query->where('enrollments.execution_id', $id);
            }])->get();

        return $this->exportCsvService->upload($executions);
    }
}
