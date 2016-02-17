<?php namespace App\Models;

class RawTrackingAdRequest{

    protected $table      = 'trackings_adrequest';
    protected $connection = 'mongodb';
    public $timestamps    = false;
   
    public function addAdRequest($wid, $zid){
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        if (!$referer){
            return false;
        }
        $id = md5(date('YmdH_') .$wid. '_'. $zid. '_' .$referer);
        $info = [
            '_id' => $id,
            'wid' => $wid,
            'zid' => $zid,
            'referer' => $referer,
            'created_d' => date('Y-m-d'),
            'created_h' => date('Y-m-d H')
        ];

        $where = [
            '_id' => $id
        ];

        return $this->incrementUpsert($this->table, 'count', 1, $where, $info);
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
        $db = \DB::connection('mongodb1')->collection($table);
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
