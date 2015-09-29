<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class PublisherModel extends PublisherBaseModel {

    protected $table = 'publisher';

    /**
     *     Module
     *     @var string
     */
    public $module = 'publisher';

    /**
     *     Fillable field of table
     *     @var array
     */
    protected $fillable = array(
        'first_name',
        'last_name',
        'title',
        'company',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'phone',
        'fax',
        'email',
        'payment_to',
        'site_name',
        'site_url',
        'site_description',
        'languages',
        'orther_lang',
        'unique_visitor',
        'pageview',
        'traffic_report_file',
        'category',
        'other_category',
        'reason',
        'status'

    );          

	public function getRegisterRules(){
        return array(
            "first_name"            =>  "required",
            "last_name"             =>  "required",
            "title"                 =>  "required",
            "company"               =>  "required",
            "address"               =>  "required",
            "city"                  =>  "required",
            "state"                 =>  "required",
            "postcode"              =>  "required",
            "country"               =>  "required",
            "phone"                 =>  "required",
            "fax"                   =>  "required",
            "email"                 =>  "required|email",
            "payment_to"            =>  "required",
            "site_name"             =>  "required",
            "site_url"              =>  "required|url",
            "site_description"      =>  "required",
            "languages"             =>  "required",
            "unique_visitor"        =>  "required",
            "pageview"              =>  "required",
            'traffic_report_file'   =>  "required|mimes:jpeg,gif,png,jpg,pdf",
            "category"              =>  "required",
            "agree"                 =>  "required",
        );
    }

    public function getRegisterLangs(){
        return array(
            "first_name.required"	=>  trans("first_name.required"),
            "last_name.required"	=>  trans("message.last_name.required"),
            "title.required"		=>  trans("message.title.required"),
            "company.required"		=>  trans("message.company.required"),
            "address.required"		=>  trans("message.address.required"),
            "city.required"			=>  trans("message.city.required"),
            "state.required"        =>  trans("message.state.required"),
            "postcode.required"		=>  trans("message.postcode.required"),
            "country.required"		=>  trans("message.country.required"),
            "phone.required"		=>  trans("message.phone.required"),
            "fax.required"			=>  trans("message.fax.required"),
            "email.required"        =>  trans("message.email.required"),
            "email.email"		 =>  trans("message.email.email"),
            "payment_to.required"	=>  trans("message.payment_to.required"),
            "site_name.required"         =>  trans("message.site_name.required"),
            "site_url.required"          =>  trans("message.site_url.required"),
            "site_url.url"          =>  trans("message.site_url.url"),
            "site_description.required"  =>  trans("message.site_description.required"),
            "languages.required"         =>  trans("message.languages.required"),
            "languages.between"          =>  trans("message.languages.between"),
            "unique_visitor.required"    =>  trans("message.unique_visitor.required"),
            "pageview.required"          =>  trans("message.pageview.required"),
            "traffic_report_file.required"  =>  trans("message.traffic_report_file.required"),
            "traffic_report_file.mimes"  =>  trans("message.traffic_report_file.mimes"),
            "category.required"          =>  trans("message.category.required"),
            "agree.required"             =>  trans("message.agree.required"),
        );
    }

    public function getContactInfoRules(){
        return array(
            "first_name"            =>  "required",
            "last_name"             =>  "required",
            "title"                 =>  "required",
            "company"               =>  "required",
            "address"               =>  "required",
            "city"                  =>  "required",
            "state"                 =>  "required",
            "postcode"              =>  "required",
            "country"               =>  "required",
            "phone"                 =>  "required",
            "fax"                   =>  "required",
            "email"                 =>  "required|email",
            "payment_to"            =>  "required",
            "site_name"             =>  "required",
            "site_url"              =>  "required|url",
            "site_description"      =>  "required",
            "agree"                 =>  "required",
        );
    }

    public function getContactInfoLangs(){
        return array(
            "first_name.required"   =>  trans("message.first_name.required"),
            "last_name.required"    =>  trans("message.last_name.required"),
            "title.required"        =>  trans("message.title.required"),
            "company.required"      =>  trans("message.company.required"),
            "address.required"      =>  trans("message.address.required"),
            "city.required"         =>  trans("message.city.required"),
            "state.required"        =>  trans("message.state.required"),
            "postcode.required"     =>  trans("message.postcode.required"),
            "country.required"      =>  trans("message.country.required"),
            "phone.required"        =>  trans("message.phone.required"),
            "fax.required"          =>  trans("message.fax.required"),
            "email.required"        =>  trans("message.email.required"),
            "email.email"           =>  trans("message.email.email"),
            "payment_to.required"   =>  trans("message.payment_to.required"),
            "site_name.required"    =>  trans("message.site_name.required"),
            "site_url.required"     =>  trans("message.site_url.required"),
            "site_url.url"          =>  trans("message.site_url.url"),
            "site_description.required"  =>  trans("message.site_description.required"),           
            "agree.required"        =>  trans("message.agree.required"),
            "captcha.required"      =>  "Code is required",
            "captcha.captcha"       =>  "Code is incorrect",
        );
    }

}
