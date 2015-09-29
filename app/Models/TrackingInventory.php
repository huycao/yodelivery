<?php namespace App\Models;

use Jenssegers\Mongodb\Model as Moloquent;

class TrackingInventory extends Moloquent{

	protected $table = 'trackings_inventory';
	protected $connection = 'mongodb';

    public function incTotalAdZoneInventory($fid, $fwid, $event){
		$sumID = "{$fid}_{$fwid}";
    	$check = $this->where(array(
    		'sum_id' => $sumID	
    	))->first();

    	if( empty($check) ){

    		$this->sum_id = $sumID;
    		$this->flight_id = $fid;
    		$this->flight_website_id = $fwid;
    		$this->inventory = (int) TrackingSummaryBaseModel::where(array(
				'flight_website_id' => $fwid, 
			))->sum($event) + 1;
			$this->save();
    	}else{
    		$check->increment('inventory');
    	}
    }

    public function getTotalAdZoneInventory($fid, $fwid){
    	$rs = 0;
		$sumID = "{$fid}_{$fwid}";
    	$check = $this->where(array(
    		'sum_id' => $sumID	
    	))->first();

    	if( empty($check) ){
			$rs = 0;
    	}else{
			$rs = $check->inventory;
    	}

    	return $rs;
    }


    public function incTotalInventory($fid, $event){
		$sumID =  (String) $fid;
    	$check = $this->where(array(
    		'sum_id' => $sumID	
    	))->first();

    	if( empty($check) ){

            $this->sum_id    = $sumID;
            $this->flight_id = $fid;
    		$this->inventory = (int) TrackingSummaryBaseModel::where(array(
				'flight_id' => $sumID, 
			))->sum($event) + 1;
			$this->save();

    	}else{
    		$check->increment('inventory');
    	}		

    }

    public function getTotalInventory($fid){
    	$rs = 0;
		$sumID = (String) $fid;
    	$check = $this->where(array(
    		'sum_id' => $sumID	
    	))->first();

    	if( empty($check) ){
			$rs = 0;
    	}else{
			$rs = $check->inventory;
    	}


    	return $rs;
    }

}
