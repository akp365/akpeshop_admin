@extends('layout')

@section('content')



  <h4 class="page-section-heading">Customize Site Look</h4>
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


                <!-- FOOTER TEXT 1 START -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="header_1_color" class="col-sm-3">Footer Text Row #1</p>
                    </div>
                    <div class="form-group form-control-default col-sm-6">
                      <label for="footer_text_1">Footer Text 1</label>
                      <input type="text" class="form-control" id="footer_text_1" name="footer_text_1" value="{{$siteLooks['footer_text_1']}}" placeholder="">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveFooterTextOne()">Update</button>
                    </div>
                </div>
                <!-- FOOTER TEXT 1 END -->
                <hr style="width:100%;text-align:left;margin-left:0">
                <br>
                <!-- FOOTER TEXT 2 START -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="footer_text_2" class="col-sm-3">Footer Text Row #2</p>
                    </div>
                    <div class="form-group form-control-default col-sm-6">
                      <label for="footer_text_2">Footer Text 2</label>
                      <input type="text" class="form-control" id="footer_text_2" name="footer_text_2" value="{{$siteLooks['footer_text_2']}}" placeholder="">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveFooterTextTwo()">Update</button>
                    </div>
                </div>
                <!-- FOOTER TEXT 2 END -->
                <hr style="width:100%;text-align:left;margin-left:0">
                <br>
                <!-- FOOTER TEXT 3 START -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="footer_text_3" class="col-sm-3">Footer Text Row #3</p>
                    </div>
                    <div class="form-group form-control-default col-sm-6">
                      <label for="footer_text_3">Footer Text 3</label>
                      <input type="text" class="form-control" id="footer_text_3" name="footer_text_3" value="{{$siteLooks['footer_text_3']}}" placeholder="">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveFooterTextThree()">Update</button>
                    </div>
                </div>
                <!-- FOOTER TEXT 3 END -->
                <hr style="width:100%;text-align:left;margin-left:0">
                <br>
                <!-- COPYRIGHT START -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="copyright" class="col-sm-3">Copyright</p>
                    </div>
                    <div class="form-group form-control-default col-sm-6">
                      <label for="copyright">Copyright</label>
                      <input type="text" class="form-control" id="copyright" name="copyright" value="{{$siteLooks['copyright']}}" placeholder="">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="copyright()">Update</button>
                    </div>
                </div>
                <!-- COPYRIGHT END -->
                <hr style="width:100%;text-align:left;margin-left:0">
                <br>
                <!-- FOOTER ADDRESS START -->
                <div class="row">
                    <div class="col-md-2">
                        <p for="copyright" class="col-sm-2">Footer Address</p>
                    </div>
                    <div class="col-md-8">
                        <textarea name="footer_address" value="{{ old('footer_address') }}" class="form-control required" id="footer_address" required>{{$siteLooks['footer_address']}}</textarea>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" onclick="footerAddress()">Update</button>
                    </div>
                </div>
                <!-- FOOTER ADDRESS END -->


@endsection
@section('scripts')
<script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
<script>
    //CHANGE FOOTER TEXT 1
    function saveFooterTextOne(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_text_1') }}",
            data: {'footer_text_1' : $('#footer_text_1').val()}
        }).done(function( data ) {
            alert('Footer Text 1 Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Footer Text 1 Update Failed');
        });
    }

    //CHANGE FOOTER TEXT 2
    function saveFooterTextTwo(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_text_2') }}",
            data: {'footer_text_2' : $('#footer_text_2').val()}
        }).done(function( data ) {
            alert('Footer Text 2 Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Footer Text 2 Update Failed');
        });
    }

    //CHANGE FOOTER TEXT 3
    function saveFooterTextThree(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_text_3') }}",
            data: {'footer_text_3' : $('#footer_text_3').val()}
        }).done(function( data ) {
            alert('Footer Text 3 Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Footer Text 3 Update Failed');
        });
    }

    //CHANGE COPYRIGHT
    function copyright(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('copyright') }}",
            data: {'copyright' : $('#copyright').val()}
        }).done(function( data ) {
            alert('Copyright Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Copyright Update Failed');
        });
    }

    //CHANGE FOOTER ADDRESS
    function footerAddress() {
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_address') }}",
            data: {'footer_address' : $('#footer_address').val()}
        }).done(function( data ) {
            alert('Footer Address Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Footer Address Update Failed');
        });
    }

    //INITIATE RICH-TEXT-EDITOR FOR FOOTER ADDRESS INPUT
    $('#footer_address').summernote({ height: 300  });

</script>
@stop