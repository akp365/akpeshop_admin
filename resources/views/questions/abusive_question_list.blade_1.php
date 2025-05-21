@extends('layout')

@section('content')

<h4 class="page-section-heading">Reported Question List</h4>



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
                <th>Product Code</th>
                <th>Question</th>
                <th>Author</th>
                <th>Reports</th>
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
    var dataTable;
    $(function() {
        dataTable = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            scrollX: true,
            ajax: "{{ route('abusive-questions') }}",
            columns: [
                {
                    data: 'formatted_date',
                    name: 'formatted_date',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'product_code',
                    name: 'product_code',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'question',
                    name: 'question',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'author_name',
                    name: 'author_name',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'reports',
                    name: 'reports',
                    className: "centerTextInDataTable"
                },
                {
                    data: 'status',
                    name: 'status',
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
                $('td', row).eq(4).addClass('redText');

                if ( data.status == 'inactive' ) 
                {
                    $('td', row).eq(7).addClass('redText');
                }
                else
                {
                    $('td', row).eq(7).addClass('greenText');
                }
            },    
        });
    });


    //METHOD TO PUBLISH/UNPUBLISH A Category
    function changeStatus(itemID, newStatus) {
        var waitPopUp;
        var statusText = newStatus == 1 ? "active" : "inactive";

        $.ajax({
            type: 'POST',
            url: "{{ route('change-product-question-status') }}",
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
                dataTable.ajax.reload();
            },
            error: function(event){
                waitPopUp.close();
            }
        });
    }

    //METHOD TO DISMISS A REPORT
    function dismissReport(itemID) {
        var waitPopUp;

        $.ajax({
            type: 'POST',
            url: "{{ route('dismiss-abuse-report') }}",
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
                dataTable.ajax.reload();
            },
            error: function(event){
                waitPopUp.close();
            }
        });
    }
</script>
@stop
@endsection