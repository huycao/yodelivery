<?php namespace App\Models;

class RawTrackingAudience{

    protected $table      = 'tracking_audience';
    protected $connection = 'mongodb';
    public $timestamps    = false;
   
    public function addAudience($uuid, $audience_id, $bid, $time, $event){
        $info = [
            '_id' => $uuid . $time,
            'uuid'=> $uuid,
            'time'=> intval($time)
        ];

        $where = [
            '_id'   =>  $uuid . $time            
        ];
        $table = "{$this->table}_{$bid}_{$audience_id}";

        return $this->incrementUpsert($table, $event, 1, $where, $info);
    }

    /**
     * increment record's column value if exists or insert new record.
     *
     * @param  array  $values
     * @param  array  $options
     * @return int
     */
    public function incrementUpsert($table, $column, $amount = 1, array $where = array(), array $extra = array())
    {   
        $db = \DB::connection('mongodb')->collection($table);
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
