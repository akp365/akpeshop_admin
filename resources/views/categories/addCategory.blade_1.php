@extends('layout')

@section('content')

      <h4 class="page-section-heading">Add new category</h4>
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

            <form id="categoryAddForm" method="POST" action="{{ route('add-new-category') }}">
                  @csrf
                    <!--  TITLE -->
                    <div class="form-group form-control-default required">
                      <label for="title">Title</label>
                      <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Category Title" required>
                    </div>

                    <!-- ORDER NUMBER -->
                    <div class="form-group form-control-default required">
                      <label for="order_num">Order#</label>
                      <input type="number" class="form-control" id="order_num" name="order_num" value="{{ old('order_num') }}" placeholder="Order Number" required>
                    </div>

                    <!-- ICON -->
                    <div class="form-group form-control-default">
                      <label for="order_num">Icon</label>
                      <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="fa icon">
                    </div>

                    <!-- PARENT CATEGORY -->
                    <div class="form-group form-control-default">
                    <label for="page_id">Parent Category <font color="blue">(optional)</font></label>
                        <select style="width: 100%;" data-toggle="select2" name="parent_id" id="parent_id" data-placeholder="Select Parent Category .." data-allow-clear="true">
                            <option></option>
                            @foreach ($parentCategories as $catItem)
                                <option value="{{ $catItem['id'] }}" @if($catItem['id'] == old('parent_id')) selected @endif> {{ $catItem['title'] }} </option>
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