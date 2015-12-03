<?php namespace App\Models;
use Jenssegers\Mongodb\Model as Moloquent;

class RawTrackingAudience extends Moloquent {

    protected $table      = 'tracking_audience';
    protected $connection = 'mongodb';
    public $timestamps    = false;


    public function addAudience($uuid, $bid, $event) {
        $data = [
            'uuid' => $uuid,
            'bid'  => intval($bid),
            'time' => intval(time())
        ];

        return $this->incrementUpsert($event, 1, $data, $data);
    }

    public function incrementUpsert($column, $amount = 1, array $where = array(), array $extra = array())
    {   
        $db = \DB::connection('mongodb')->collection($this->table);
        $db1 = \DB::connection('mongodb')->collection($this->table);
        $tmp = $extra;
        if (isset($tmp['time'])) {
            unset($tmp['time']);
        }
        unset($where['time']);
        $a = $db1->where($tmp)->where('time', 'exists', true)->get();        
        if (!empty($a)) {
            unset($extra['time']);
        }   

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
}
