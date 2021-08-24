<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Question extends Model
{
    use Uuids;

    const CREATED_AT = "createdAt";
    const UPDATED_AT = "updatedAt";

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "questionId";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "questions";

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = "string";

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["content", "status", "typeId"];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ["createdAt", "updatedAt"];

    public function options()
    {
        return $this->hasMany(Option::class, "questionId", "questionId");
    }
}
