<?php

trait InputCheckTrait{

	public function checkType($value){
		if(in_array( strtolower($value), ['video']) ){
			return true;
		}
		return false;
	}
	
}
