<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function createTag($tag_name): Tag
    {
        if ($tag = Tag::where('name', $tag_name)->first()) {
            return $tag;
        } else {
            return Tag::create(['name' => $tag_name]);
        }
    }
}
