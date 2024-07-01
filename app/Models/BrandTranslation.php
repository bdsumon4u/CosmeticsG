<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class BrandTranslation extends Model
{
  /**
   * The connection name for the model.
   *
   * @var string|null
   */
  protected $connection = 'oninda';

  protected $fillable = ['name', 'lang', 'brand_id'];

  public function brand(){
    return $this->belongsTo(Brand::class);
  }
}
