<?php

class MakeJsHandle {

	public $inputAccecpt = 
	[
		'type',
		'wid',
		'zid',
		'eid',
		'ew',
		'eh',
		'sw',
		'sh',
		'scd',
		'fv',
		'l',
		'iej'
	];

	public $validateRule = [
		'type'	=>	'in:video,popup',
		'wid'	=>	'numeric',
		'zid'	=>	'numeric',
		'ew'	=>	'numeric',
		'eh'	=>	'numeric'
	];

	public function checkInputValid($input){

		$inputFilled = [];

		foreach( $input as $key =>	$value ){
			if( in_array($key, $this->inputAccecpt) ){
				$inputFilled[$key] = $value;
			}
		}
        $validate = Validator::make($inputFilled, $this->validateRule);

        return $validate->passes();

	}

	public function getAdJsType($name){

		switch ($name) {
			case 'Video Vast':
				return 'videoInline';
				break;

			case 'Universal Tag':
				return 'videoPopup';
				break;
			
			default:
				break;
		}

		return false;

	}


}