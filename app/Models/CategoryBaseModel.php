<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CategoryBaseModel extends Eloquent {

	/**
	 *     Table name of model used
	 *     @var string
	 */
	protected $table = 'category';

	/**
	 *     Get Data for create Form in View
	 *     @param  integer $level
	 *     @return array
	 */
	public function getAllForm($level = 0, $defaultValue = '', $defaultOption = 'Select Category'){
		$listCountry = $this->orderBy('name','asc')
							->where('parent_id', '<=', $level)
							->where('status', 1)
							->get()
							->lists('name','id');
							
		return array($defaultValue=>$defaultOption) + $listCountry;
	}
			
}