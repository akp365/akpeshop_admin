@extends('layout')

@section('content')

                <h4 class="page-section-heading">Category List</h4>


                <div class="panel-body buttons-spacing-vertical">
                      <p>
                        <a href="{{ route( 'add-new-category' ) }}" class="btn btn-success" style="float: right;"><i class="fa fa-plus"></i> New Category</a>
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
                          <th>Level</th>
                          <th>Parent Category</th>
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
  var datasTable;
  $(function () {   
    datasTable = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('categories') }}",
        columns: [
            {data: 'id', name: 'id', className: "centerTextInDataTable"},
            {data: 'title', name: 'title', className: "centerTextInDataTable"},
            {data: 'level', name: 'level', className: "centerTextInDataTable"},
            {data: 'parent_cat', name: 'parent_cat', className: "centerTextInDataTable"},
            {data: 'order_num', name: 'order_num', className: "centerTextInDataTable"},
            {
                data: 'status', 
                name: 'status', 
                className: "centerTextInDataTable", 
                render: function ( data, type, row ) { 
                    return data.toUpperCase(); 
                }, 
            },
            {data: 'action', name: 'action', orderable: false, searchable: false, className: "centerTextInDataTable"},
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

  //METHOD TO PUBLISH/UNPUBLISH A Category
  function changeStatus( itemID, newStatus ) {
    $.ajax({
               type:'POST',
               url:"{{ route('change-category-status') }}",
               data: {"itemId": itemID , "_token": "{{ csrf_token() }}", "status": newStatus },
               success:function(data) {
                  datasTable.ajax.reload();
               }
    });
  }

  //METHOD TO DELETE Category
  function deleteIt( itemName, itemID ){
    $.confirm({
            title: `<strong>${itemName}</strong>`,
            icon: 'fa fa-warning',
            content: 'Sure to delete the category ?',
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
            url:"{{ route('delete-category', app()->getLocale() ) }}",
            data: { "itemId": itemID , "_token": "{{ csrf_token() }}" },
            dataType: 'JSON',
            success:function(data) {
              if(data.status == 1)
              {
                datasTable.ajax.reload();
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
</script>
@stop
@endsection

