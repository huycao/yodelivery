<?php namespace App\Http\Controllers;
use App\Models\CountryBaseModel;
use App\Models\CategoryBaseModel;
use App\Models\LanguageBaseModel;
use App\Models\PublisherModel;

class HomeController extends Controller
{

	public function __construct(){
		parent::__construct(pathinfo(dirname(__DIR__), PATHINFO_BASENAME));
	}

	public function index(){
		$slugMenu = 'home';		
		return view('home.homepage', compact('slugMenu'));
	}

	public function publisher(){
		$slugMenu = 'publisher';		
		return view('home.publisherPage', compact('slugMenu'));
	}

	public function advertiser(){
		$slugMenu = 'advertiser';		
		return view('home.advertiserPage', compact('slugMenu'));
	}

	public function aboutUs(){
		$slugMenu = 'about-us';		
		return view('home.aboutUsPage', compact('slugMenu'));
	}

	public function contactUs(){
		$slugMenu = 'contact-us';		
		return view('home.contactUsPage', compact('slugMenu'));
	}

	public function contactInfo(){
        $countryModel = new CountryBaseModel;
        $data['listCountry']	= $countryModel->getAllForm();
        //pr($this->data['listCountry']); die;

        $data['listWebsiteCategory'] 	= CategoryBaseModel::where('status', 1)->where('parent_id', 0)->where('name', '!=', 'Other')->orderBy('name','asc')->get()->toArray();
        $data['listLanguage'] 		= LanguageBaseModel::where('status', 1)->orderBy('name','asc')->get()->toArray();
        //$this->data['listReason']			= $this->model->reason;
        if(\Request::isMethod('post')){
            $this->postContactInfo($data);
        }
        
        $data['slugMenu'] = '';		
		return view('home.contactInfoPage', $data);
	}
    public function postContactInfo(&$data){
        $this->model = new PublisherModel;
        $Rules = $this->model->getContactInfoRules();
        $Langs = $this->model->getContactInfoLangs();

        $validate 		= \Validator::make(\Input::all(), $Rules, $Langs);

        if( $validate->passes() ){

            $insertData = array(
                'first_name'		=>	\Input::get('first_name'),
                'last_name'			=>	\Input::get('last_name'),
                'title'				=>	\Input::get('title'),
                'company'			=>	\Input::get('company'),
                'address'			=>	\Input::get('address'),
                'city'				=>	\Input::get('city'),
                'state'				=>	\Input::get('state'),
                'postcode'			=>	\Input::get('postcode'),
                'country_id'	    =>	\Input::get('country'),
                'phone'				=>	\Input::get('phone'),
                'fax'				=>	\Input::get('fax'),
                'email'				=>	\Input::get('email'),
                'payment_to'		=>	\Input::get('payment_to'),
                'site_name'			=>	\Input::get('site_name'),
                'site_url'			=>	\Input::get('site_url'),
                'site_description'	=>	\Input::get('site_description'),
                'status'			=>	0
            );

            // insert new publisher pending
            if( $publisher = $this->model->create($insertData) ){
                \Session::flash('success', 'Thank you! Your application has been submitted! Note All applications will be responded to within 5 working days from the date of submission');
                return redirect(\URL::to(Route('HomePage')));
            }

        }else{
            $data['validate'] = $validate->messages();
            return redirect('form')->withInput();
        }
        return redirect('form')->withInput();

    }
    
    public function demoVast(){
		return \View::make('home.runVast');
	}

	public function demoPopup(){
		return \View::make('home.runPopup');
	}

	public function demoBalloon(){
		return \View::make('home.runBalloon');
	}

	public function demoTVC(){
		return \View::make('home.tvc');
	}

	public function demoPauseVast(){
		$size = \Input::get('size');
		$data = ['w'=>640,'h'=>480];
		if($size){
			$sizeArr = explode('x', $size);
			$data['w'] = !empty($sizeArr[0]) ? $sizeArr[0] : 640;
			$data['h'] = !empty($sizeArr[1]) ? $sizeArr[1] : 480;
		}
		
		$body = \View::make('home.pauseVast', $data);
		return response($body, 200)
              ->header('Content-Type', "application/xml; charset=UTF-8");
	}
    public function demoSidekick(){
        return \View::make('home.runSidekick');
    }
}