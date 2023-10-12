<?php

namespace App\Services;

use App\Models\User;
use App\Models\Program;

class ProgramService
{
    public function __construct(
        protected TagService $tagService,
        protected UserRoleService $roleService,
    ) {
    }

    public function createProgram($attributes)
    {
        $tags = $attributes['tags'];

        unset($attributes['tags']);

        $program = Program::create($attributes);

        if (strpos($tags, ',') !== false) {

            $tags = explode(",", $tags);

            $tag_sync = [];

            foreach ($tags as $tag) {
                $tags_sync[] = $this->tagService->createTag($tag);;
            }

            foreach ($tags_sync as $tag_sync) {
                $program->tags()->attach($tag_sync);
            }
        } else {

            $tag_sync = $this->tagService->createTag($tags);

            $program->tags()->attach($tag_sync);
        }

        $program->save();

        return $program;
    }

    public function updateProgram(Program $program, array $attributes): Program
    {
        $program->update($attributes);

        if ($attributes['tags'] ?? false) {

            $program->tags()->detach();

            $tags = $attributes['tags'];

            if (strpos($tags, ',') !== false) {

                $tags = explode(",", $tags);

                $tag_sync = [];

                foreach ($tags as $tag) {
                    $tags_sync[] = $this->tagService->createTag($tag);
                }

                foreach ($tags_sync as $tag_sync) {
                    $program->tags()->attach($tag_sync);
                }
            } else {

                $tag_sync = $this->tagService->createTag($tags);

                $program->tags()->attach($tag_sync);
            }

            $program->save();
        }

        return $program;
    }

    public function assignDeveloper(Program $program, User $user)
    {
        $this->roleService->validateDeveloperRole($user);

        $program->developers()->attach($user);

        return $program;
    }
}