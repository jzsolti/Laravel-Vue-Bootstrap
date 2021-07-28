<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Models\Article;

class ArticlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'detail' => '/articles/'.$this->id,
            'title' => $this->title,
            'lead' => Str::limit($this->lead, 40),
            'image_src' => !is_null($this->image)? Article::IMAGE_URL . $this->image : null,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
