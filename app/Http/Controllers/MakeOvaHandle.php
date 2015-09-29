<?php namespace App\Http\Controllers;

class MakeOvaHandle {

	public $inputAccecpt = 
	[
		'wid',
		'zid',
		'fid',
		'aid',
	];

	public $validateRule = [
		'wid'	=>	'numeric',
		'zid'	=>	'numeric',
		'fid'	=>	'numeric',
		'aid'	=>	'numeric'
	];

	public function checkInputValid($input){

		$inputFilled = [];

		foreach( $input as $key =>	$value ){
			if( in_array($key, $this->inputAccecpt) ){
				$inputFilled[$key] = $value;
			}
		}
        $validate = \Validator::make($inputFilled, $this->validateRule);

        return $validate->passes();

	}

}