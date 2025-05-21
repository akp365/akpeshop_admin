@extends('layout')

@section('content')

      <h4 class="page-section-heading">Edit Country</h4>
        <div class="panel panel-default">
          <div class="panel-body">

            <form id="countryEditForm" method="POST" action="{{ route('edit-country', ['countryId' => $country->id]) }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Name</label>
                      <input type="text" class="form-control" id="title" name="country_name" value="{{ $country->country_name }}" placeholder="Country Name" required>
                    </div>

                    <!-- Country Code -->
                    <div class="form-group form-control-default required">
                      <label for="countryCode">Country Code</label>
                      <input type="text" class="form-control" id="countryCode" name="country_code" value="{{ $country->country_code }}" placeholder="Country Code" required>
                    </div>

                    <!-- Dial Code -->
                    <div class="form-group form-control-default required">
                      <label for="dialCode">Dial Code</label>
                      <input type="text" class="form-control" id="dialCode" name="dial_code" value="{{ $country->dial_code }}" placeholder="Dial Code" required>
                    </div>

                    <!-- CONTROLES -->
                    <div class="row" style="text-align:center;">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>
          </div>
        </div>
<style>
    .error {
      color: red;
   }
</style>
@section('scripts')
<script>
//FORM VALIDATION SPECIALLY FOR SELECT2 COMPONENTS
  $('#categoryAddForm').validate({
    ignore: [], 
    rules: {
        title: 'required',
        order_num: 'required',
    },
  });
  </script>
@stop
@endsection