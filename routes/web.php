<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExportDataController;
use App\Http\Controllers\SellerPayableController;
use App\Http\Controllers\UpdateOrderController;
use App\Http\Controllers\VendorController;
use App\Models\Models\SiteLook;
use App\Models\UpdateOrder;
use Facade\FlareClient\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftBalanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//HOME
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('looks');
    }
    return redirect('login');
})->name('home');

//LOGIN
Route::get('login', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    }
    return view('login');
})->name('login');

//LOGOUT
Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

//LOGIN POST METHOD
Route::post('authenticate', [App\Http\Controllers\LoginController::class, 'authenticate'])->name('authenticate');

Route::middleware(['auth'])->group(function () {
    //HOME
    Route::get('dashboard',[AdminController::class, 'index'])->name('dashboard');
    Route::get('open-ticket', [AdminController::class, 'OpenTicketList'])->name('open_ticket');
    Route::get('/open-ticket/view/{ticket_unique_id}', [AdminController::class, 'OpenTicketView'])->name('OpenTicketView');
    Route::post("/send-message", [AdminController::class, 'SendTicketMessage'])->name('SendTicketMessage');
    Route::post("/assign-ticket", [AdminController::class, 'AssignTicket'])->name('AssignTicket');

    Route::get('/gift-balance', [GiftBalanceController::class, 'index'])->name('gift-balance.index');
    Route::post('/gift-balance', [GiftBalanceController::class, 'store'])->name('gift-balance.store');
    Route::get('/search/customers', [GiftBalanceController::class, 'searchCustomers'])->name('search.customers');
    Route::get('/initial/customers', [GiftBalanceController::class, 'getInitialCustomers'])->name('initial.customers');

    //SETTINGS
    Route::get('settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::post('save_default_currency', [App\Http\Controllers\SettingsController::class, 'saveDefaultCurrency'])->name('save_default_currency');
    Route::post('set_cod', [App\Http\Controllers\SettingsController::class, 'set_cod'])->name('set_cod');


    //LOOKS
    Route::get('looks', [App\Http\Controllers\LookController::class, 'index'])->name('looks');
    Route::post('save_header_1_color', [App\Http\Controllers\LookController::class, 'saveHeaderOneColor'])->name('save_header_1_color');
    Route::post('save_header_2_color', [App\Http\Controllers\LookController::class, 'saveHeaderTwoColor'])->name('save_header_2_color');
    Route::post('save_category_color', [App\Http\Controllers\LookController::class, 'saveCategoryColor'])->name('save_category_color');
    Route::post('save_category_item_hover_color', [App\Http\Controllers\LookController::class, 'saveCategoryItemHoverColor'])->name('save_category_item_hover_color');
    Route::post('save_header_logo', [App\Http\Controllers\LookController::class, 'saveHeaderLogo'])->name('save_header_logo');
    Route::post('save_footer_logo', [App\Http\Controllers\LookController::class, 'saveFooterLogo'])->name('save_footer_logo');
    Route::post('save_background_image', [App\Http\Controllers\LookController::class, 'saveBackgroundImage'])->name('save_background_image');
    Route::post('save_footer_bg_color', [App\Http\Controllers\LookController::class, 'saveFooterBgColor'])->name('save_footer_bg_color');


    //FOOTER
    Route::get('footer', [App\Http\Controllers\FooterController::class, 'index'])->name('footer');
    Route::post('save_footer_link', [App\Http\Controllers\FooterController::class, 'saveFooterLink'])->name('save_footer_link');

    //PAYMENT METHOD IMAGES START
    Route::get('payment-methods', [App\Http\Controllers\PaymentMethodImageController::class, 'index'])->name('payment-methods');

    Route::post('save_pm_image', [App\Http\Controllers\PaymentMethodImageController::class, 'savePayMethodImages'])->name('save_pm_image');
    Route::post('save_payment_methods', [App\Http\Controllers\PaymentMethodImageController::class, 'savePmImages'])->name('save_payment_methods');
    Route::post('delete-pm', [App\Http\Controllers\PaymentMethodImageController::class, 'deletePmImage'])->name('delete-pm');

    //PAYMENT METHOD IMAGES END

    //SOCIAL NETWORKS START
    Route::get('social-networks', [App\Http\Controllers\SocialNetworkController::class, 'index'])->name('social-networks');
    Route::post('save-social-network-1', [App\Http\Controllers\SocialNetworkController::class, 'saveSocialNetwork1'])->name('save-social-network-1');
    Route::post('save-social-network-2', [App\Http\Controllers\SocialNetworkController::class, 'saveSocialNetwork2'])->name('save-social-network-2');
    Route::post('save-social-network-3', [App\Http\Controllers\SocialNetworkController::class, 'saveSocialNetwork3'])->name('save-social-network-3');
    Route::post('save-social-network-4', [App\Http\Controllers\SocialNetworkController::class, 'saveSocialNetwork4'])->name('save-social-network-4');
    Route::post('save-social-network-5', [App\Http\Controllers\SocialNetworkController::class, 'saveSocialNetwork5'])->name('save-social-network-5');
    //SOCIAL NETWORKS END

    //FOOTER TEXTS START
    Route::get('footer-texts', [App\Http\Controllers\FooterTextController::class, 'index'])->name('footer-texts');
    Route::post('save_footer_text_1', [App\Http\Controllers\FooterTextController::class, 'saveFooterTextOne'])->name('save_footer_text_1');
    Route::post('save_footer_text_2', [App\Http\Controllers\FooterTextController::class, 'saveFooterTextTwo'])->name('save_footer_text_2');
    Route::post('save_footer_text_3', [App\Http\Controllers\FooterTextController::class, 'saveFooterTextThree'])->name('save_footer_text_3');
    Route::post('copyright', [App\Http\Controllers\FooterTextController::class, 'copyright'])->name('copyright');
    Route::post('save_footer_address', [App\Http\Controllers\FooterTextController::class, 'saveFooterAddress'])->name('save_footer_address');
    //FOOTER TEXTS END

    //BANNER & ADDS
    Route::get('banner-and-adds', [App\Http\Controllers\BannerController::class, 'showBannerAndAdd'])->name('banner-and-adds');
    Route::post('save_top_banner', [App\Http\Controllers\BannerController::class, 'saveTopBanner'])->name('save_top_banner');
    Route::get('home-slider', [App\Http\Controllers\HomeSliderController::class, 'showHomeSlider'])->name('home-slider');
    Route::post('save_home_slider', [App\Http\Controllers\HomeSliderController::class, 'saveHomeSlider'])->name('save_home_slider');
    Route::get('home-adds', [App\Http\Controllers\HomeAddController::class, 'showHomeAdds'])->name('home-adds');
    Route::post('save_home_add', [App\Http\Controllers\HomeAddController::class, 'saveHomeAdd'])->name('save_home_add');


    //PAGES
    Route::get('pages', [App\Http\Controllers\PagesController::class, 'index'])->name('pages');
    Route::match(['get', 'post'], 'new-page', [App\Http\Controllers\PagesController::class, 'addNewPage'])->name('new-page');
    Route::match(['get', 'post'], 'edit-page/{pageId?}', [App\Http\Controllers\PagesController::class, 'editPage'])->name('edit-page');
    Route::post('change-page-status', [App\Http\Controllers\PagesController::class, 'changePageStatus'])->name('change-page-status');
    Route::post('delete-page', [App\Http\Controllers\PagesController::class, 'deletePage'])->name('delete-page');

    //MENUS-1
    Route::match(['get', 'post'], 'menus-1', [App\Http\Controllers\MenuOneController::class, 'index'])->name('menus-1');
    Route::match(['get', 'post'], 'new-menu-1', [App\Http\Controllers\MenuOneController::class, 'addNewMenu'])->name('new-menu-1');
    Route::match(['get', 'post'], 'edit-menu-1/{menuId?}', [App\Http\Controllers\MenuOneController::class, 'editMenu'])->name('edit-menu-1');
    Route::post('change-menu-status-1', [App\Http\Controllers\MenuOneController::class, 'changeMenuStatus'])->name('change-menu-status-1');
    Route::post('delete-menu-1', [App\Http\Controllers\MenuOneController::class, 'deleteMenu'])->name('delete-menu-1');

    //MENUS-2
    Route::match(['get', 'post'], 'menus-2', [App\Http\Controllers\MenuTwoController::class, 'index'])->name('menus-2');
    Route::match(['get', 'post'], 'new-menu-2', [App\Http\Controllers\MenuTwoController::class, 'addNewMenu'])->name('new-menu-2');
    Route::match(['get', 'post'], 'edit-menu-2/{menuId?}', [App\Http\Controllers\MenuTwoController::class, 'editMenu'])->name('edit-menu-2');
    Route::post('change-menu-status-2', [App\Http\Controllers\MenuTwoController::class, 'changeMenuStatus'])->name('change-menu-status-2');
    Route::post('delete-menu-2', [App\Http\Controllers\MenuTwoController::class, 'deleteMenu'])->name('delete-menu-2');

    //CATEGORIES
    Route::match(['get', 'post'], 'categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
    Route::post('change-category-status', [App\Http\Controllers\CategoryController::class, 'changeCategoryStatus'])->name('change-category-status');
    Route::match(['get', 'post'], 'edit-category/{catId?}', [App\Http\Controllers\CategoryController::class, 'editCategory'])->name('edit-category');
    Route::match(['get', 'post'], 'add-new-category', [App\Http\Controllers\CategoryController::class, 'addNewCategory'])->name('add-new-category');
    Route::post('delete-category', [App\Http\Controllers\CategoryController::class, 'deleteCategory'])->name('delete-category');

    //COUPONS
    Route::match(['get', 'post'], 'coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons');
    Route::match(['get', 'post'], 'coupon-details', [App\Http\Controllers\CouponController::class, 'couponDetails'])->name('coupon-details');
    Route::match(['get', 'post'], 'update-coupon', [App\Http\Controllers\CouponController::class, 'updateCoupon'])->name('update-coupon');
    Route::match(['get', 'post'], 'add-new-coupon', [App\Http\Controllers\CouponController::class, 'addNewCoupon'])->name('add-new-coupon');
    Route::match(['get', 'post'], 'save-new-coupon', [App\Http\Controllers\CouponController::class, 'saveNewCoupon'])->name('save-new-coupon');
    Route::post('delete-coupon', [App\Http\Controllers\CouponController::class, 'deleteCoupon'])->name('delete-coupon');
    Route::post('delete-coupon-product', [App\Http\Controllers\CouponController::class, 'deleteCouponProduct'])->name('delete-coupon-product');

    //CURRENCIES
    Route::match(['get', 'post'], 'currencies', [App\Http\Controllers\CurrencyController::class, 'index'])->name('currencies');
    Route::post('change-currency-status', [App\Http\Controllers\CurrencyController::class, 'changeCurrencyStatus'])->name('change-currency-status');
    Route::match(['get', 'post'], 'edit-currency/{currencyId?}', [App\Http\Controllers\CurrencyController::class, 'editCurrency'])->name('edit-currency');
    Route::match(['get', 'post'], 'add-new-currency', [App\Http\Controllers\CurrencyController::class, 'addNewCurrency'])->name('add-new-currency');
    Route::post('delete-currency', [App\Http\Controllers\CurrencyController::class, 'deleteCurrency'])->name('delete-currency');

    //PRE APPROVAL
    Route::get('pre-approval', [App\Http\Controllers\VendorController::class, 'pendingForPreApproval'])->name('pre-approval');

    //FINAL APPROVAL
    Route::get('final-approval', [App\Http\Controllers\VendorController::class, 'pendingForFinalApproval'])->name('final-approval');

    //REGISTERED SELLER LIST
    Route::get('seller-list', [App\Http\Controllers\VendorController::class, 'sellerList'])->name('seller-list');

    //UPDATE VENDOR STATUS
    Route::post('change-vendor-status', [App\Http\Controllers\VendorController::class, 'changeVendorStatus'])->name('change-vendor-status');

    //RESENT PRE-APPROVAL EMAIL
    Route::post('resend-pre-approval-email', [App\Http\Controllers\VendorController::class, 'resendPreApprovalMail'])->name('resend-pre-approval-email');

    //SHOW SELLER DETAILS
    Route::get('seller-details/{sellerId?}', [App\Http\Controllers\VendorController::class, 'vendorDetails'])->name('seller-details');
    Route::get('change-log/{vendorId}', [App\Http\Controllers\VendorController::class, 'changeLOg'])->name('change-log');

    //UPDATE SELLER INFO
    Route::post('update-seller', [App\Http\Controllers\VendorController::class, 'updateVendor'])->name('update-seller');

    //CITY LIST API URL
    Route::get('cities-for-country/{countryId}', [App\Http\Controllers\VendorController::class, 'citiesForCountry'])->name('cities-for-country');

    //ALL CHANGE REQUESTS
    Route::get('change-requests', [App\Http\Controllers\VendorController::class, 'changeRequests'])->name('change-requests');

    //PROFILE CHANGE REQUESTS
    Route::get('profile-change-request', [App\Http\Controllers\VendorController::class, 'pendingProfileChanges'])->name('profile-change-request');
    Route::get('document-change-request', [App\Http\Controllers\VendorController::class, 'pendingDocumentChanges'])->name('document-change-request');
    Route::post('accept-profile-change', [App\Http\Controllers\VendorController::class, 'acceptProfileChange'])->name('accept-profile-change');
    Route::post('decline-profile-change', [App\Http\Controllers\VendorController::class, 'declineProfileChange'])->name('decline-profile-change');

    //NEW CATEGORY REQUESTS
    Route::get('new-category-request', [App\Http\Controllers\VendorController::class, 'pendingCategoryRrequests'])->name('new-category-request');
    Route::post('approve-new-category', [App\Http\Controllers\VendorController::class, 'approveNewCategory'])->name('approve-new-category');
    Route::post('decline-new-category', [App\Http\Controllers\VendorController::class, 'declineNewCategory'])->name('decline-new-category');

    //CATEGORY CHANGE REQUESTS
    Route::get('category-change-request', [App\Http\Controllers\VendorController::class, 'catChangeRequests'])->name('category-change-request');
    Route::post('approve-category-change', [App\Http\Controllers\VendorController::class, 'approveCatChange'])->name('approve-category-change');
    Route::post('decline-category-change', [App\Http\Controllers\VendorController::class, 'declineCatChange'])->name('decline-category-change');

    //PRODUCTS
    Route::get('products/pending', [App\Http\Controllers\ProductController::class, 'pendingProducts'])->name('pending-products');
    Route::get('products/list', [App\Http\Controllers\ProductController::class, 'productList'])->name('product-list');
    Route::get('product/details/{productId}', [App\Http\Controllers\ProductController::class, 'productDetails'])->name('product-details');
    Route::post('update-product', [App\Http\Controllers\ProductController::class, 'updateProduct'])->name('update-product');
    Route::get('product/reports', [App\Http\Controllers\ProductController::class, 'reportedProductList'])->name('reported-products');
    Route::post('change-product-question-status', [App\Http\Controllers\ProductController::class, 'changeQuestionStatus'])->name('change-product-question-status');
    Route::post('dismiss-reports', [App\Http\Controllers\ProductController::class, 'dismissReports'])->name('dismiss-reports');

    //ORDERS
    Route::get('orders', [App\Http\Controllers\OrderController::class, 'view'])->name('order.view');
    Route::get('orders/edit-page', [App\Http\Controllers\OrderController::class, 'edit_view'])->name('order.edit_view');
    Route::get('orders/details', [App\Http\Controllers\OrderController::class, 'order_details'])->name('order.details');
    Route::get('orders/more-info', [App\Http\Controllers\OrderController::class, 'order_more_info'])->name('order_more_info');
    Route::get('orders/yajra', [App\Http\Controllers\OrderController::class, 'yajra'])->name('yajra');



    Route::post('order-update', [App\Http\Controllers\OrderController::class, 'OrderUpdate'])->name('OrderUpdate');
    Route::get('order-delete', [App\Http\Controllers\OrderController::class, 'DeleteOrder'])->name('delete_order');
    Route::get('get-cities', [App\Http\Controllers\OrderController::class, 'GetCities'])->name('GetCities');
    Route::get('vendor-withdraw', [App\Http\Controllers\OrderController::class, 'VendorWithdraw'])->name('VendorWithdraw');
    Route::post('approve-seller-withdraw', [App\Http\Controllers\OrderController::class, 'ApproveSellerWithdraw'])->name('ApproveSellerWithdraw');
    Route::post('pending-seller-withdraw', [App\Http\Controllers\OrderController::class, 'PendingSellerWithdraw'])->name('PendingSellerWithdraw');
    Route::get('delete-seller-withdraw', [App\Http\Controllers\OrderController::class, 'DeleteSellerWithdraw'])->name('DeleteSellerWithdraw');
    Route::get('view-seller-earnings', [App\Http\Controllers\OrderController::class, 'SellerEarnings'])->name('SellerEarnings');


    //UPDATE PRODUCT STATUS
    Route::post('change-product-status', [App\Http\Controllers\ProductController::class, 'changeProductStatus'])->name('change-product-status');

    //CITY LIST API URL
    Route::get('cities-for-country/{countryId}', [App\Http\Controllers\HelperController::class, 'citiesForCountry'])->name('cities-for-country');
    Route::get('cities-for-country-2/{countryId}', [App\Http\Controllers\HelperController::class, 'citiesForCountryWithAll'])->name('cities-for-country-2');

    //CATEGORY LIST API URL
    Route::get('subcat-of-cat/{categoryId}', [App\Http\Controllers\HelperController::class, 'subcatOfCat'])->name('subcat-of-cat');

    //-- Q-&-A ABUSE
    Route::get('abusive-questions', [App\Http\Controllers\ProductController::class, 'abusiveQuestions'])->name('abusive-questions');
    Route::post('dismiss-abuse-report', [App\Http\Controllers\ProductController::class, 'dismissAbuseReport'])->name('dismiss-abuse-report');

    //PRODUCT LIST API URL
    Route::get('products-for-type/{typeId}', [App\Http\Controllers\HelperController::class, 'productsForType'])->name('products-for-type');

    //GEO LOCATIONS
    Route::match(['get', 'post'], 'countries', [App\Http\Controllers\GeoController::class, 'index'])->name('countries');
    Route::match(['get', 'post'], 'country/{countryId}/cities', [App\Http\Controllers\GeoController::class, 'cityOfCountries'])->name('city-list');
    Route::match(['get', 'post'], 'edit-country/{countryId?}', [App\Http\Controllers\GeoController::class, 'editCountry'])->name('edit-country');
    Route::match(['get', 'post'], 'add-new-country', [App\Http\Controllers\GeoController::class, 'addNewCountry'])->name('add-new-country');
    Route::post('delete-country', [App\Http\Controllers\GeoController::class, 'deleteCountry'])->name('delete-country');
    Route::match(['get', 'post'], 'add-new-city', [App\Http\Controllers\GeoController::class, 'addNewCity'])->name('add-new-city');
    Route::match(['get', 'post'], 'edit-city/{cityId}', [App\Http\Controllers\GeoController::class, 'editCity'])->name('edit-city');
    Route::post('delete-city', [App\Http\Controllers\GeoController::class, 'deleteCity'])->name('delete-city');

    Route::get('payment-options', [App\Http\Controllers\PaymentOptionsController::class, 'index'])->name('payment-options');
    Route::post('save-payment-option', [App\Http\Controllers\PaymentOptionsController::class, 'savePaymentOption'])->name('save-payment-option');


});

//TEST MAIL SEND
Route::get('test-mail', [VendorController::class, 'testMail'])->name('test-mail');







//Extra routes
Route::post('/update-seller-payable', [SellerPayableController::class, 'updateData'])->name('update_seller_payable_data');
Route::post('/update-order-status', [UpdateOrderController::class, 'updateOrder'])->name('updateOrder');


Route::get('export-order-details', [ExportDataController::class,'exportOrder'])->name('exportOrder');
Route::get('orders-public', [App\Http\Controllers\OrderController::class, 'view'])->name('order.view_public');
Route::get('orders/more-info-public', [App\Http\Controllers\OrderController::class, 'order_more_info'])->name('order_more_info_public');

Route::get('/get-total-sells',[AdminController::class,'get_total_sells'])->name('get_total_sells');
Route::get('/get-total-sells-by-currency',[VendorController::class,'get_total_sells_by_currency'])->name('get_total_sells_by_currency');
