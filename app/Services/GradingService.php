<?php

namespace App\Services;

use App\Factories\GradingResourceFactory;
use App\Models\Grading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class GradingService
{
    public function __construct(protected GradingResourceFactory $resourceFactory)
    {
    }

    public function takeSnapshot(Grading $grading, Model $gradable, string $gradableType): Grading
    {
        $grading->snapshot = $this->resourceFactory->make($gradable, $gradableType)->resolve();
        $grading->save();

        return $grading;
    }

    public function upsert(FormRequest $request, string $gradableType)
    {
        $attributes = $request->validated();

        Validator::make($attributes, [
            'gradables.*.gradable_id' => '|exists:' . $gradableType . ',id,deleted_at,NULL',
        ])->validate();

        $assignmentId = $attributes['assignment_id'];

        $gradables = collect($attributes['gradables'])->map(function ($item)
        use ($gradableType, $assignmentId) {
            return array_merge($item, [
                'assignment_id' => $assignmentId,
                'gradable_type' => $gradableType,
            ]);
        });

        Grading::query()->upsert(
            $gradables->all(),
            ['assignment_id', 'gradable_id', 'gradable_type'],
            ['comments', 'grade']
        );

        return $this->getUpdatedGradings($gradables, $assignmentId, $gradableType);
    }

    protected function getUpdatedGradings(Collection $gradables, $assignmentId, string $gradableType)
    {
        $identifiers = $gradables->map(function ($item) {
            return $item['gradable_id'];
        })->all();

        return Grading::query()
            ->where('assignment_id', $assignmentId)
            ->whereHasMorph('gradable', $gradableType, function ($query) use ($identifiers) {
                $query->whereIn('id', $identifiers);
            })
            ->with('gradable')
            ->get();
    }
}
