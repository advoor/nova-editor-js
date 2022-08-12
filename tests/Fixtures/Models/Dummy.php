<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Advoor\NovaEditorJs\NovaEditorJsCast;
use Advoor\NovaEditorJs\NovaEditorJsData;
use Illuminate\Database\Eloquent\Model;

/**
 * @property NovaEditorJsData $data
 * @method static \Illuminate\Database\Eloquent\Builder|Dummy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dummy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dummy query()
 * @mixin \Eloquent
 */
class Dummy extends Model
{
    protected $casts = [
        'data' => NovaEditorJsCast::class,
    ];

    protected $fillable = [
        'data',
    ];
}
