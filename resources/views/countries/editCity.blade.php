@extends('layout')

@section('content')

      <h4 class="page-section-heading">Edit City</h4>
        <div class="panel panel-default">
          <div class="panel-body">

            <form id="cityEditForm" method="POST" action="{{ route('edit-city', ['cityId' => $city->id]) }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">City Name</label>
                      <input type="text" class="form-control" id="title" name="city_name" value="{{ $city->city_name }}" placeholder="City Name" required>
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
  $('#cityEditForm').validate({
    ignore: [], 
    rules: {
        city_name: 'required',
    },
  });
  </script>
@stop
@endsection