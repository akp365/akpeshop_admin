@extends('layout')

@section('content')

<h4 class="page-section-heading">Page List</h4>


<div class="panel-body buttons-spacing-vertical">
    <p>
        <a href="{{ route( 'new-page', app()->getLocale() ) }}" class="btn btn-success" style="float: right;"><i class="fa fa-plus"></i> New page</a>
    </p>
</div>


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
                <th>No.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th width="180px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <!-- // Data table -->
</div>
@section('scripts')
<script type="text/javascript">
    //DATA TABLE LOADING METHOD
    var pageTable;
    $(function() {
        pageTable = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pages') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });




    });

    //METHOD TO PUBLISH/UNPUBLISH A PAGE
    function changeStatus(itemID, newStatus) {
        $.ajax({
            type: 'POST',
            url: "{{ route('change-page-status') }}",
            dataType: 'JSON',
            data: {
                "itemId": itemID,
                "_token": "{{ csrf_token() }}",
                "status": newStatus
            },
            success: function(data) {
                if (data.status == 1) {
                    pageTable.ajax.reload();
                } else {
                    $.alert({
                        title: 'Snap !!',
                        icon: 'fa fa-error',
                        content: 'Something went wrong, please try again !',
                        type: 'red'
                    });
                }
            }
        });
    }

    //METHOD TO DELETE PAGE
    function deleteIt(itemName, itemID) {
        $.confirm({
            title: `<strong>${itemName}</strong>`,
            icon: 'fa fa-warning',
            content: 'Sure to delete the page ?',
            type: 'red',
            buttons: {
                Cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-red',
                },
                deleteIncome: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function() {
                        runDelete(itemID);
                    }
                },
            }
        });
    }

    function runDelete(itemID) {
        $.ajax({
            type: 'POST',
            url: "{{ route('delete-page') }}",
            data: {
                "itemId": itemID,
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.status == 1) {
                    pageTable.ajax.reload();
                } else {
                    $.alert({
                        title: 'Snap !!',
                        icon: 'fa fa-error',
                        content: 'Something went wrong, please try again !',
                        type: 'red'
                    });
                }

            }
        });
    }
</script>
@stop
@endsection