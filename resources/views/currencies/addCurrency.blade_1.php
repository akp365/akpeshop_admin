@extends('layout')

@section('content')

      <h4 class="page-section-heading">Add new currency</h4>
        <div class="panel panel-default">
          <div class="panel-body">

            <form id="categoryAddForm" method="POST" action="{{ route('add-new-currency') }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Title</label>
                      <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Currency Title" required>
                    </div>

                    <!-- USD Conversion Rate -->
                    <div class="form-group form-control-default required">
                      <label for="usdConversionRateInput">USD Conversion Rate</label>
                      <input type="number" step="0.01" class="form-control" id="usdConversionRateInput" name="usd_conversion_rate" value="{{ old('usd_conversion_rate') }}" placeholder="USD Conversion Rate" required>
                    </div>

                    <!-- BDT Conversion Rate -->
                    <div class="form-group form-control-default required">
                      <label for="bdtConversionRateInput">BDT Conversion Rate</label>
                      <input type="number" step="0.01" class="form-control" id="bdtConversionRateInput" name="bdt_conversion_rate" value="{{ old('bdt_conversion_rate') }}" placeholder="BDT Conversion Rate" required>
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