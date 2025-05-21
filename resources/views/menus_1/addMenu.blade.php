@extends('layout')

@section('content')

      <h4 class="page-section-heading">Add new item to menu-one</h4>
        <div class="panel panel-default">
          <div class="panel-body">

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

            <form id="menuAddForm" method="POST" action="{{ route('new-menu-1') }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Title</label>
                      <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Menu Title" required>
                    </div>

                    <!-- ORDER NUMBER-->
                    <div class="form-group form-control-default required">
                      <label for="order_num">Menu Order#</label>
                      <input type="number" class="form-control" id="order_num" name="order_num" value="{{ old('order_num') }}" placeholder="Order Number" required>
                    </div>

                    <!-- URL -->
                    <div class="form-group form-control-default">
                      <label for="url">URL</label>
                      <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}" placeholder="Menu URL">
                    </div>

                    <!-- PAGE CONNECTION -->
                    <div class="form-group form-control-default">
                    <label for="page_id">Landing Page <font color="red">(Overrides URL)</font></label>
                        <select style="width: 100%;" data-toggle="select2" name="page_id" id="page_id" data-placeholder="Select Landing Page .." data-allow-clear="true">
                            <option></option>
                            @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == old('page_id')) selected @endif> {{ $item['title'] }} </option>
                            @endforeach    
                        </select>
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
  $('#menuAddForm').validate({
    ignore: [], 
    rules: {
        title: 'required',
        order_num: 'required',
        page_id: {
            required: function(element) {
                return "#url:blank";
            }
        },
        url: {
            required: function(element) {
                return "#page_id:blank";
            }
        },
    },
  });
  </script>
@stop
@endsection