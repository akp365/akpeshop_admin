<?php

namespace App\Http\Controllers;

use App\Models\Models\FooterSetting;
use App\Models\Models\Page;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index( Request $request ){
        //FETCH CURRENT FOOTER SETTINGS 
        $footerSettings = [];
        foreach(FooterSetting::get() as $key => $data){
            $footerSettings[$data['key']] = array('label' => $data['label'], 'value' => $data['value'], 'page_id' => $data['page_id']);
        }

        //PAGES
        $pages = Page::select('page_id','title')->where('status','active')->orderBy('page_id','DESC')->get();  
                
        return view('footer_settings',compact( 'footerSettings', 'pages' ) );
    }


    public function saveFooterLink( Request $request ){
        //FETCH ITEM NUMBER
        $itemNumber = $request->input('link_num');

        //FETCH CURRENT SETTINGS FOR SUBJECTED ITEM-NUMBER
        $currentSettings = FooterSetting::where('key' , '=', 'footer_link_' . $itemNumber )->first();

        //FETCH ITEM LABEL
        $label = $request->input('label');
        if($currentSettings->label != $label) $currentSettings->label = $label;

        //FETCH ITEM VALUE
        $value = $request->input('value');
        if($currentSettings->value != $value) $currentSettings->value = $value;

        //FETCH PAGE ID
        $pageId = $request->input('page_id');
        if($currentSettings->page_id != $pageId) $currentSettings->page_id = $pageId;

        //SAVE THE CHANGES AND RETURN 1
        if( $currentSettings->save() )
        {
            return response()->json( array( 'status' => 1 ) );
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json( array( 'status' => 0 ) );
        }
    }
}
