<?php namespace App\Models;
use RedisHelper;
use Predis\Client as Client;

class RawTrackingSummary{

    protected $table      = 'trackings_summary';
    protected $tableMySQL = 'tracking_summary';
    protected $connection = 'mongodb';
    public $timestamps    = false;
    private static $redis_connection;
    
    const CACHE_1D_MINUTES = 86400;
    const CACHE_HALF_YEAR_MINUTES = 15768000;


    public static function connection() {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
        self::$redis_connection = $redis->connection;
        return self::$redis_connection;
    }
   
    public function addSummary($metric, $websiteID, $adZoneID, $adformatID, $flightID, $flightWebsiteID, $adID, $campaignID, $base_cost = 0, $overReport = 0){

        //make index attribute to group data summary by flight website
        $index = date('YmdH_') . $flightWebsiteID . '_' . intval($base_cost); // index theo flight website và theo từng giờ
        if ($overReport) {
            $index .= '_ovr';
        }
        $info = [
            '_id'                  => $index,
            'created_d'            => date('Y-m-d'),
            'created_h'            => date('Y-m-d H'),
            'f'                    => intval($flightID),
            'fw'                   => intval($flightWebsiteID),
            'w'                    => intval($websiteID),
            'az'                   => intval($adZoneID),
            'a'                    => intval($adID),
            'camp'                 => intval($campaignID),
            'af'                   => intval($adformatID),
            // 'impression'        => 500, //impression
            // 'unique_impression' => 100, // unique impression
            // 'click'             => 300, // click
            // 'unique_click'      => 100, // unique click
            // 'ads_request'       => 1000 // ads request
            // .. etc
        ];

        $where = [
            '_id'   =>  $index
        ];
    	return $this->incrementUpsert($metric, 1, $where, $info);
    }

    public function addSummaryRequestEmptyAd($adZoneID, $adformatID, $websiteID, $overReport = 0){

        $metric = "ads_request";

        $indexKey = implode('_', [$adZoneID, $adformatID, $websiteID]);
        
        $index  = date('Ymdh_') . "noads_" . $indexKey; // index theo ad zone, website và adformat và theo từng giờ
        if ($overReport) {
            $index .= '_ovr';
        }
        $info = [
            '_id'       => $index,
            'created_d' => date('Y-m-d'),
            'created_h' => date('Y-m-d H'),
            'az'        => intval($adZoneID),
            'af'        => intval($adformatID),
            'w'         => intval($websiteID),
            // 'ar'     => 1000 // ads request
        ];

        $where = [
            '_id'   =>  $index
        ];

        return $this->incrementUpsert($metric, 1, $where, $info);
    }


    /**
     * increment record's column value if exists or insert new record.
     *
     * @param  array  $values
     * @param  array  $options
     * @return int
     */
    public function incrementUpsert($column, $amount = 1, array $where = array(), array $extra = array())
    {   
        $db = \DB::connection('mongodb')->collection($this->table);
        $query = array('$inc' => array($column => $amount));

        if ( ! empty($extra))
        {
            $query['$set'] = $extra;
        }
       
        // Protect
        $db->where(function($query) use ($column)
        {
            $query->where($column, 'exists', false);

            $query->orWhereNotNull($column);
        });

        if( ! empty($where))
        {
            $db->where($where);
        }

        return $db->update($query, array('upsert' => true, 'multiple' => 0));

    }
    /**
     * Decrement a column's value by a given amount.
     *
     * @param  string  $column
     * @param  int     $amount
     * @param  array   $extra
     * @return int
     */
    public function decrementUpsert($column, $amount = 1, array $extra = array())
    {
        return $this->incrementUpsert($column, -1 * $amount, $extra);
    }


    public function getRecentHourData($timestamp = ''){
        $dateH = !empty($timestamp) ? date('Y-m-d H', $timestamp) : date('Y-m-d H');
        return \DB::connection('mongodb')->collection($this->table)->where('created_h', $dateH)->get();
    }

    public function getSummaryDayData($timestamp = ''){
        $date = !empty($timestamp) ? date('Y-m-d', $timestamp) : date('Y-m-d');
        return \DB::connection('mongodb')->collection($this->table)->where('created_d', $date)->get();
    }


    public function reportScheduleHourly(){
        set_time_limit(0);
        $recordUpdated = 0;
        
        // begin summary data
        //current hour
        $data = (new RawTrackingSummary())->getRecentHourData(strtotime("-1 hour"));

        $recordUpdated = $this->generateSummaryData($data);
        return $recordUpdated;
        
    }

    public function reportScheduleDaily(){
        //run at 2.00 AM
        set_time_limit(0);
        $recordUpdated = 0;
        
        // begin summary data of yesterday
        $data = (new RawTrackingSummary())->getSummaryDayData(strtotime("-1 day"));
        $recordUpdated = $this->generateSummaryData($data);

        return $recordUpdated;
    }

    public function generateSummaryData($rawSummaryData){
        $recordUpdated = 0;
        if(empty($rawSummaryData)){
            return $recordUpdated;
        }

        foreach ($rawSummaryData as $row) {
            $dataUpdate = [];
            $where = [];
            $where = [
                'website_id'           =>   $row['w'],
                'publisher_ad_zone_id' =>   $row['az'],
                'ad_format_id'         =>   $row['af'],
                'hour'                 =>   intval(substr($row['created_h'], -2)),
                'date'                 =>   $row['created_d']
            ];
            
            if ('ovr' == substr($row['_id'], -3)) {
                $where['ovr'] = 1;
            } else {
                $where['ovr'] = 0;
            }

            if(strpos($row['_id'], 'noads') === FALSE){
                //summary of tracking success
                $where['flight_id']         = $row['f'];
                $where['flight_website_id'] = $row['fw'];
                $where['campaign_id']       = $row['camp'];
                $where['ad_id']             = $row['a'];
            }
            else{
                //summary ads request of adzone and adformat when no ads deliveried
                $where['flight_website_id'] = 0;
            }

            //list metrics to repport
            $metrics = [
                //general metrics
                'impression','unique_impression','click','unique_click','ads_request','conversion',
                //TVC metrics
                'start','firstquartile','midpoint','thirdquartile','complete','pause','unpause','mute','unmute','fullscreen',
            ];

            $checkExists = \DB::table($this->tableMySQL)->select('id')->where($where)->first();
     
            foreach ($metrics as $metric) {
                if(!empty($row[$metric])){
                    $dataUpdate[$metric] = $row[$metric];
                }
            }

            if($checkExists){
                \DB::table($this->tableMySQL)->where('id', $checkExists->id)->update($dataUpdate);
            }
            else{
                $dataUpdate = array_merge($dataUpdate, $where);
                \DB::table($this->tableMySQL)->insert($dataUpdate);   
            }
            // pr($dataUpdate);

            $recordUpdated++;
        }

        return $recordUpdated;
    }
    
    public function updateInventory($flightID, $flightWebsiteID, $event, $overReport = false) {
        $this->incTotalAdZoneInventory($flightID, $flightWebsiteID, $event, $overReport);
        $this->incTotalInventory($flightID, $event, $overReport);
    }
    
    public function incTotalAdZoneInventory($fid, $fwid, $event, $overReport = false) {
        $date = date('Ymd');
        $cacheKeyInDay = "Flight_Inventory_{$fid}_{$date}_{$fwid}_{$event}";        
        $cacheKey = "Flight_Inventory_{$fid}_{$fwid}_{$event}";
        
        if ($overReport) {
            $cacheKeyInDay .= "_ovr";
            $cacheKey .= "_ovr";
        }
        
        if ($this->connection()->exists($cacheKeyInDay)) {
            $this->connection()->incr($cacheKeyInDay);
        } else {
            $fwInventoryInDay = $this->getInventoryFromDB($fid, $event, $fwid, date('Y-m-d'), $overReport);
            $this->connection()->setex($cacheKeyInDay, self::CACHE_1D_MINUTES, $fwInventoryInDay);
        }
        
        if ($this->connection()->exists($cacheKey)) {
            $this->connection()->incr($cacheKey);
        } else {
            $fwInventory = $this->getInventoryFromDB($fid, $event, $fwid, '', $overReport);
             $this->connection()->setex($cacheKey, self::CACHE_HALF_YEAR_MINUTES, $fwInventory);
        }        
    }
    
    public function incTotalInventory($fid, $event, $overReport = false) {
        $date = date('Ymd');
        $cacheKeyInDay = "Flight_Inventory_{$fid}_{$date}_{$event}";
        $cacheKey = "Flight_Inventory_{$fid}_{$event}";
        
        if ($overReport) {
            $cacheKeyInDay .= "_ovr";
            $cacheKey .= "_ovr";
        } 
        
        if ($this->connection()->exists($cacheKeyInDay)) {
            $this->connection()->incr($cacheKeyInDay);
        } else {
            $inventoryInDay = $this->getInventoryFromDB($fid, $event, '', date('Y-m-d'), $overReport);
            $this->connection()->setex($cacheKeyInDay, self::CACHE_1D_MINUTES, $inventoryInDay);
        }
        
        if ($this->connection()->exists($cacheKey)) {
            $this->connection()->incr($cacheKey);
        } else {
            $inventory = $this->getInventoryFromDB($fid, $event, '', '', $overReport);
            $this->connection()->setex($cacheKey, self::CACHE_HALF_YEAR_MINUTES, $inventory);
        }
    }
    
    public function getTotalAdZoneInventoryInDay($fid, $fwid, $event, $overReport = false){
        $date = date('Ymd');
        $cacheKey = "Flight_Inventory_{$fid}_{$date}_{$fwid}_{$event}";
        
        if ($overReport) {
            $cacheKey .= "_ovr";
        }
        
        $retval = $this->connection()->get($cacheKey);
        
        if (empty($retval)) {
            $retval = $this->getInventoryFromDB($fid, $event, $fwid, date('Y-m-d'), $overReport);
            $this->connection()->setex($cacheKey, self::CACHE_1D_MINUTES, $retval);
        }
        
        return $retval;
    }
    
    public function getTotalInventoryInDay($fid, $event, $overReport = false){
        $date = date('Ymd');
        $cacheKey = "Flight_Inventory_{$fid}_{$date}_{$event}";
        
        if ($overReport) {
            $cacheKey .= "_ovr";
        }
        
        $retval = $this->connection()->get($cacheKey);
        
        if (empty($retval)) {
            $retval = $this->getInventoryFromDB($fid, $event, '', date('Y-m-d'), $overReport);
            $this->connection()->setex($cacheKey, self::CACHE_1D_MINUTES, $retval);
        }
        
        return $retval;
    }
    
    public function getTotalAdZoneInventory($fid, $fwid, $event, $overReport = false){
        $cacheKey = "Flight_Inventory_{$fid}_{$fwid}_{$event}";
        
        if ($overReport) {
            $cacheKey .= "_ovr";
        }
        
        $retval = $this->connection()->get($cacheKey);
        
        if (empty($retval)) {
            $retval = $this->getInventoryFromDB($fid, $event, $fwid, '', $overReport);
            $this->connection()->setex($cacheKey, self::CACHE_HALF_YEAR_MINUTES, $retval);
        }
        
        return $retval;
    }
    
    public function getTotalInventory($fid, $event, $overReport = false){
        $cacheKey = "Flight_Inventory_{$fid}_{$event}";
        
        if ($overReport) {
            $cacheKey .= "_ovr";
        }
        
        $retval = $this->connection()->get($cacheKey);
        
        if (empty($retval)) {
            $retval = $this->getInventoryFromDB($fid, $event, '', '', $overReport);
            $this->connection()->setex($cacheKey, self::CACHE_HALF_YEAR_MINUTES, $retval);
        }
        
        return $retval;
    }
    
    public function getInventoryFromDB($fid, $event, $fwid = '', $date = '', $overReport = false) {
        if ($fid) {
            $where['f'] = intval($fid);
        }
        if ($fwid) {
            $where['fw'] = intval($fwid);
        }
        if ($date) {
            $where['created_d'] = $date;
        }
        
        $rs = \DB::connection('mongodb')->collection($this->table)->where($where)->get();
        
        $inventory = 0;
        $inventoryOvr = 0;
        foreach ($rs as $val) {
            if (isset($val[$event])) {
                if ('ovr' === substr($val['_id'], -3)) {
                    $inventoryOvr += $val[$event];
                } else {                    
                    $inventory += $val[$event];
                }
            }
        }
        
        if ($overReport) {
            $retval = $inventoryOvr;
        } else {
            $retval = $inventory;
        }
        
        return $retval;
    }
}
