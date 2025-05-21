@extends('layout')
@section('page_title', 'Pending for Pre-Approval')
@section('content')

<h4 class="page-section-heading">Pending for Pre-Approvals</h4>



<div class="panel panel-default">
    <div class="row" style="text-align:center;">
        <!-- SHOW VALIDATION ERRORS IF ANY -->
        @if(count($errors))
        <div class="form-group">
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
    </div>

    <!-- Data table -->
    <table class="table table-bordered data-table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Account Type</th>
                <th>Country</th>
                <th width="300px">Mobile</th>
                <th>Status</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <!-- // Data table -->
</div>
<style>
    td.redText {
        font-weight: bold;
        color: red;
    }

    td.greenText {
        font-weight: bold;
        color: green;
    }

    .centerTextInDataTable {
        text-align: center;
    }
</style>
@section('scripts')
<script type="text/javascript">
    //DATA TABLE LOADING METHOD
    var datasTable;
    $(function() {
        datasTable = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            bAutoWidth: false,
            scrollX: true,
            ajax: "{{ route('pre-approval') }}",
            columns: [
                {
                    data: 'formatted_date',
                    name: 'formatted_date',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'name',
                    name: 'name',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'account_type',
                    name: 'account_type',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'country_name',
                    name: 'country_name',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'phone_with_country_code',
                    name: 'phone_with_country_code',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'account_status',
                    name: 'account_status',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "centerTextInDataTable"
                },
            ],
            createdRow: function ( row, data, index ) {
                if ( data.account_status == 'pre_declined' ) 
                {
                    $('td', row).eq(5).addClass('redText');
                }
                else if( data.account_status == 'pre_approved' )
                {
                    $('td', row).eq(5).addClass('greenText');   
                }
            },
        });
    });

    //METHOD TO PUBLISH/UNPUBLISH A Category
    function changeStatus(itemID, newStatus) {
        var waitPopUp;
        var statusText = newStatus == 1 ? "pre_approved" : "pre_declined";

        $.ajax({
            type: 'POST',
            url: "{{ route('change-vendor-status') }}",
            data: {
                "itemId": itemID,
                "_token": "{{ csrf_token() }}",
                "status": statusText
            },
            beforeSend: function(){
                waitPopUp = $.alert({
                    title: '',
                    content: '<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>',
                    buttons:[],
                    closeIcon: false,
                });
            },
            success: function(data) {
                waitPopUp.close();
                datasTable.ajax.reload();
            },
            error: function(event){
                waitPopUp.close();
            }
        });
    }

     //METHOD TO RESEND APPROVAL EMAIL
     function resendPreApprovalEmail(itemID) {
        var waitPopUp;

        $.ajax({
            type: 'POST',
            url: "{{ route('resend-pre-approval-email') }}",
            data: {
                "itemId": itemID,
                "_token": "{{ csrf_token() }}",
            },
            beforeSend: function(){
                waitPopUp = $.alert({
                    title: '',
                    content: '<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>',
                    buttons:[],
                    closeIcon: false,
                });
            },
            success: function(data) {
                waitPopUp.close();
                datasTable.ajax.reload();
            },
            error: function(event){
                waitPopUp.close();
            }
        });
    }
</script>
@stop
@endsection