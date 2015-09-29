<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Carbon\Carbon;

class PublisherBaseModel extends Eloquent {

    protected $table = 'publisher';

    protected $appends = array('statusText');

    public $reason = array(
        'advaluead' => "Yomedia Ad",
        'mail'      => "Yomedia Invitation Mail",
        'search'    => "Search Results",
        'friend'    => "Recommended by Friends"
    );

    const STATUS_PENDING        = 0;
    const STATUS_ARCHIVED       = 1;
    const STATUS_DISAPPROVED    = 2;
    const STATUS_APPROVED       = 3;

    public function getStatusTextAttribute(){

        $text = "";
        switch ($this->status) {
            case self::STATUS_PENDING:
                $text   = trans("text.pending");
                $class  = "label-warning";
                break;
            case self::STATUS_ARCHIVED:
                $text = trans("text.archived");
                $class  = "label-default";
                break;
            case self::STATUS_DISAPPROVED:
                $text = trans("text.disapproved");
                $class  = "label-danger";
                break;
            case self::STATUS_APPROVED:
                $text = trans("text.approved");
                $class  = "label-success";
                break;
        }

        return '<span class="label '.$class.'">'.$text.'</span>';

    }

    public function getUniqueVisitorFormatAttribute(){
        return '<span class="badge badge-info">'.number_format($this->unique_visitor).'</span>';
    }


    public function getUpdateRules(){
        return array(
            "site-name"         =>  "required|min:5",
            "site-url"          =>  "required|min:5",
            "unique-visitor"    =>  "required",

            "page-view"         =>  "required",
            "selected_site-channel"      =>  "required",
            "selected_serve-country"     =>  "required",
        );
    }

    public function getUpdateLangs(){
        return array(
            "site-name.required"         =>  trans("backend::publisher/validation.site-name.required"),
            "site-name.min"              =>  trans("backend::publisher/validation.site-name.min"),
            "site-url.required"          =>  trans("backend::publisher/validation.site-url.required"),
            "site-url.min"               =>  trans("backend::publisher/validation.site-url.min"),
            "site-description.required"  =>  trans("backend::publisher/validation.site-description.required"),
            "site-category.required"     =>  trans("backend::publisher/validation.site-catogory.required"),
            "unique-visitor.required"    =>  trans("backend::publisher/validation.unique-visitor.required"),

            "page-view.required"         =>  trans("backend::publisher/validation.page-view.required"),
           // "daily-view.required"        =>  trans("backend::publisher/validation.daily-view.required"),
            "payment-to.required"        =>  trans("backend::publisher/validation.payment-to.required"),
            "company.required"           =>  trans("backend::publisher/validation.company.required"),
            "company.min"                =>  trans("backend::publisher/validation.company.min"),
            "title.required"             =>  trans("backend::publisher/validation.title.required"),
            "f-name.required"            =>  trans("backend::publisher/validation.f-name.required"),
            "f-name.min"                 =>  trans("backend::publisher/validation.f-name.min"),
            "l-name.required"            =>  trans("backend::publisher/validation.l-name.required"),
            "l-name.min"                 =>  trans("backend::publisher/validation.l-name.min"),
            "address.required"           =>  trans("backend::publisher/validation.address.required"),
            "city.required"              =>  trans("backend::publisher/validation.city.required"),
            "city.min"                   =>  trans("backend::publisher/validation.city.min"),
            "state.required"             =>  trans("backend::publisher/validation.state.required"),
            "postcode.required"          =>  trans("backend::publisher/validation.postcode.required"),
            "selected_site-channel.required"      =>  trans("backend::publisher/validation.site-channel.required"),
            "selected_serve-country.required"     =>  trans("backend::publisher/validation.serve-country.required"),

            "company-name-e.required"              =>  trans("backend::publisher/validation.company-name.required"),
            "tax.required"                       =>  trans("backend::publisher/validation.tax.required"),
            "management-free.required"           =>  trans("backend::publisher/validation.management-free.required"),
            "split-billing.required"             =>  trans("backend::publisher/validation.split-billing.required"),
            "primium-publisher.required"         =>  trans("backend::publisher/validation.primium-publisher.required"),
            "domain-checking.required"           =>  trans("backend::publisher/validation.domain-checking.required"),
            "vast-tag.required"                  =>  trans("backend::publisher/validation.vast-tag.required"),
            "network-publisher.required"         =>  trans("backend::publisher/validation.network-publisher.required"),
            "mobile-ad.required"                 =>  trans("backend::publisher/validation.mobile-ad.required"),
            "access-to-all-channels.required"    =>  trans("backend::publisher/validation.access-to-all-channels.required"),
            "newsletter.required"                =>  trans("backend::publisher/validation.newsletter.required"),
            "enable-report-by-model.required"    =>  trans("backend::publisher/validation.enable-report-by-model.required"),
        );
       
    }

    public function getUpdateProfileRules(){
        return array(
            "password"         =>  "required|min:5",
            "c-password"       =>  "required|min:5|same:password",
            "f-name"           =>  "required|min:3",
            "l-name"           =>  "required|min:3",
            "email"            =>  "required|email|min:3"
        );
    }

    public function getUpdateProfileLangs(){
        return array(
            "password.required"         =>  trans("backend::publisher/validation.password.required"),
            "password.min"              =>  trans("backend::publisher/validation.password.min"),
            "c-password.required"       =>  trans("backend::publisher/validation.c-password.required"),
            "c-password.min"            =>  trans("backend::publisher/validation.c-password.min"),
            "c-password.same"           =>  trans("backend::publisher/validation.c-password.same"),
            "f-name.required"           =>  trans("backend::publisher/validation.f-name.required"),
            "f-name.min"                =>  trans("backend::publisher/validation.f-name.min"),
            "l-name.required"           =>  trans("backend::publisher/validation.l-name.required"),
            "l-name.min"                =>  trans("backend::publisher/validation.l-name.min"),
            "email.required"            =>  trans("backend::publisher/validation.email.required"),
            "email.email"               =>  trans("backend::publisher/validation.email.email"),
            "email.min"                 =>  trans("backend::publisher/validation.email.min"),
        );   
    }

    public function getShowField(){        
        return array(
            'company'         =>  array(
                'label'         =>  trans("backend::publisher/text.company"),
                'type'          =>  'text'
            ),
            'site_url'         =>  array(
                'label'         =>  trans("backend::publisher/text.site_url"),
                'type'          =>  'text'
            ),            
            'pageview'         =>  array(
                'label'         =>  trans("backend::publisher/text.pageview"),
                'type'          =>  'text'
            ),
            'unique_visitor'  =>  array(
                'label'         =>  trans("backend::publisher/text.unique_visitor"),
                'type'          =>  'text',
                'alias'         => 'uniqueVisitorFormat'
            ),            
            'email'  =>  array(
                'label'         =>  trans("backend::publisher/text.email"),
                'type'          =>  'text',
                'alias'         => 'email'
            ),
            'status'  =>  array(
                'label'         =>  trans("backend::publisher/text.status"),
                'type'          =>  'text',
                'alias'         => 'statusText'
            ),
            'created_at'    =>  array(
                'label'         =>  trans("backend::publisher/text.date_registered"),
                'type'          =>  'text'
            )
        );   
    }

    public function user(){
        return $this->belongsTo('User');        
    }

    public function payment() {
        return $this->belongsTo('PublisherInfoPaymentBaseModel','id','publisher_id');
    }

    /**
     *     Relation : publisher - country
     */
    public function country(){
        return $this->belongsTo('CountryBaseModel','country_id', 'id');
    }

    /**
     *     Relation : publisher -  Server country : n-n
     */
    public function serveCountry(){
        return $this->belongsToMany('CountryBaseModel', 'publisher_country', 'publisher_id', 'country_id')->withTimestamps();
    }

    /**
     *     Relation : publisher - channel : n-n
     */
    public function channel(){
        return $this->belongsToMany('CategoryBaseModel', 'publisher_channel', 'publisher_id', 'channel_id')->withTimestamps();
    }

    /**
     *     Relation : publisher - language : n-n
     */
    public function language(){
        return $this->belongsToMany('LanguageBaseModel', 'publisher_language', 'publisher_id', 'language_id')->withTimestamps();
    }

    /**
     *     Relation : publisher - language : n-n
     */
    public function publisherSite(){
        return $this->hasMany('PublisherSiteBaseModel', 'publisher_id');
    }



    public function insertData($userId){
        $this->created_by = $userId;
        $this->storeData($userId);
        $this->updateUser();

        $websiteModel = new PublisherSiteBaseModel();
        $status = ($this->status == 3) ? 1 : 0;
        $websiteData = array(
            'name'  =>  $this->site_name,
            'url'   =>  $this->site_url,
            'premium_publisher' =>  $this->premium_publisher,
            'domain_checking'   =>  $this->domain_checking,
            'vast_tag'  =>  $this->vast_tag,
            'network_publisher' =>  $this->network_publisher,
            'mobile_ad' =>  $this->mobile_ad,
            'status'    =>  $status
        );

        $websiteModel->insertData($userId, $this->id, $websiteData);

        return $this;
    }

    public function updateData($userId){
        $this->storeData($userId);
        $this->updateUser();
        $websiteModel = new PublisherSiteBaseModel();
        $status = ($this->status == 3) ? 1 : 0;
        $websiteModel->updateAllStatus($this->id, $status);
        return $this;
    }

    public function updateUser(){

        if( $this->user_id == 0 ){
            // create user
            if( Input::get('username') && Input::get('password') ){


                $user = Sentry::createUser(array(
                    'username'     => trim(Input::get('username')),
                    'password'  => trim(Input::get('password')),
                    'show_password'  => trim(Input::get('password')),
                    'activated'     =>  1
                ));

                $publisherGroup = Sentry::findGroupById(Config::get('backend.group_publisher_id'));
                if($publisherGroup){
                    $permissions = $publisherGroup->getPermissions();
                    $user->addGroup($publisherGroup);
                    $user->permissions = $permissions;
                    $user->save();
                }

                $this->user_id = $user->id;
                $this->save();
            }
        }else{
            // update user
            $user = Sentry::findUserById($this->user_id);

            if( Input::get('password') && Input::get('password') != $user->show_password ){
                $user->password         = Input::get('password');
                $user->show_password    = Input::get('password');
                $user->save();
            }

        }

    }

    public function storeData($userId){

        $this->site_name = trim(Input::get('site-name'));
        $this->site_url = trim(Input::get('site-url'));
        $this->site_description = trim(Input::get('site-description'));
        $this->pageview = trim(Input::get('page-view'));
        $this->unique_visitor = trim(Input::get('unique-visitor'));
        $this->first_name = trim(Input::get('f-name'));
        $this->last_name = trim(Input::get('l-name'));
        $this->title = trim(Input::get('title'));
        $this->company = trim(Input::get('company'));
        $this->address = trim(Input::get('address'));
        $this->state = trim(Input::get('state'));
        $this->postcode = trim(Input::get('postcode'));
        $this->country_id = trim(Input::get('country'));
        $this->payment_to = trim(Input::get('payment-to'));
        $this->phone = trim(Input::get('phone'));
        $this->email = trim(Input::get('email-address'));
        $this->fax = trim(Input::get('fax'));
        $this->billing_company_name = trim(Input::get('billing_company_name'));
        $this->billing_company_address = trim(Input::get('billing_company_address'));
        $this->billing_tax_id = trim(Input::get('billing_tax_id'));
        $this->billing_revenue_sharing = trim(Input::get('billing_revenue_sharing'));
        $this->name_contact = trim(Input::get('name_contact'));
        $this->email_contact = trim(Input::get('email_contact'));
        $this->phone_contact = trim(Input::get('phone_contact'));
        $this->address_contact = trim(Input::get('address_contact'));
        $this->other_lang = trim(Input::get('orther-lang'));
        $this->city = Input::get('city');
        $this->premium_publisher = Input::get('premium-publisher');
        $this->domain_checking = Input::get('domain-checking');
        $this->vast_tag = Input::get('vast-tag');
        $this->network_publisher = Input::get('network-publisher');
        $this->mobile_ad = Input::get('mobile-ad');
        $this->access_to_all_channel = Input::get('access-to-all-channels');
        $this->newsletter = Input::get('newsletter');
        $this->enable_report_by_model = Input::get('enable-report-by-model');
        $this->status = Input::get('status');
        $this->updated_by = $userId;

        if( $this->save() ){
            return $this->storeDataRelation();
        }
        return FALSE;

    }

    public function storeDataRelation(){

        if( Input::get('selected_serve-country') && count(Input::get('selected_serve-country')) ){
            $this->serveCountry()->attach(Input::get('selected_serve-country'));       
        }

        if( Input::get('selected_site-channel') && count(Input::get('selected_site-channel')) ){
            $this->channel()->attach(Input::get('selected_site-channel'));
        }

        if( Input::get('selected_site-language') && count(Input::get('selected_site-language')) ){
            $this->language()->attach(Input::get('selected_site-language'));
        }

        return $this;
    }



    public function getSearchField(){
        return array(
            'title'         =>  trans("text.name")
        );
    }

    public function scopeSearch($query, $searchData = array(),$status='')
    {

        if( !empty($searchData) ){

            $idCountry  =$searchData[0]['value'];
            $idCate     =$searchData[1]['value'];
            $typeName   =$searchData[2]['value'];
            $keyword    =$searchData[3]['value'];
            $status     =$searchData[4]['value'];

            if(!empty($idCountry))
                $query->where('country', $idCountry);

            if(!empty($idCate))
                $query->where('category', $idCate);
            
            if(isset($status) && $status !=-1){
                $query->where('status', $status);               
            }

            if(!empty($keyword)){
                if($typeName=="company"){
                    $query->where("company","LIKE",DB::raw("'%{$keyword}%'"));
                }else
                    $query->where('email', 'LIKE', DB::raw("'%{$keyword}%'"));
            }
            
        }else{
            if($status==Config::get('backend.publisher_approved')){
                $query=$query->where('status',Config::get('backend.publisher_approved'));
            }else
                $query->where('status','0');
        }      
        
        return $query;
    }

    public function scopeSearchAd($query, $searchData = array())
    {
        if( !empty($searchData) ){
            foreach ($searchData as $search) {
                if( $search['value'] != '' ){
                    switch ($search['name']) {
                        case 'search[\'keyword\']':
                            break;

                        case 'search[\'field\']':
                            if( $search['value'] == 'username' ){
                                $username = $searchData['search[\'keyword\']']['value'];
                                $query->whereHas
                                ('user', function($q) use ($username){
                                    $q->where('username', 'LIKE', "%${username}%");
                                });
                            }else{
                                $query->where($search['value'], 'LIKE', "%{$searchData['search[\'keyword\']']['value']}%");
                            }
                            break;

                        case 'name':
                            $query->where($search['name'], 'LIKE', "%{$search['value']}%");
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
    
    public function getCategoryByIdAttribute(){
        $modelCate=new CategoryBaseModel;      
        $item=$modelCate->where('id',$this->category)->select('id','name')->first();
        if(!empty($item)) return $item->name;
        else return FALSE;
    }

    public function scopeGetListByDate($query,$dateStart,$dateEnd){
        $item=$query->whereBetween('updated_at',[$dateEnd,$dateStart])->get();
        return $item;
    }

    public function relationCategory(){
        return $this->belongsTo('CategoryBaseModel','category');
    }

    public function searchByCapital($keyword) {

        $query = $this;
        if( !empty($keyword) ){
            $query = $this->where('company', 'LIKE' ,"{$keyword}");
        }
        return $query->where('status', 3)->get();
    }

    public function getPublisherHost($publisherId){
        $rs = $this->select('site_url')->where('id', $publisherId)->first();
        if($rs){
            return parse_url($rs->site_url, PHP_URL_HOST);
        }else{
            return false;
        }
    }

    public function createMonthlyPaymentRequest(){

        $startDate = Carbon::now()->firstOfMonth()->subMonth();
        $endDate   = Carbon::now()->firstOfMonth()->subMonth()->lastOfMonth();
        $date      = $startDate->format('Y-m');

        $check = PaymentRequestBaseModel::where(array(
            'publisher_id'  =>  $this->id,
            'date'          =>  $date
        ))->first();

        $websiteLists = $this->publisherSite->lists('id');

        if( empty($check) ){
            $tracking         = new TrackingSummaryBaseModel;
            $data             = $tracking->sumEarnPerCampaign($websiteLists, $startDate, $endDate);
            $campaigns        = $data['campaign'];
            $total            = $data['total'];
            
            $pr               = new PaymentRequestBaseModel;
            $pr->publisher_id = $this->id;
            $pr->date         = $date;
            $pr->amount       = $total;
            $pr->save();

            if( !empty($campaigns) ){

                foreach( $campaigns as $campaignID => $campaign ){

                    $prd               = new PaymentRequestDetailBaseModel;
                    $prd->publisher_id = $this->id;
                    $prd->campaign_id  = $campaignID;
                    $prd->amount       = $campaign['cost'];
                    $prd->impression   = $campaign['impression'];
                    $prd->click        = $campaign['click'];
                    if( $campaign['click'] != 0 && $campaign['impression'] != 0 ){
                        $prd->ctr = $campaign['click'] / $campaign['impression'];
                    }else{
                        $prd->ctr = 0;   
                    }
                    $prd->date               = $date;
                    $prd->payment_request_id = $pr->id;
                    $prd->save();

                }

            }


        }


    }



}
