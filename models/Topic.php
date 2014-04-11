<?php namespace RainLab\Forum\Models;

use App;
use Model;

/**
 * Topic Model
 */
class Topic extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_forum_topics';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules
     */
    public $rules = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];

    public $hasMany = [
        'posts' => ['RainLab\Forum\Models\Post']
    ];

    public $belongsTo = [
        'channel' => ['RainLab\Forum\Models\Channel']
    ];

    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function listFrontEnd($page = 1, $sort = 'created_at', $channels = [], $search = '')
    {
        App::make('paginator')->setCurrentPage($page);
        $search = trim($search);

        $allowedSortingOptions = ['created_at', 'updated_at', 'name'];
        if (!in_array($sort, $allowedSortingOptions))
            $sort = $allowedSortingOptions[0];

        $obj = $this
            ->orderBy($sort, $sort != 'created_at' ? 'asc' : 'desc')
        ;

        if (strlen($search)) {
            $obj->searchWhere($search, ['title', 'count_posts']);
        }

        if ($channels) {
            $obj->whereIn('channel_id', $channels);
        }

        return $obj->paginate(20);
    }
}