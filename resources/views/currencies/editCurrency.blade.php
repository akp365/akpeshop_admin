@extends('layout')

@section('content')

      <h4 class="page-section-heading">Edit Currency</h4>
        <div class="panel panel-default">
          <div class="panel-body">

            <form id="currencyEditForm" method="POST" action="{{ route('edit-currency', ['currencyId' => $currency->id]) }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Title</label>
                      <input type="text" class="form-control" id="title" name="title" value="{{ $currency->title }}" placeholder="Category Title" required>
                    </div>

                    <!-- USD CONVERSION RATE -->
                    <div class="form-group form-control-default required">
                      <label for="usdConversionRate">USD Conversion Rate</label>
                      <input type="number" step="0.01" class="form-control" id="usdConversionRate" name="usd_conversion_rate" value="{{ $currency->usd_conversion_rate }}" placeholder="USD Conversion Rate" required>
                    </div>

                    <!-- BDT CONVERSION RATE -->
                    <div class="form-group form-control-default required">
                      <label for="bdtConversionRateInput">BDT Conversion Rate</label>
                      <input type="number" step="0.01" class="form-control" id="bdtConversionRateInput" name="bdt_conversion_rate" value="{{ $currency->bdt_conversion_rate }}" placeholder="BDT Conversion Rate" required>
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