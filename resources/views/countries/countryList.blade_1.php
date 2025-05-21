@extends('layout')

@section('content')

<h4 class="page-section-heading">Country List</h4>


<div class="panel-body buttons-spacing-vertical">
  <p>
    <a href="{{ route( 'add-new-country' ) }}" class="btn btn-success" style="float: right;"><i class="fa fa-plus"></i> New Country</a>
  </p>
</div>


<div class="panel panel-default">

  <!-- Data table -->
  <table class="table table-bordered data-table" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Country Code</th>
        <th>Dial Code</th>
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
  var datasTable;
  $(function() {
    datasTable = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('countries') }}",
      columns: [{
          data: 'id',
          name: 'id',
          className: "centerTextInDataTable"
        },
        {
          data: 'country_name',
          name: 'country_name',
          className: "centerTextInDataTable"
        },
        {
          data: 'country_code',
          name: 'country_code',
          className: "centerTextInDataTable",
        },
         {
          data: 'dial_code',
          name: 'dial_code',
          className: "centerTextInDataTable",
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          className: "centerTextInDataTable"
        },
      ],
    });
  });


  //METHOD TO DELETE Category
  function deleteIt(itemName, itemID) {
    $.confirm({
      title: `<strong>${itemName}</strong>`,
      icon: 'fa fa-warning',
      content: 'Sure to delete the country ?',
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
      url: "{{ route('delete-country') }}",
      data: {
        "itemId": itemID,
        "_token": "{{ csrf_token() }}"
      },
      dataType: 'JSON',
      success: function(data) {
        if (data.status == 1) {
          datasTable.ajax.reload();
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