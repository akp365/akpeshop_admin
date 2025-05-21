<!DOCTYPE html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- TITLE -->
    <title>AKP-ADMIN</title>

    <!-- THEME CSS FILES -->
    <link href="{{ URL::asset('admin_assets/css/vendor/all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('admin_assets/css/app/app.css') }}" rel="stylesheet">

    <!-- POP UP LIBRARY CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/jquery_confirm/jquery-confirm.min.css') }}">

    <!-- SUMMERNOTE CSS -->
    <link href="{{ asset('summernote/summernote-lite.css') }}" rel="stylesheet">

    <!-- CSRF TOKEN META -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>



        <!-- Inline Script for colors and config objects; used by various external scripts; -->
        <script>
            var colors = {
                "danger-color": "#e74c3c",
                "success-color": "#81b53e",
                "warning-color": "#f0ad4e",
                "inverse-color": "#2c3e50",
                "info-color": "#2d7cb5",
                "default-color": "#6e7882",
                "default-light-color": "#cfd9db",
                "purple-color": "#9D8AC7",
                "mustard-color": "#d4d171",
                "lightred-color": "#e15258",
                "body-bg": "#f6f6f6"
            };
            var config = {
                theme: "admin",
                skins: {
                    "default": {
                        "primary-color": "#3498db"
                    }
                }
            };
        </script>
        <script src="{{ URL::asset('admin_assets/js/vendor/all.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/app/app.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.extensions.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.services.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.microdata.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.microformat.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.overlays.js') }}"></script>
        <script src="{{ asset('admin_assets/js/vendor/maps/google/jquery-ui-map/ui/jquery.ui.map.rdfa.js') }}"></script>

        <!-- POP UP LIBRARY JS -->
        <script src="{{ asset('admin_assets/jquery_confirm/jquery-confirm.min.js') }}"></script>

        <!-- JQUERY FORM VALIDATION -->
        <script src="{{ asset('admin_assets/jquery_validate/jquery.validate.min.js') }}"></script>

        <!-- SUMMERNOTE JS -->
        <script src="{{ asset('summernote/summernote-lite.js') }}"></script>

        <!-- PRINT PLUGIN -->
        <script src="{{ asset('admin_assets/js/printThis.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>




<div>
    <button type="submit" class="btn btn-primary filter-btn" style="display: none" onclick="$('.data-table_details').tblToExcel();">Export as .XLSX</button>

    <!-- Data table -->
    <table class="table table-bordered data-table_details" cellspacing="0" width="100%">
        <thead>
            <tr>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>






















{{-- 
<div class="productQuantityEditor addButtonWrapper border-radius-small" data-reactid=".28un79bu7ri.e.2.0.0.0.0.2.6.1.0:$52020_Grocery.0.4" id="product_{{$promotional->id}}"
    style="
    @foreach (Cart::content() as $item)
        @if($item->id != $promotional->id)
            display:none;
        @else
            @php
                $rowID = $item->rowId;
            @endphp
        @endif
    @endforeach
    ">

   @if($rowID)
       @php
           $cartItem = Cart::get($rowID); 
       @endphp
       <button class="minusQuantity">–</button>
       <div class="QuantityTextContainer>
           <span id="QuantityPeo_quantity">55
           </span>
           <span>in bag</span>
       </div>
       <button type="button" class="plusQuantity" onclick="updatenum('{{$rowID}}')">+</button>
       
       <input type="text" value="3" id="QuantityPeo{{$rowID}}"/>
       
   @else
   @endif
</div> --}}

  

























<script type="text/javascript">
$(function () {
let ordersData = [];
let moreInfoData = [];

function fetchOrdersData() {
return $.ajax({
    url: "{{ route('order.view') }}?start_date={{ request()->query('start_date') }}&end_date={{ request()->end_date}}&country_id={{ request()->country_id}}&seller_id={{ request()->seller_id}}&ordered_currency={{ request()->ordered_currency}}&order_status={{ request()->order_status}}",
    type: "GET",
    dataType: "json",
    success: function (response) {
        ordersData = response.data; 
    }
});
}

function fetchMoreInfoData() {
return $.ajax({
    url: "{{ route('order_more_info') }}",
    type: "GET",
    dataType: "json",
    success: function (response) {
        moreInfoData = response.data; 
    }
});
}

$.when(fetchOrdersData(), fetchMoreInfoData()).done(function () {
let combinedData = ordersData.map(order => {
    let extraInfo = moreInfoData.find(info => info.id === order.id) || {};
    return { ...order, ...extraInfo }; 
});

$('.data-table_details').DataTable({
    processing: true,
    serverSide: false, 
    data: combinedData,
    columns: [
        {
            data: null,
            title: 'Sl No',
            render: function (data, type, row, meta) {
                return meta.row + 1;
            },
            searchable: false
        },
        { data: 'invoice_no', name: 'invoice_no', title: 'Invoice No' },
        { data: 'formatted_date', name: 'formatted_date', title: 'Date (dd-mm-yy)' },
        { data: 'seller_details', name: 'seller_details', title: 'Sellers' },
        { data: 'order_country_name', name: 'order_country_name', title: 'Order Country' },
        { data: 'customer_name', name: 'customer_name', title: 'Customer Name' },
        { data: 'contract_number', name: 'contract_number', title: 'Contract Number' },
        { data: 'order_details_btn', name: 'order_details_btn', title: 'Order Details' },
        { data: 'order_status_dropdown', name: 'order_status_dropdown', title: 'Order Status' },
        { data: 'payment_status', name: 'payment_status', title: 'Payment Status' },
        { data: 'payment_method', name: 'payment_method', title: 'Payment Method' },
        { data: 'billing_address', name: 'billing_address', title: 'Shipping Address' },
        { data: 'currency', name: 'currency', title: 'Currency' },

        // order_more_info
        { data: 'final_invoice_value', name: 'final_invoice_value', title: 'Invoice Value' },
        { data: 'tax', name: 'tax', title: 'Tax' },
        { data: 'seller_total_shipping_fee', name: 'seller_total_shipping_fee', title: 'Shipping Fee' },
        { data: 'checkout_cod_charge', name: 'checkout_cod_charge', title: 'COD Charge' },
        { data: 'total_discount', name: 'total_discount', title: 'Coupon Discount' },
        { data: 'total_price_without_vat', name: 'total_price_without_vat', title: 'Product Price' },
        { data: 'commisonFee', name: 'commisonFee', title: 'Commission' },
        { data: 'promoterClubFee', name: 'promoterClubFee', title: 'Promoter Fee' },
        { data: 'vatOnFee', name: 'vatOnFee', title: 'VAT on Fee' },
        { data: 'seller_payable_money', name: 'seller_payable_money', title: 'Seller/Agent Payable' },
        { data: 'subcidy', name: 'subcidy', title: 'Subsidy' },
        { data: 'earnings', name: 'earnings', title: 'Earnings' },
    ]
});
});
});
</script>

<script>
$(document).ready(function () {
    setTimeout(function () {
        $(".filter-btn").trigger("click"); // Click the filter button

        setTimeout(function () {
            // Attempt to close the current tab
            if (window.opener) {
                window.close(); // Close the current tab if it was opened by JavaScript
            } else {
                console.log("The current tab cannot be closed because it was not opened by JavaScript.");
            }
        }, 2000); // Delay closing to allow the button action to take effect
    }, 1000); // Wait 1 second before clicking
});
</script>

<script src="{{ asset('js/jquery.tableToExcel.js') }}"></script>
<script src="{{ asset('js/table2csv') }}"></script>

<script>
    $.ajax({
        url: "{{route('get_total_sells')}}", // Update with your actual route URL
        type: "GET",
        dataType: "json",
        success: function (response) {
            $("#total_sells_amnt_all_currency").html(response.total_selected_currency_amnt + " " + response.currency_name);
            console.log("Total Selected Currency Amount:", response.total_selected_currency_amnt + " " + response.currency_name);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });

</script>


</body>

</html>
















