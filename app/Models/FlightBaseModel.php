<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class FlightBaseModel extends Eloquent {

    protected $table = 'flight';

/**
     *     Append attribute
     *     @var array
     */
    // protected $appends = array('statusText');

    /**
     *     Mass Assignment
     *     @var array
     */
    protected $fillable = array(
        'name',
        'category_id',
        'ad_id',
        'campaign_retargeting',
        'campaign_id',
        'publisher_id',
        'publisher_site_id',
        'publisher_ad_zone_id',
        'ad_format_id',
        'flight_objective',
        'allow_over_delivery_report',
        'remark',
        'date',
        'hour',
        'end_hour',
        'frequency_cap',
        'frequency_cap_free',
        'frequency_cap_time',
        //--Start--Phuong-VM add 05-05-2015
        'country',
        //--End--Phuong-VM add 05-05-2015
        'province',
        'type',
        'day',
        'age',
        'sex',
        'total_inventory',
        'value_added',
        'cost_type',
        //--Start--Phuong-VM add 11-05-2015
        'event',
        //--End--Phuong-VM add 11-05-2015
        'regional_buy',
        'base_media_cost',
        'media_cost',
        'real_base_media_cost',
        'real_media_cost',
        'discount',
        'cost_after_discount',
        'total_cost_after_discount',
        'agency_commission',
        'cost_after_agency_commission',
        'advalue_commission',
        'publisher_cost',
        'publisher_base_cost',
        'total_profit',
        'sale_profit',
        'sale_id',
        'company_profit',
        'created_by',
        'updated_by',
        'is_fix_inventory',
        'retargeting_url',
        'retargeting_show',
    	'retargeting_number',
        'status'
    );

    /**
     *     Get field will display in list
     */
    public function getShowField(){        
        return array(
            'id' =>  array(
                'label'         =>  trans("text.id"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            'campaign_id' =>  array(
                'label'         =>  trans("text.campaign"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            'publisher_site_id'  =>  array(
                'label'         =>  trans("text.section"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            'total_inventory'  =>  array(
                'label'         =>  trans("text.total_inventory"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            'day'  =>  array(
                'label'         =>  trans("text.days"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            'cost_type'  =>  array(
                'label'         =>  trans("text.cost_type"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            ),
            //--Phuong-VM -- add -- 11-05-2015
            'event'  =>  array(
                'label'         =>  trans("text.event"),
                'type'          =>  'text',
                'sortable'      =>  FALSE
            )
        );   
    }

    /**
     *     Get validate rule when update
     */
    public function getUpdateRules(){
        return array(
            'campaign_id'                   =>  'required',
            'category_id'                   =>  'required',
            'date'                          =>  'required',
            'total_inventory'               =>  'required',
            //'value_added'                   =>  'required',
            'cost_type'                     =>  'required',
            //--Phuong-VM -- add -- 11-05-2015
            //'event'     	                =>  'required',
            'base_media_cost'               =>  'required',
            'media_cost'                    =>  'required'
        );
    }

    /**
     *     Get validate message when update
     */
    public function getUpdateLangs(){
        return array(
            'campaign_id.required'                  =>  trans('flight::validate.campaign_id.required'),
            'category_id.required'                  =>  trans('flight::validate.category_id.required'),
            'publisher_id.required'                 =>  trans('flight::validate.publisher_id.required'),
            'publisher_site_id.required'            =>  trans('flight::validate.publisher_site_id.required'),
            'publisher_ad_zone_id.required'         =>  trans('flight::validate.publisher_ad_zone_id.required'),
            'flight_objective.required'             =>  trans('flight::validate.flight_objective.required'),
            'allow_over_delivery_report.required'   =>  trans('flight::validate.allow_over_delivery_report.required'),
            'date.required'                         =>  trans('flight::validate.date.required'),
            'total_inventory.required'              =>  trans('flight::validate.total_inventory.required'),
            'value_added.required'                  =>  trans('flight::validate.value_added.required'),
            'cost_type.required'                    =>  trans('flight::validate.cost_type.required'),
            //--Phuong-VM -- add -- 11-05-2015
            'event.required'                 		=>  trans('flight::validate.event.required'),
            'base_media_cost.required'              =>  trans('flight::validate.base_media_cost.required'),
            'media_cost.required'                   =>  trans('flight::validate.media_cost.required')
        );
    }

    /**
     *     Filter in list
     *     @param  object $query      
     *     @param  array  $searchData 
     *     @return object
     */
    public function scopeSearch($query, $searchData = array())
    {
        if( !empty($searchData) ){
            foreach ($searchData as $search) {
                if( $search['value'] != '' ){
                    switch ($search['name']) {
                        case 'name':
                            $query->where($search['name'], 'LIKE', "%{$search['value']}%");
                            break;
                        case 'publisher_site_id':
                            $valueSite = $search['value'];
                            $query->whereHas('flightWebsite', function($q) use ($valueSite){
                            $q->where('website_id', $valueSite);
                        });
                            break;

                        default:
                            $query->where($search['name'], $search['value']);
                            break;
                    }
                }
            }
        }
        return $query;
    }    

    function getsite() {
        return $this->belongsTo('SitePublisherModel', 'publisher_site_id');
    }

    /**
     *     Relation : flight - category : 1-n
     */
    public function category() {
        return $this->hasOne('CategoryBaseModel', 'id');
    }

    /**
     *     Relation : flight - category : 1-n
     */
    public function ad() {
        return $this->hasOne('AdBaseModel', 'id', 'ad_id');
    }

    /**
     *     Relation : flight - campaign : 1-n
     */
    public function campaign() {
        return $this->belongsTo('CampaignBaseModel');
    }

    public function adzone() {
        return $this->belongsTo('PublisherAdZoneBaseModel', 'publisher_ad_zone_id');
    }

    /**
     *     Relation : flight - publisher_site : 1-n
     */
    public function publisherSite() {
        return $this->hasOne('PublisherSiteBaseModel', 'id', 'publisher_site_id');
    }

    /**
     *     Relation : flight - publisher_ad_zone : 1-n
     */
    public function publisher_ad_zone() {
        return $this->hasOne('PublisherAdZoneBaseModel', 'id', 'publisher_ad_zone_id');
    }

    public function flightDate() {
        return $this->hasMany('FlightDateBaseModel', 'flight_id');
    }

    public function flightWebsite() {
        return $this->hasMany('FlightWebsiteBaseModel', 'flight_id');
    }

    public function getListByCampaign($campaignId) {
        return $this->where('campaign_id', $campaignId)->get();
    }

    public function getFormByCampaign($campaignId, $value = FALSE, $name = FALSE) {
        $list = $this->getListByCampaign($campaignId)
                ->lists('name', 'id');

        if ($value != FALSE && $name != FALSE) {
            $list = array('' => 'Select Flight') + $list;
        }
        return $list;
    }

    public function getLishtFlightByCampaign($campaignId){
        return $this->where('campaign_id', $campaignId)->lists('id');
    }

    public function getByRangeId($range) {
        if (!empty($range)) {
            return $this->whereIn('id', $range)->get();
        } else {
            return FALSE;
        }
    }

    public function getDate() {
        return $this->hasMany('FlightDateBaseModel', 'flight_id');
    }

    public function searchByCapital($keyword) {
        $query = $this;
        if( !empty($keyword) ){
           $query = $this->where('name', 'LIKE' ,"{$keyword}");
        }
        return $query->orderBy('name','asc')->get();
    }
    
    // -- Start - Phuong-VM - Add - 14-04-2015
    public static function getCurrentDayRun($dateRange, $flightInventoryPerDay){
        $inventoryCurrent = 0;
        $inventoryExp = 0;
        
        if( !empty($dateRange) ){
            foreach( $dateRange as $date ){
                $dayCurrent = 0;
                //Get thoi gian hien tai
                $timeToday = strtotime(date('Y-m-d'));
                //Get gio hien tai
                $nowTime = intvalFromTimeText(date('H:i'));
                //Chuyen ngay bat dau, ngay ket thuc chay flight sang time
                $timeStart = strtotime($date->start);
                $timeEnd   = strtotime($date->end);
                
                $dateToday = \Carbon\Carbon::createFromTimeStamp($timeToday);
                $dateStart = \Carbon\Carbon::createFromTimeStamp($timeStart);
                $dateEnd   = \Carbon\Carbon::createFromTimeStamp($timeEnd);
                
                // Truong hop hop data khong setting daily inventory thi tinh daily inventory trung binh moi ngay phai chay
                $daily_inventory = $date->daily_inventory > 0 ? $date->daily_inventory : $flightInventoryPerDay;
                
                // Ngay hien tai trong khoang thoi gian chay flight
                if( $timeToday >= $timeStart && $timeToday <= $timeEnd ){
                    // date inventory = 0 -> unlimit
                    if($date->daily_inventory == 0){
                        return false;
                    }

                    
                    $dayCurrent       = $dateToday->diffInDays($dateStart);
                    $inventoryExp     += ($dayCurrent * $daily_inventory);
                    if (!empty($date->hour)) { 
                        foreach ($date->hour as $hour) {
                            $start          = $hour->start ? intvalFromTimeText($hour->start) : 0;
                            $end            = $hour->end ? intvalFromTimeText($hour->end) : 0;
                            $time_inventory = $hour->time_inventory ? $hour->time_inventory : $daily_inventory;
                            if ( $start <= $nowTime && ($end == 0 || $end >= $nowTime)) {
                                $inventoryCurrent = $time_inventory;
                                break;
                            } else if ($end != 0 && $end < $nowTime) {
                                if ($hour->time_inventory) {
                                    $inventoryExp += $time_inventory;
                                }
                            }

                        }
                    } else {
                        $inventoryCurrent = $daily_inventory;
                    }
                    
                    break;
                }else {
                    $dayCurrent = $date->diff;
                    $inventoryExp += ($dayCurrent * $daily_inventory);
                }
            }
        }
        
        $result['inventory_current'] = $inventoryCurrent;        
        $result['inventory_exp']     = $inventoryExp; 
        
        return $result;
    }   	
	// -- End - Phuong-VM - Add - 14-04-2015
    
    function getStatusInventory($data){
        $endadate           = strtotime($data->end_date);
        $startdate          = strtotime($data->start_date);
        $now                = strtotime(date('Y-m-d',time()));
        $Delivery           = new Delivery;
        $statusInventory    = 'play';
        $statusInventoryMsg = "Running";
        $event              = Tracking::getTrackingEventType($this->cost_type);

        if($startdate <= $now && $endadate >= $now){
            $status =  $Delivery->checkInventory($this,0,$event,json_decode($this->date));
            if( (string)$status  == 'inventory_limited' || (string)$status  == 'ad_zone_daily_inventory_limited'){
                $statusInventory    = 'stop';
                $statusInventoryMsg = $status;
            }
        }else if($startdate > $now){
            $statusInventory    = 'stop';
            $statusInventoryMsg = "Campaignnotstart";
        }else{
            $statusInventory    = 'stop';
            $statusInventoryMsg = "End_Campaign";
        }
        $statusI['status'] = $statusInventory;
        $statusI['msg']    = 'text.'. $statusInventoryMsg ;
        return $statusI;
    }
    function getProcess(){
        $TrackingInventory      = new TrackingInventory;
        $event                  = Tracking::getTrackingEventType($this->cost_type);
        $flightInventoryAllTime = $TrackingInventory->getTotalInventory($this->id, $event);
        $rate                   = $this->cost_type == 'cpm' ? 1000 : 1;
        $flightInventory        = $this->total_inventory * $rate;
        return  round(($flightInventoryAllTime / $flightInventory)*100,0);
    }

    public function getTime() {
        return json_decode($this->hour,true);
    }
    
    function getProcessPreview($totalImpression){
        $process = 0;
        $TrackingInventory      = new TrackingInventory;
        if (isset($this->event) && $this->event != '') {
            $event = $this->event;
        } else {
            $event = Tracking::getTrackingEventType($this->cost_type);
        }
        
        $rate                   = $event == 'impression' ? 1000 : 1;
        $flightInventory        = $this->total_inventory * $rate;
        if ($flightInventory > 0) {
            $process = round(($totalImpression / $flightInventory)*100, 2);
        }
        return  $process;
    }
}
