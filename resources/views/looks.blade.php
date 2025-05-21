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


                <!-- HEADER 1 , DEFAULT GREEN -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="header_1_color" class="col-sm-3">Header #1</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control minicolors minicolors-input" name="header-1" id="header_1_color" data-defaultvalue="{{ $siteLook['header_1_color'] }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveHeaderOneColor()">Save</button>
                    </div>
                </div>
                <!-- HEADER 1 END -->

                <!-- HEADER 2 , DEFAULT RED -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="header_2_color" class="col-sm-3">Header #2</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control minicolors minicolors-input" name="header-2" id="header_2_color" data-defaultvalue="{{ $siteLook['header_2_color'] }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveHeaderTwoColor()">Save</button>
                    </div>
                </div>
                <!-- HEADER 2 END -->

                <!-- CATEGORY , DEFAULT RED -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Category</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control minicolors minicolors-input" name="category" id="category_color" data-defaultvalue="{{ $siteLook['category_color'] }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveCategoryColor()">Save</button>
                    </div>
                </div>
                <!-- CATEGORY END -->


                <!-- CATEGORY-HOVER , DEFAULT GREEN -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Category Item Hover</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control minicolors minicolors-input" name="category" id="category_item_hover_color" data-defaultvalue="{{ $siteLook['category_item_hover_color'] }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveCategoryItemHoverColor()">Save</button>
                    </div>
                </div>
                <!-- CATEGORY-HOVER END -->

                <!-- FOOTER BG , DEFAULT BLUE -->
                <div class="row">
                    <div class="col-md-3">
                        <p for="header_1_color" class="col-sm-3">Footer Background</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control minicolors minicolors-input" name="footer-bg" id="footer_bg_color" data-defaultvalue="{{ $siteLook['footer_bg_color'] }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="saveFooterColor()">Save</button>
                    </div>
                </div>
                <!-- FOOTER BGEND -->


                <!-- HEADER LOGO -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Header Logo</p>
                    </div>
                    <div class="col-sm-9">
                        <img id="header_logo_preview" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'logos' . '/' .  $siteLook['header_logo'] }}">
                        <span class="percentage pl-2" id="pct_header_logo" style="display:none;">0 %</span>
                        <input type="file" name="header_logo_input" id="header_logo" accept="image/jpg, image/jpeg, image/png">
                        <button type="button" class="btn btn-primary" onclick="saveHeaderLogo()">Save</button>

                    </div>
                </div>
                <!-- HEADER LOGO END -->


                <!-- FOOTER LOGO -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Footer Logo</p>
                    </div>
                    <div class="col-sm-9">
                        <img id="footer_logo_preview" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'logos' . '/' .  $siteLook['footer_logo'] }}">
                        <span class="percentage pl-2" id="pct_footer_logo" style="display:none;">0 %</span>
                        <input type="file" name="footer_logo_input" id="footer_logo" accept="image/jpg, image/jpeg, image/png">
                        <button type="button" class="btn btn-primary" onclick="saveFooterLogo()">Save</button>

                    </div>
                </div>
                <!-- FOOTER LOGO END -->


                <!-- BACKGROUND IMAGE -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Background Image</p>
                    </div>
                    <div class="col-sm-9">
                        <img id="background_image_preview" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'bg' . '/' .  $siteLook['background_image'] }}">
                        <span class="percentage pl-2" id="pct_background_image" style="display:none;">0 %</span>
                        <input type="file" name="background_image_input" id="background_image" accept="image/jpg, image/jpeg, image/png">
                        <button type="button" class="btn btn-primary" onclick="saveBackgroundImage()">Save</button>

                    </div>
                </div>
                <!-- BACKGROUND IMAGE END -->




@endsection
@section('scripts')
<script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
<script>
    // CHANGE COLOR OF HEADER-1
    function saveHeaderOneColor(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_header_1_color') }}",
            data: {'color_code' : $('#header_1_color').val()}
        }).done(function( data ) {
            alert('Header 1 Color Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Could Not Change Header 1 Color');
        });
    }

    // CHANGE COLOR OF HEADER-2
    function saveHeaderTwoColor(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_header_2_color') }}",
            data: {'color_code' : $('#header_2_color').val()}
        }).done(function( data ) {
            alert('Header 2 Color Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Could Not Change Header 2 Color');
        });
    }

    //CHANGE COLOR OF CATEGORY DROPDOWN
    function saveCategoryColor(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_category_color') }}",
            data: {'color_code' : $('#category_color').val()}
        }).done(function( data ) {
            alert('Category Color Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Could Not Change Category Color');
        });
    }

    //CHNAGE CATEGORY-ITEM-HOVER COLOR
    function saveCategoryItemHoverColor(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_category_item_hover_color') }}",
            data: {'color_code' : $('#category_item_hover_color').val()}
        }).done(function( data ) {
            alert('Category Item Hover Color Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Could Not Change Category Item Hover Color');
        });
    }

    // CHANGE BG COLOR OF FOOTER
    function saveFooterColor(){
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_bg_color') }}",
            data: {'color_code' : $('#footer_bg_color').val()}
        }).done(function( data ) {
            alert('Footer Color Updated');
        }).fail(function( jqXHR, textStatus ) {
            alert('Could Not Change Footer Color');
        });
    }


    //METHOD TO PREVIEW HEADER LOGO
    $('[name="header_logo_input"]').on('change', function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#header_logo_preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO CHANGE HEADER LOGO
    function saveHeaderLogo(){
        var pctSection = document.getElementById(`pct_header_logo`);
        pctSection.style.display = "block";

        var fileData = $("#header_logo").prop("files")[0];
        var formData = new FormData();
        formData.append('image', fileData);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_header_logo') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            uploadProgress: function (e) {
                if (e.lengthComputable) {
                    var completedPercentage = Math.round((e.loaded * 100) / e.total);
                    pctSection.innerHTML = completedPercentage + ' %';
                }
            },
        }).done(function( data ) {
            pctSection.style.display = "none";
            alert('Header Logo Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Could Not Update Header Logo');
        });
    }


    //METHOD TO PREVIEW FOOTER LOGO
    $('[name="footer_logo_input"]').on('change', function() {
        readURL2(this);
    });

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#footer_logo_preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO CHANGE FOOTER LOGO
    function saveFooterLogo(){
        var pctSection = document.getElementById(`pct_footer_logo`);
        pctSection.style.display = "block";

        var fileData = $("#footer_logo").prop("files")[0];
        var formData = new FormData();
        formData.append('image', fileData);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_footer_logo') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            uploadProgress: function (e) {
                if (e.lengthComputable) {
                    var completedPercentage = Math.round((e.loaded * 100) / e.total);
                    pctSection.innerHTML = completedPercentage + ' %';
                }
            },
        }).done(function( data ) {
            pctSection.style.display = "none";
            alert('Footer Logo Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Could Not Update Footer Logo');
        });
    }

    //METHOD TO CHANGE BACKGROUND IMAGE
    function saveBackgroundImage(){
        var pctSection = document.getElementById(`pct_background_image`);
        pctSection.style.display = "block";

        var fileData = $("#background_image").prop("files")[0];
        var formData = new FormData();
        formData.append('image', fileData);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save_background_image') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            uploadProgress: function (e) {
                if (e.lengthComputable) {
                    var completedPercentage = Math.round((e.loaded * 100) / e.total);
                    pctSection.innerHTML = completedPercentage + ' %';
                }
            },
        }).done(function( data ) {
            pctSection.style.display = "none";
            alert('Background Image Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Could Not Update Background Image');
        });
    }

    //METHOD TO PREVIEW FOOTER LOGO
    $('[name="background_image_input"]').on('change', function() {
        readURL3(this);
    });

    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#background_image_preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
</script>
@stop