<?php namespace IDesigning\Feedback\Models;

use Model;
use October\Rain\Database\Builder;
use October\Rain\Database\QueryBuilder;
use October\Rain\Database\Traits\Validation;

/**
 * Feedback Model
 */
class Feedback extends Model
{
    use Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'idesigning_feedback_feedbacks';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'files',
        'channel_id'
    ];

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'name' => 'string|required',
        'email' => 'email',
        'message' => 'required',
        'channel_id' => 'integer|required'
    ];


    /**
     * @var array The array of custom attribute names.
     */
    public $attributeNames = [
        'name' => 'idesigning.feedback::lang.feedback.name',
        'email' => 'idesigning.feedback::lang.feedback.email',
        'phone' => 'idesigning.feedback::lang.feedback.phone',
        'files' => 'idesigning.feedback::lang.feedback.files',
        'message' => 'idesigning.feedback::lang.feedback.message'
    ];

    /**
     * @var array The array of custom error messages.
     */
    public $customMessages = [
        'email' => 'idesigning.feedback::lang.component.onSend.error.email.email',
        'message' => 'idesigning.feedback::lang.component.onSend.error.message.required'
    ];


    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'channel' => '\IDesigning\Feedback\Models\Channel'
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'files' => [ 'System\Models\File' ]
    ];

    protected $jsonable = [ 'extra' ];


    public static function archive($query)
    {
        $query->update(['archived' => true]);
    }

}