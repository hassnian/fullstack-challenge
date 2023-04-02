<?php

namespace App\Models;

use App\Helpers\Traits\ElasticSearchSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 *  App\Models\Article
 *
 * @property int id
 * @property string url
 * @property string image_url
 * @property string title
 * @property ?string content
 * @property string published_at
 * @property string source
 * @property string source_url
 * @property ?string author
 */
class Article extends Model
{
    use HasFactory;
    use ElasticSearchSearchable;

    protected $fillable = [
        'url',
        'image_url',
        'title',
        'content',
        'published_at',
        'source',
        'source_url',
        'author',
        'datasource'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at->format('Y-m-d H:i:s'),
            'authors' => $this->authors->map(function (Author $author){
                return [
                    'name' => $author->name,
                    'id' => $author->id,
                ];
            })->toArray(),
            'categories' => $this->categories->map(function (Category $category){
                return [
                    'name' => $category->name,
                    'id' => $category->id,
                ];
            })->toArray(),
            'datasource' => $this->datasource,
        ];
    }

    public static function getElasticSearchMappings()
    {
        return [
            'index' => 'articles',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'standard',
                        ],
                        'content' => [
                            'type' => 'text',
                            'analyzer' => 'standard',
                        ],
                        'published_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss',
                        ],
                        'authors' => [
                            'type' => 'nested',
                            'properties' => [
                                'name' => [
                                    'type' => 'text',
                                    'analyzer' => 'standard',
                                ],
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                        'categories' => [
                            'type' => 'nested',
                            'properties' => [
                                'name' => [
                                    'type' => 'text',
                                    'analyzer' => 'standard',
                                ],
                                'id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                        'datasource' => [
                            'type' => 'text',
                            'analyzer' => 'standard',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function forceReIndex()
    {
        event('eloquent.updated: App\Models\Article', $this);
    }
}
