<?php namespace App\Models;

use Jenssegers\Mongodb\Model as Moloquent;

class TrackingOverInventory extends Moloquent{

	protected $table = 'trackings_over_inventory';
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
                'ovr'            => 1
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
			$sumID = (String) $fid;
	    	$check = $this->where(array(
	    		'sum_id' => $sumID	
	    	))->first();

	    	if( empty($check) ){

	    		$this->sum_id = $sumID;
	    		$this->flight_id = $fid;
	    		$this->inventory = (int) TrackingSummaryBaseModel::where(array(
                    'flight_id' => $fid, 
                    'ovr'    => 1
				))->sum($event);
				$this->save() + 1;

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
