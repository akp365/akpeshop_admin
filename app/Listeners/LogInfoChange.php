<?php

namespace App\Listeners;

use App\Events\InfoChanged;
use App\Models\ChangeLog;
use App\Models\City;
use App\Models\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogInfoChange
{
    private $logData;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InfoChanged  $event
     * @return void
     */
    public function handle(InfoChanged $event)
    {
        $this->logData = $event->logData;
        //dd($this->logData);

        //PREPARE DESCRIPTION
        $changeLog = new ChangeLog();
        $changeLog->seller_id = $this->logData->seller_id;
        $changeLog->attribute_name = $this->attributeName();
        $changeLog->old_value = $this->getValueFromId($this->logData->old_value);
        $changeLog->new_value = $this->getValueFromId($this->logData->new_value);
        $changeLog->change_status = $this->logData->change_status;
        $changeLog->description = $this->getDescription($changeLog);
        $changeLog->save();
    }

    private function attributeName(){
        switch($this->logData->attribute_name){
            case 'city_id':
                return 'City';
            break;
            case 'phone':
                return 'Phone Number';
            break;
            case 'account_type':
                return 'Account Type';
            break;
            case 'company_name':
                return 'Company Name';
            break;
            case 'company_address':
                return 'Company Address';
            break;
            case 'new_category':
                return 'New Category';
            case 'category_change':
                return 'Category';
            break;
            case 'photo_url':
                return 'Photo';
            break;
            case 'gst_url':
                return 'GST/BIN';
            break;
            case 'trade_license_url':
                return 'Trade License';
            break;
            case 'bank_check_url':
                return 'Bank Cheque';
            break;
            case 'nid_url':
                return 'NID';
            break;
            case 'tin_certificate_url':
                return 'TIN';
            break;
            default:
                return $this->logData->attribute_name;
        }
    }

    private function getValueFromId($id){
        switch($this->logData->attribute_name){
            case 'city_id':
                return City::find($id)->city_name;
            break;
            case 'new_category':
                if($id == "NA") return "NA";
                return Category::find($id)->title;
            case 'category_change':
                return Category::find($id)->title;
            break;
            default:
                return $id ?? "NA";
        }
    }

    private function getDescription($changeLog){
        switch($this->logData->attribute_name){
            case 'new_category':
                return 'Added ' . $changeLog->attribute_name . " '" . $changeLog->new_value . "'";
            break;
            case 'photo_url': 
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ;
            break;
            case 'gst_url': 
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ;
            break;
            case 'trade_license_url': 
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ;
            break;
            case 'bank_check_url':
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ; 
            break;
            case 'nid_url':
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ; 
            break;
            case 'tin_certificate_url':
                $oldfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->old_value;
                $newfileUrl = env('AKP_STORAGE') . 'seller_attachments/' . $changeLog->new_value;
                return $changeLog->attribute_name . ' Change From <a target="_blank" href="' . $oldfileUrl . '">' . $changeLog->old_value . '</a> To <a target="_blank" href="' . $newfileUrl . '">' . $changeLog->new_value . '</a>'  ; 
            break;
            default:
                return $changeLog->attribute_name . " Change From '" . $changeLog->old_value . "' To '" . $changeLog->new_value . "'";
        }
    }
}
