@extends('layout')

@section('content')

<h4 class="page-section-heading">Currency List</h4>


<div class="panel-body buttons-spacing-vertical">
  <p>
    <a href="{{ route( 'add-new-currency' ) }}" class="btn btn-success" style="float: right;"><i class="fa fa-plus"></i> New Currency</a>
  </p>
</div>


<div class="panel panel-default">

  <!-- Data table -->
  <table class="table table-bordered data-table" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>BDT Conversion Rate</th>
        <th>USD Conversion Rate</th>
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
  $(function() {
    datasTable = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('currencies') }}",
      columns: [{
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
          data: 'bdt_conversion_rate',
          name: 'bdt_conversion_rate',
          className: "centerTextInDataTable"
        },
        {
          data: 'usd_conversion_rate',
          name: 'usd_conversion_rate',
          className: "centerTextInDataTable"
        },
        {
          data: 'status',
          name: 'status',
          className: "centerTextInDataTable",
          render: function(data, type, row) {
            return data.toUpperCase();
          },
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false,
          className: "centerTextInDataTable"
        },
      ],
      "createdRow": function(row, data, index) {
        if (data.status == 'active') {
          $('td', row).eq(5).addClass('greenText');
        } else {
          $('td', row).eq(5).addClass('redText');
        }
      },
    });
  });

  //METHOD TO PUBLISH/UNPUBLISH A Category
  function changeStatus(itemID, newStatus) {
    $.ajax({
      type: 'POST',
      url: "{{ route('change-currency-status') }}",
      data: {
        "itemId": itemID,
        "_token": "{{ csrf_token() }}",
        "status": newStatus
      },
      success: function(data) {
        datasTable.ajax.reload();
      }
    });
  }

  //METHOD TO DELETE Category
  function deleteIt(itemName, itemID) {
    $.confirm({
      title: `<strong>${itemName}</strong>`,
      icon: 'fa fa-warning',
      content: 'Sure to delete the currency ?',
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
      url: "{{ route('delete-currency') }}",
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