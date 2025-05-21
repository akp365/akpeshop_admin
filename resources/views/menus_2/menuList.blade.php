@extends('layout')

@section('content')
                <!-- TITLE -->
                <h4 class="page-section-heading">Items on menu-two </h4>

                 <!-- ADD NEW BUTTON -->
                <div class="panel-body buttons-spacing-vertical">
                  <p>
                    <a href="{{ route( 'new-menu-1' ) }}" class="btn btn-success" style="float: right;"><i class="fa fa-plus"></i> New menu</a>
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
                          <th>ID</th>
                          <th>Title</th>
                          <th>URL</th>
                          <th>Landing Page</th>
                          <th>Order</th>
                          <th>Status</th>
                          <th width="180px">Action</th>
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
  var menuTable;
  $(function () { 
    menuTable = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('menus-2') }}",
            type: "POST",
        },
        order: [[ 4, "desc" ]],
        columns: [
            {
                data: 'id', 
                name: 'id', 
                className: "centerTextInDataTable" 
            },
            {
                data: 'title', 
                name: 'title', 
                className: "centerTextInDataTable" 
            },
            {
                data: 'url', 
                name: 'url', 
                className: "centerTextInDataTable" 
            },
            {
                data: 'page_name', 
                name: 'page_name', 
                className: "centerTextInDataTable", 
            },
            {
                data: 'order_num', 
                name: 'order_num', 
                className: "centerTextInDataTable" 
            },
            {
                data: 'status', 
                name: 'status', 
                render: function ( data, type, row ) { 
                    return data.toUpperCase(); 
                    }, 
                className: "centerTextInDataTable" },
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                className: "centerTextInDataTable" 
            },
        ],
        "createdRow": function ( row, data, index ) {
            if ( data.status == 'active' ) 
            {
                $('td', row).eq(5).addClass('greenText');
            }
            else
            {
                $('td', row).eq(5).addClass('redText'); 
            }
        },
    }); 
  });


    //METHOD TO PUBLISH/UNPUBLISH A PAGE
    function changeStatus( itemID, newStatus ) {
        $.ajax({
            type:'POST',
            url:"{{ route('change-menu-status-2') }}",
            data: {"itemId": itemID , "_token": "{{ csrf_token() }}", "status": newStatus },
            success:function(data) {
                console.log(data);
                menuTable.ajax.reload();
            }
        });
    }

  //METHOD TO DELETE PAGE
  function deleteIt( itemName, itemID ){
    $.confirm({
            title: `<strong>${itemName}</strong>`,
            icon: 'fa fa-warning',
            content: 'Sure to delete the menu ?',
            type: 'red',
            buttons: {
                Cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-red',
                },
                deleteIncome: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function () {
                        runDelete( itemID );
                    }
                },
            }
        });
  }

  function runDelete( itemID ){
    $.ajax({
            type:'POST',
            url:"{{ route('delete-menu-2') }}",
            data: { "itemId": itemID , "_token": "{{ csrf_token() }}" },
            dataType: 'JSON',
            success:function(data) {
              console.log(data);
              if(data.status == 1)
              {
                menuTable.ajax.reload();
              }
              else
              {
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
</script>s
@stop
@endsection