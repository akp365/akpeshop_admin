@extends('layout')

@section('content')



  <h4 class="page-section-heading">Site settings</h4>
  <div class="panel panel-default">
    <div class="panel-body">
    @csrf
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


    <!-- DEFAULT CURRENCY -->
    <div class="row">
      <div class="col-md-3">
      <p for="default_currency" class="">Default currency</p>
      </div>
      <div class="col-sm-6">
      <select style="width: 100%;" data-toggle="select2" name="default_currency" id="default_currency"
        data-placeholder="Select Default Currency .." data-allow-clear="false">
        <option></option>
        @foreach ($currencies as $item)
      <option value="{{ $item['id'] }}" @if($item['title'] == $siteSettings['default_currency']) selected @endif>
      {{ $item['title'] }} </option>
    @endforeach    
      </select>
      </div>
      <div class="col-md-3">
      <button type="button" class="btn btn-primary" onclick="saveDefaultCurrency()">Save</button>
      </div>
    </div>

    <!-- DEFAULT CURRENCY -->



    <div class="row" style="margin-top: 20px;">
      <form action="{{route('set_cod')}}" method="POST">
        @csrf
      <div class="col-md-3">
        <p for="default_currency" class="">COD Charge (Percent)</p>
      </div>
      <div class="col-sm-6">
        <input id="maximum_spend_input" type="number" placeholder="COD Charge" style="width: 100%;"
        class="form-control" name="cod_as_percent" value="{{$codCharge->cod_charge_as_percent}}" required=""
        aria-required="true">
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary"">Save</button>
      </div>
      </form>
    </div>
@endsection
    @section('scripts')
    <script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
    <script>
      // CHANGE COLOR OF HEADER-1
      function saveDefaultCurrency() {
        $.ajax({
          method: 'POST',
          dataType: 'json',
          url: "{{ route('save_default_currency') }}",
          data: { 'default_currency': $('#default_currency').select2('data').text, 'c_id': $('#default_currency').select2('data').id }
        }).done(function (data) {
          alert('Default currency Updated');
        }).fail(function (jqXHR, textStatus) {
          alert('Could Not Change Default currency');
        });
      }
    </script>
    @stop