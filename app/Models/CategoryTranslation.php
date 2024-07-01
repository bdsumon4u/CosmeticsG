<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class CategoryTranslation extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'oninda';

    protected $fillable = ['name', 'lang', 'category_id'];

    public function category(){
    	return $this->belongsTo(Category::class);
    }
}
