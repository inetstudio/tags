<?php

namespace InetStudio\TagsPackage\Tags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InetStudio\TagsPackage\Tags\Contracts\Models\TaggableModelContract;

class TaggableModel extends Model implements TaggableModelContract
{
    protected $table = 'taggables';

    protected $fillable = [
        'tag_model_id',
        'taggable_id',
        'taggable_type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function tag(): BelongsTo
    {
        $tagModel = resolve('InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract');

        return $this->belongsTo(
            get_class($tagModel),
            'tag_model_id'
        );
    }

    public function model(): MorphTo
    {
        return $this->morphTo(
            'model',
            'taggable_type',
            'taggable_id'
        );
    }
}
