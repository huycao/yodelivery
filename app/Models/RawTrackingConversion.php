<?php namespace App\Models;
use Jenssegers\Mongodb\Model as Moloquent;
use RedisHelper;

class RawTrackingConversion extends Moloquent {

    protected $table      = 'trackings_conversion';
    protected $tableMySQL = 'tracking_conversion';
    protected $connection = 'mongodb';
    public $timestamps    = false;


    public function addConversion($conversionID, $campaignID, $param = ''){
        $index = date('YmdH_') . $conversionID;
        $this->cid     = intval($conversionID);
        $this->camp    = intval($campaignID);
        $this->param   = $param;
        $this->created = date('Y-m-d H:i:s');
    	return $this->save();
    }
    
    public function getConversionDataByTime($timeFrom = '', $timeTo = ''){
        if ('' == $timeFrom) {
            $timeFrom = date('Y-m-d 00:00:00');
        }
        if ('' == $timeTo) {
            $timeTo = date('Y-m-d 23:59:59');
        }
        return \DB::connection('mongodb')->collection($this->table)
                                         ->where('created', '>=', $timeFrom)
                                         ->where('created', '<=', $timeTo)
                                         ->get();
    }
    
    public function reportConversionDaily(){
        set_time_limit(0);
        $recordUpdated = 0;
        
        $data = (new RawTrackingConversion())->getConversionDataByTime();
        $recordUpdated = $this->generateConversionData($data);

        return $recordUpdated;
    }
    
    public function generateConversionData($rawConversionData){
        $recordUpdated = 0;
        if(empty($rawConversionData)){
            return $recordUpdated;
        }

        foreach ($rawConversionData as $row) {
            $dataUpdate = [];
            $where = [];
            $where = [
                'object_id' => $row['_id']->{'$id'}
            ];

            $dataUpdate = [
                'conversion_id' => $row['cid'],
            	'campaign_id'   => $row['camp'],
                'param'         => $row['param'],
            	'created_at'    => $row['created']
            ];

            $checkExists = \DB::table($this->tableMySQL)->select('id')->where($where)->first();

            if($checkExists){
                \DB::table($this->tableMySQL)->where('id', $checkExists->id)->update($dataUpdate);
            } else{
                $dataUpdate = array_merge($dataUpdate, $where);
                \DB::table($this->tableMySQL)->insert($dataUpdate);   
            }

            $recordUpdated++;
        }

        return $recordUpdated;
    }
}
