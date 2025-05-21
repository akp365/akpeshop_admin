@extends('layout')

@section('content')

      <h4 class="page-section-heading">Add New Country</h4>
        <div class="panel panel-default">
          <div class="panel-body">

            <form id="countryAddForm" method="POST" action="{{ route('add-new-country') }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Country Name</label>
                      <input type="text" class="form-control" id="title" name="country_name" value="{{ old('country_name') }}" placeholder="Country Name" required>
                    </div>

                    <!-- Country Code -->
                    <div class="form-group form-control-default required">
                      <label for="countryCode">Country Code</label>
                      <input type="text" class="form-control" id="countryCode" name="country_code" value="{{ old('country_code') }}" placeholder="Country Code" required>
                    </div>

                    <!-- Dial Code -->
                    <div class="form-group form-control-default required">
                      <label for="dialCode">Dial Code</label>
                      <input type="text" class="form-control" id="dialCode" name="dial_code" value="{{ old('dial_code') }}" placeholder="Dial Code" required>
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
  $('#countryAddForm').validate({
    ignore: [], 
    rules: {
        country_name: 'required',
        country_code: 'required',
        dial_code: 'required'
    },
  });
  </script>
@stop
@endsection