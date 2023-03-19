<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformCategories extends Model
{
   
    public function child()
	{
	   return $this->hasMany('Ecommify\Core\Models\PlatformCategories', 'parent_content_id','content_id');
	}

	// recursive, loads all descendants
	public function children()
	{
	   return $this->child()->with(['children'=>function($query){
	   		$query->select("content_id","content_name","parent_content_id");
	   }]);
	}
	
	public function parent()
	{
	   return $this->belongsTo('Ecommify\Core\Models\PlatformCategories', 'parent_content_id','content_id');
	}

	// recursive, loads all descendants
	public function parents()
	{
	   return $this->parent()->with(['parents'=>function($query){
	   		$query->select("content_id","content_name","parent_content_id");
	   }]);
	}
}