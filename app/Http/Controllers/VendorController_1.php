<?php

namespace App\Http\Controllers;

use App\Events\InfoChanged;
use App\Models\CategoryChangeRequest;
use App\Models\CategoryRequest;
use App\Models\ChangeLog;
use App\Models\City;
use App\Models\Commission;
use App\Models\Country;
use App\Models\InfoChangeRequest;
use App\Models\Seller;
use App\Models\SellerCategory;
use App\Models\SellerPayable;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use stdClass;

class VendorController extends Controller
{
    public function testMail()
    {
        //SEND EMAIL
        $details = [
            'title' => 'Test',
            'body' => 'Test email from akp using zeptomail smtp.',
        ];

        \Mail::to('anas.bin.numan@gmail.com')->send(new \App\Mail\GeneralEmail($details));
    }

    //FUNCTION TO SHOW VIEW PAGE OF PENDING FOR PRE-APPROVAL
    //IF ON AJAX, SHOWS LIST OF VENDORS PENDING FOR PRE-APPROVAL
    public function pendingForPreApproval(Request $request)
    {
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) {
            //PREPARE DATA FOR DATATABLE
            $data = Seller::with('country')->whereIn('account_status', array('pre_approval_pending', 'pre_declined', 'pre_approved'))->get();

            return DataTables::of($data)
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('country_name', function ($row) {
                    return $row->country->country_name;
                })
                ->addColumn('city_name', function ($row) {
                    return $row->city->city_name;
                })
                ->addColumn('phone_with_country_code', function ($row) {
                    return $row->country->dial_code . '-' . $row->phone;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('seller-details', ['sellerId' => $row->id]);
                    if ($row->account_status == "pre_approval_pending") {
                        $btn =  '
                                    <button class="btn btn-success btn-stroke btn-circle" title="Approve" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>
                                    <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                                    <button class="btn btn-danger btn-stroke btn-circle" title="Decline" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                                ';
                    } else {
                        $btn =  '
                                    <button class="btn btn-success btn-stroke btn-circle" title="Resend Approval Email" onclick="resendPreApprovalEmail(' . $row->id . ' )"><i class="fa fa-external-link"></i></button>
                                    <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                                ';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //RENDER VIEW
        else {
            return view('vendor.pre_approval');
        }
    }

    //FUNCTION TO SHOW VIEW PAGE OF PENDING FOR FINAL-APPROVAL
    //IF ON AJAX, SHOWS LIST OF VENDORS PENDING FOR FINAL-APPROVAL
    public function pendingForFinalApproval(Request $request)
    {
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) {
            //PREPARE DATA FOR DATATABLE
            $data = Seller::with('country')->whereIn('account_status', array('final_approval_pending', 'final_declined'))->get();

            return DataTables::of($data)
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('country_name', function ($row) {
                    return $row->country->country_name;
                })
                ->addColumn('city_name', function ($row) {
                    return $row->city->city_name;
                })
                ->addColumn('phone_with_country_code', function ($row) {
                    return $row->country->dial_code . '-' . $row->phone;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('seller-details', ['sellerId' => $row->id]);

                    if ($row->account_status == "final_approval_pending") {
                        $btn = '
                                <button class="btn btn-success btn-stroke btn-circle" title="Approve" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                                <button class="btn btn-danger btn-stroke btn-circle" title="Decline" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                            ';
                    } else {
                        $btn = '
                            <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                        ';
                    }

                    return $btn;
                })
                ->rawColumns(['photo_div', 'nid_div', 'tin_div', 'gst_div', 'trd_div', 'cheque_div', 'action'])
                ->make(true);
        }
        //RENDER VIEW
        else {
            return view('vendor.final_approval');
        }
    }

    public function sellerList(Request $request)
    {
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) {
            //PREPARE DATA FOR DATATABLE
            $data = Seller::with('country')->whereIn('account_status', array('active', 'inactive'))->get();

            return DataTables::of($data)
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->member_since));
                })
                ->addColumn('country_name', function ($row) {
                    return $row->country->country_name;
                })
                ->addColumn('city_name', function ($row) {
                    return $row->city->city_name;
                })
                ->addColumn('phone_with_country_code', function ($row) {
                    return $row->country->dial_code . '-' . $row->phone;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('seller-details', ['sellerId' => $row->id]);

                    if ($row->account_status == "inactive") {
                        $btn = '
                                <button class="btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                            ';
                    } else {
                        $btn = '
                                <button class="btn btn-danger btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>
                            ';
                    }

                    return $btn;
                })
                ->rawColumns(['photo_div', 'nid_div', 'tin_div', 'gst_div', 'trd_div', 'cheque_div', 'action'])
                ->make(true);
        }
        //RENDER VIEW
        else {
            return view('vendor.seller_list');
        }
    }

    //FUNCTION TO CHANGE VENDOR STATUS
    //SEND EMAIL BASED ON UPDATED STATUS
    //GENERATES SELLER-CODE IF NEEDED
    public function changeVendorStatus(Request $request)
    {
        //COLLECT USER INPUT
        $vendorId = $request->itemId;
        $statusText = $request->status;

        //FETCH VENDOR
        $vendor = Seller::find($vendorId);

        //SET NEW STATUS
        $vendor->account_status = $statusText;

        //SAVE NEW STATUS
        $vendor->save();

        //SEND EMAIL BASED ON STATUS
        switch ($statusText) {
            case 'pre_approved':
                $this->sendPreApprovalEmail($vendor->id, $vendor->email);
                break;
            case 'pre_declined':
                $this->sendPreDeclinationEmail($vendor->email);
                break;
            case 'active':
                $vendorCode = $this->generateVendorCode($vendor);
                $this->sendFinalApprovalEmail($vendor->email);
                break;
            case 'final_declined':
                $this->sendFinalApprovalEmail($vendor->email);
                break;
            case 'inactive':
                $this->sendDeactivationEmail($vendor->email);
                break;
        }
    }

    //FUNCTION TO RESEND PRE-APPROVAL EMAIL
    public function resendPreApprovalMail(Request $request)
    {
        //COLLECT USER INPUT
        $vendorId = $request->itemId;

        //FETCH VENDOR
        $vendor = Seller::find($vendorId);

        $this->sendPreApprovalEmail($vendor->id, $vendor->email);
    }

    //MAIL FOR PRE-APPROVAL
    public function sendPreApprovalEmail($vendorId, $emailAddress)
    {

        //GENERATE VERIFICATION URL
        $verificationURL = "http://128.199.172.145/akp/public/be-a-seller-step-2/" . $vendorId;

        //SEND EMAIL
        $details = [
            'title' => 'Welcome to AKP. Complete your registration.',
            'body' => 'Please complete your final registration by clicking the link below',
            'verification_url' => $verificationURL
        ];

        \Mail::to($emailAddress)->send(new \App\Mail\WelcomeEmail($details));
    }

    //MAIL FOR PRE-DECLINATION
    public function sendPreDeclinationEmail($emailAddress)
    {

        //SEND EMAIL
        $details = [
            'title' => 'Dear vendor',
            'body' => 'We are sorry but your priliminary information does not qualify as a vendor on AKP.',
        ];

        \Mail::to($emailAddress)->send(new \App\Mail\WelcomeEmail($details));
    }

    //MAIL FOR FINAL-APPROVAL
    public function sendFinalApprovalEmail($emailAddress)
    {

        //SEND EMAIL
        $details = [
            'title' => 'Welcome onboard.',
            'body' => 'Congratulations, Your AKP vendor account is active now',
        ];

        \Mail::to($emailAddress)->send(new \App\Mail\GeneralEmail($details));
    }

    //MAIL FOR FINAL-DECLINATION
    public function sendFinalDeclinationEmail($emailAddress)
    {

        //SEND EMAIL
        $details = [
            'title' => 'Dear vendor',
            'body' => 'We are sorry but your final attachments does not qualify as a vendor on AKP.',
        ];

        \Mail::to($emailAddress)->send(new \App\Mail\GeneralEmail($details));
    }

    //MAIL FOR ACCOUNT DEACTIVATION
    public function sendDeactivationEmail($emailAddress)
    {

        //SEND EMAIL
        $details = [
            'title' => 'Dear vendor',
            'body' => 'Your account on AKP has been deactivated. Please contact admin for more info.',
        ];

        \Mail::to($emailAddress)->send(new \App\Mail\GeneralEmail($details));
    }

    //MAIL FOR FINAL-DECLINATION
    public function vendorDetails(Request $request, $sellerId)
    {
        //FETCH VENDOR/SELLER DETAILS
        $vendor = Seller::find($sellerId);

        $infoChangeRequest = InfoChangeRequest::where('seller_id', $sellerId)->first();
        //dd($infoChangeRequest);

        //COUNTRY LIST
        $countryList = Country::whereIn('country_name', array("Bangladesh"))->orderBy('country_name')->get();

        $sellerPayable = SellerPayable::latest()->first();

        // return $sellerPayable;

        //RENDER VIEW
        return view('vendor.vendor_details', compact('vendor', 'countryList', 'infoChangeRequest', 'sellerPayable'));
    }

    //FUNCTION TO UPDATE VENDOR DETAILS
    public function updateVendor(Request $request)
    {
        //VALIDATE INPUT
        $request->validate([
            'seller_id' => 'required|bail',
            'name' => 'required|max:100',
            'gender' => 'required',
            'age' => 'required',
            'account_type' => 'required',
            'shop_name' => 'required',
            'country' => 'required',
            'city' => 'required',
            'shop_address' => 'required',
            'company_name' => Rule::requiredIf($request->input('account_type') == 'business'),
            'company_address' => Rule::requiredIf($request->input('account_type') == 'business'),
            'phone' => 'required',
            'email' => 'required',
        ]);

        //GET SUBJECT SELLER DETAILS
        $sellerDetails = Seller::find($request->seller_id);

        //UPDATE EACH INFO IF NEEDED
        $sellerDetails->name = $request->name;
        $sellerDetails->gender = $request->gender;
        $sellerDetails->age = $request->age;
        $sellerDetails->account_type = $request->account_type;
        $sellerDetails->shop_name = $request->shop_name;
        $sellerDetails->country_id = $request->country;
        $sellerDetails->city_id = $request->city;
        $sellerDetails->shop_address = $request->shop_address;

        if ($request->account_type == "business") {
            $sellerDetails->company_name = $request->company_name;
            $sellerDetails->company_address = $request->company_address;
        } else {
            $sellerDetails->company_name = "";
            $sellerDetails->company_address = "";
        }

        $sellerDetails->phone = $request->phone;
        $sellerDetails->email = $request->email;

        $sellerDetails->save();

        //COLLECT SELLER CATEGORIES
        $sellerCategories = SellerCategory::where('seller_id', $request->seller_id)->get();

        //SAVE OR UPDATE COMMISSION AND PROMOTER CLUB FEE FOR EACH CATEGORY
        foreach ($sellerCategories as $key => $data) {
            //COLLECT EXISTING COMMISSIONS FOR CURRENT CATEGORY
            $commission = Commission::where('category_id', $data->category_id)->where('seller_id', $request->seller_id)->first();

            //COMMISSION DATA EXISTS
            //WILL UPDATE
            if ($commission) {
                $commission->commission_rate = $request->input('commission_rate_for_cat_' . $data->category_id) ?? 0;
                $commission->promoter_club_fee = $request->input('promoter_club_fee_for_cat_' . $data->category_id) ?? 0;
            }
            //WILL INSERT NEW COMMISSSION DATA
            else {
                $commission = new Commission();
                $commission->seller_id = $request->seller_id;
                $commission->category_id = $data->category_id;
                $commission->commission_rate = $request->input('commission_rate_for_cat_' . $data->category_id) ?? 0;
                $commission->promoter_club_fee = $request->input('promoter_club_fee_for_cat_' . $data->category_id) ?? 0;
            }

            //RESPONSE BASED ON SAVE SUCCESS/FAILURE
            $commission->save();
        }

        return redirect()->back();
    }

    //FUNCTION TO FETCH CITY LIST FOR A COUNTRY
    public function citiesForCountry(Request $request, $countryId)
    {
        $cityList = City::select('id', 'city_name AS text')->where('country_id', '=', $countryId)->orderBy('city_name')->get();
        return json_encode($cityList->toArray());
    }

    //FUNCTION TO GENERATE SELLER-CODE
    public function generateVendorCode($vendor)
    {
        //GET LAST VENDOR BEFORE CURRENT FROM DATABASE
        $lastVendor = Seller::whereNotNull('seller_code')->orderBy('member_since', 'DESC')->first();

        //CURRENT VENDOR IS THE FIRST VENDOR
        if (!$lastVendor) {
            $vendorCode = "A001";
        } else {
            //LATEST SELLER CODE
            $lastSellerCode = $lastVendor->seller_code;

            //CHUNK NUMBER AND LETTER
            $numberPortion = filter_var($lastSellerCode, FILTER_SANITIZE_NUMBER_INT);
            $letterPortion = str_replace($numberPortion, "", $lastSellerCode);

            //IF NUMBER CHUNK IS 999
            //NEW LETTER WILL BE USED
            if ($numberPortion == 999) {
                $letterPortion++;
                $numberPortion = 0;
            }

            $vendorCode = $letterPortion . str_pad($numberPortion + 1, 3, 0, STR_PAD_LEFT);
        }

        $vendor->seller_code = $vendorCode;
        $vendor->member_since = date("Y-m-d H:i:s");
        $vendor->save();

        return $vendorCode;
    }

    //ALL CHANGE REQUESTS
    public function changeRequests(Request $request)
    {
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) {
            $allChangeRequests = new Collection();


            //PROFILE & DOCUMENT CHANGES
            $profileChangeRequests = InfoChangeRequest::whereNotNull('name')
                ->orWhereNotNull('gender')
                ->orWhereNotNull('age')
                ->orWhereNotNull('account_type')
                ->orWhereNotNull('shop_name')
                ->orWhereNotNull('company_name')
                ->orWhereNotNull('product_categories')
                ->orWhereNotNull('country_id')
                ->orWhereNotNull('city_id')
                ->orWhereNotNull('shop_address')
                ->orWhereNotNull('company_address')
                ->orWhereNotNull('phone')
                ->orWhereNotNull('email')
                ->orWhereNotNull('photo_url')
                ->orWhereNotNull('nid_url')
                ->orWhereNotNull('tin_certificate_url')
                ->orWhereNotNull('trade_license_url')
                ->orWhereNotNull('gst_url')
                ->orWhereNotNull('bank_check_url')
                ->get();

            //ADD PROFILE CHANGE REQUEST TO THE HOLDING ARRAY
            foreach ($profileChangeRequests as $profileChangeRequest) {
                $allChangeRequests->push($profileChangeRequest->original);
            }


            //ADD NEW CATEGORY REQUESTS TO THE HOLDING ARRAY
            $categoryRequests = CategoryRequest::where('status', 'pending')->groupBy('seller_id')->get();
            foreach ($categoryRequests as $catRequest) {
                $allChangeRequests->push($catRequest->seller);
            }


            //ADD CATEGORY CHANGE REQUESTS TO THE HOLDING ARRAY
            $categoryChangeRequest = CategoryChangeRequest::where('status', 'pending')->groupBy('seller_id')->get();
            foreach ($categoryChangeRequest as $catChangeRequest) {
                $allChangeRequests->push($catChangeRequest->seller);
            }


            //TAKE UNIQUE SELLER ID'S
            $allChangeRequests = $allChangeRequests->unique('id');


            return DataTables::of($allChangeRequests)
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('country_name', function ($row) {
                    return $row->country->country_name;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('account_type', function ($row) {
                    return ucfirst($row->account_type);
                })
                ->addColumn('city_name', function ($row) {
                    return $row->city->city_name;
                })
                ->addColumn('phone_with_country_code', function ($row) {
                    return $row->country->dial_code . '-' . $row->phone;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('seller-details', ['sellerId' => $row->id]);
                    $btn =  '<a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //RENDER VIEW
        else {
            return view('vendor.pending_profile_change');
        }
    }

    //ACCEPT PROFILE CHANGES
    public function acceptProfileChange(Request $request)
    {
        $infoChangeRequest = InfoChangeRequest::where('seller_id', $request->seller_id)->first();
        if ($infoChangeRequest) {
            //FETCH SELLER DETAILS
            $sellerDetails = Seller::find($request->seller_id);
            //dd($sellerDetails);

            //PREPARE LOG DATA
            //THIS DATA WILL BE USED TO WRITE LOG
            $logData = new stdClass();
            $logData->seller_id = $request->seller_id;
            $logData->attribute_name = $request->attribute;
            $logData->old_value = $sellerDetails->{$request->attribute};
            $logData->new_value = $infoChangeRequest->{$request->attribute};
            $logData->change_status = 'approved';


            try {
                //START TRANSACTION
                DB::beginTransaction();

                //UPDATE SELLER INFO
                $sellerDetails->{$request->attribute} = $infoChangeRequest->{$request->attribute};
                $sellerDetails->save();

                //DEELTE CHANGE REQUEST
                $infoChangeRequest->{$request->attribute} = NULL;
                $infoChangeRequest->save();

                //DISPATCH INFO CHANGE EVENT
                //THIS EVENT WILL TRIGGER A LOG WRITER WHICH WILL WRITE THE LOG INTO THE DATABASE FOR FUTURE USE
                InfoChanged::dispatch($logData);

                //COMMIT CHANGES
                DB::commit();

                //RETURN SUCCESS RESPONSE
                return response()->json(array('status' => 1));
            } catch (\Exception $e) {
                //ROLLBACK CHANGES
                DB::rollback();

                //RETURN ERROR RESPONSE
                return response()->json(array('status' => 0, 'msg' => $e->getMessage()));
            }
        }
    }

    //DECLINE PROFILE CHANGES
    public function declineProfileChange(Request $request)
    {
        $infoChangeRequest = InfoChangeRequest::where('seller_id', $request->seller_id)->first();

        try {
            //START TRANSACTION
            DB::beginTransaction();

            //DEELTE CHANGE REQUEST
            $infoChangeRequest->{$request->attribute} = NULL;
            $infoChangeRequest->save();

            //COMMIT CHANGES
            DB::commit();

            //RETURN SUCCESS RESPONSE
            return response()->json(array('status' => 1));
        } catch (\Exception $e) {
            //ROLLBACK CHANGES
            DB::rollback();

            //RETURN ERROR RESPONSE
            return response()->json(array('status' => 0));
        }
    }

    //APPROVE NEW CATEGORY REQUEST
    public function approveNewCategory(Request $request)
    {
        //VERIFY AJAX
        if ($request->ajax()) {
            //CATEGORY REQUEST VERIFICATION
            $categoryRequest = CategoryRequest::find($request->request_id);

            //PREPARE LOG DATA
            //THIS DATA WILL BE USED TO WRITE LOG
            $logData = new stdClass();
            $logData->seller_id = $request->seller_id;
            $logData->attribute_name = "new_category";
            $logData->old_value = "NA";
            $logData->new_value = $request->category_id;
            $logData->change_status = 'approved';

            //REQUEST VERIFIED
            if ($categoryRequest) {
                try {
                    //START TRANSACTION
                    DB::beginTransaction();

                    //MARK THE REQUEST AS 'APPROVED'
                    $categoryRequest->status = 'approved';
                    $categoryRequest->save();

                    //SAVE NEW CATEGORY ON SELLER-CATEGORY TABLE
                    $sellerCategory = new SellerCategory();
                    $sellerCategory->seller_id = $request->seller_id;
                    $sellerCategory->category_id = $request->category_id;
                    $sellerCategory->save();

                    //SAVE COMMISSION RATE AND PROMOTER CLUB FEE FOR NEW CATEGORY
                    $commission = new Commission();
                    $commission->seller_id = $request->seller_id;
                    $commission->category_id = $request->category_id;
                    $commission->commission_rate = $request->commission_rate;
                    $commission->promoter_club_fee = $request->promoter_club_fee;
                    $commission->save();

                    //DISPATCH INFO CHANGE EVENT
                    //THIS EVENT WILL TRIGGER A LOG WRITER WHICH WILL WRITE THE LOG INTO THE DATABASE FOR FUTURE USE
                    InfoChanged::dispatch($logData);

                    //COMMIT TRANSACTION
                    DB::commit();

                    //RETURN SUCCESS RESPONSE
                    return response()->json(array('status' => 1));
                } catch (\Exception $e) {
                    //ROLLBACK CHANGES
                    DB::rollback();

                    //RETURN ERROR RESPONSE
                    return response()->json(array('status' => 0, 'msg' => $e->getMessage()));
                }
            }
        }
        //NON AJAX REQUESTS ARE NOT ALLOWED
        else {
            return response()->json(array('status' => 0));
        }
    }

    //DECLINE NEW CATEGORY REQUEST
    public function declineNewCategory(Request $request)
    {
        //VERIFY AJAX
        if ($request->ajax()) {
            //CATEGORY REQUEST VERIFICATION
            $categoryRequest = CategoryRequest::find($request->request_id);

            //REQUEST VERIFIED
            if ($categoryRequest) {
                try {
                    //START TRANSACTION
                    DB::beginTransaction();

                    //MARK THE REQUEST AS 'APPROVED'
                    $categoryRequest->status = 'approved';
                    $categoryRequest->save();

                    //COMMIT CHANGES
                    DB::commit();

                    return response()->json(array('status' => 1));
                } catch (\Exception $e) {
                    //ROLLBACK CHANGES
                    DB::rollback();

                    return response()->json(array('status' => 0, 'msg' => $e->getMessage()));
                }
            }
        }
        //NON AJAX REQUESTS ARE NOT ALLOWED
        else {
            return response()->json(array('status' => 0));
        }
    }

    //APPROVE CATEGORY CHANGE REQUEST
    function approveCatChange(Request $request)
    {
        //VERIFY AJAX
        if ($request->ajax()) {
            try {
                // dd($request->input());
                $requestId = $request->request_id;
                $sellerId = $request->seller_id;
                $newCat = $request->new_cat;
                $oldCat = $request->old_cat;

                //PREPARE LOG DATA
                //THIS DATA WILL BE USED TO WRITE LOG
                $logData = new stdClass();
                $logData->seller_id = $request->seller_id;
                $logData->attribute_name = "category_change";
                $logData->old_value = $request->old_cat;
                $logData->new_value = $request->new_cat;
                $logData->change_status = 'approved';

                //FETCH CATEGORY-CHANGE-REQUEST CURRENTLY IN SUBJECT
                $catChangeReqeust = CategoryChangeRequest::find($requestId);

                //START TRANSACTION
                DB::beginTransaction();

                //SET NEW CATEGORY IN PLACE OF OLD CATEGORY
                $sellerCategory = SellerCategory::where(['seller_id' => $sellerId, 'category_id' => $oldCat])->first();
                $sellerCategory->category_id = $newCat;
                $sellerCategory->save();

                //DELETE COMMISSION AND PROMOTER CLUB FEE RATE OF OLD CATEGORIES
                $commission = Commission::where(['seller_id' => $sellerId, 'category_id' => $oldCat])->delete();

                //UPDATE REQUEST TO SET THE STATUS TO 'APPROVED'
                $catChangeReqeust = CategoryChangeRequest::find($requestId);
                $catChangeReqeust->status = "approved";
                $catChangeReqeust->save();

                //DISPATCH INFO CHANGE EVENT
                //THIS EVENT WILL TRIGGER A LOG WRITER WHICH WILL WRITE THE LOG INTO THE DATABASE FOR FUTURE USE
                InfoChanged::dispatch($logData);

                //COMMIT CHANGES
                DB::commit();

                //RETURN SUCCESS RESPONSE
                return response()->json(array('status' => 1));
            } catch (\Exception $e) {
                //ROLLBACK CHANGES
                DB::rollback();

                //RETURN ERROR RESPONSE
                return response()->json(array('status' => 0, 'msg' => $e->getMessage()));
            }
        }
        //NON AJAX REQUESTS ARE NOT ALLOWED
        else {
            return response()->json(array('status' => 0));
        }
    }

    //DECLINE CATEGORY CHANGE REQUEST
    function declineCatChange(Request $request)
    {
        //VERIFY AJAX
        if ($request->ajax()) {
            try {
                // dd($request->input());
                $requestId = $request->request_id;


                //START TRANSACTION
                DB::beginTransaction();

                //UPDATE REQUEST TO SET THE STATUS TO 'APPROVED'
                $catChangeReqeust = CategoryChangeRequest::find($requestId);
                $catChangeReqeust->status = "declined";
                $catChangeReqeust->save();

                //COMMIT CHANGES
                DB::commit();

                //RETURN SUCCESS RESPONSE
                return response()->json(array('status' => 1));
            } catch (\Exception $e) {
                //ROLLBACK CHANGES
                DB::rollback();

                //RETURN ERROR RESPONSE
                return response()->json(array('status' => 0, 'msg' => $e->getMessage()));
            }
        }
        //NON AJAX REQUESTS ARE NOT ALLOWED
        else {
            return response()->json(array('status' => 0));
        }
    }

    //VIEW CHANGE LOG PAGE
    public function changeLog(Request $request, $vendorId)
    {
        //FETCH CHANGES LOGS IN DESCENDING ORDER SO THAT LATEST CHANGES ARE SHOWN FIRST
        $changeLogs = ChangeLog::where('seller_id', Auth::id())->latest()->get();
        //dd($changeLogs);

        return view('vendor.change_log', compact('changeLogs'));
    }
}
