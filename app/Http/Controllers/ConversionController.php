<?php namespace App\Http\Controllers;
use App\Models\Conversion;
use App\Models\RawTrackingConversion;

class ConversionController extends Controller
{

	public function __construct(){
		parent::__construct(pathinfo(dirname(__DIR__), PATHINFO_BASENAME));
	}

    public function trackingConversion() {
        $responseType = '';
        $response = NULL;
        $conversionID = \Input::get('cid', 0);
        $param = \Input::get('param', '');
        
        $conversionModel = new Conversion;
        $rawTrackingConversionModel = new RawTrackingConversion;
        if ($conversionID) {
            $objConversion = $conversionModel->getConversion($conversionID);
            if ($objConversion) {
                $param = urldecode($param);
                if ($rawTrackingConversionModel->addConversion($conversionID, $objConversion->campaign_id, $param)) {
                    $responseType = Conversion::RESPONSE_TYPE_CONVERSION_SUCCESS;
                } else {
                    $responseType = Conversion::RESPONSE_TYPE_CONVERSION_ERROR;
                }
            } else {
                $responseType = Conversion::RESPONSE_TYPE_CONVERSION_NOT_FOUND;
            }
        } else {
            $responseType = Conversion::RESPONSE_TYPE_CONVERSION_NOT_INVALID;
        }
        pr($responseType);
    }
}