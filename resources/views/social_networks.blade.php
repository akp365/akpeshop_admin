@extends('layout')

@section('content')



  <h4 class="page-section-heading">Customize Social Network Images</h4>
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

                <!-- SOCIAL NETWORK IMAGE 1 START -->
                <div class="row form-group form-control-white" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Social Network Image 1</p>
                    </div>
                    <div class="col-sm-4">
                        <img id="sn_image_preview_1" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'social_networks' . '/' .  $siteLook['social_network_image_1'] }}">
                        <span class="percentage pl-2" id="pc_sn_image_1" style="display:none;">0 %</span>
                        <input type="file" name="sn_image_input_1" id="sn_image_1" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="col-sm-4 form-group form-control-default required">
                      <label for="title">Social Networks URL 1</label>
                      <input type="url" class="form-control" id="sn_url_1" name="sn_url_1" value="{{ $siteLook['social_network_url_1']  }}" placeholder="Social Network URL">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary" onclick="validateForm1();">Save</button>
                    </div>
                </div>
                <!-- SOCIAL NETWORK IMAGE 1 END -->
                <br>
                <!-- SOCIAL NETWORK IMAGE 2 START -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Social Network Image 2</p>
                    </div>
                    <div class="col-sm-4">
                        <img id="sn_image_preview_2" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'social_networks' . '/' .  $siteLook['social_network_image_2'] }}">
                        <span class="percentage pl-2" id="pc_sn_image_2" style="display:none;">0 %</span>
                        <input type="file" name="sn_image_input_2" id="sn_image_2" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="col-sm-4 form-group form-control-default required">
                      <label for="title">Social Networks URL 2</label>
                      <input type="url" class="form-control" id="sn_url_2" name="sn_url_2" value="{{ $siteLook['social_network_url_2']  }}" placeholder="Social Network URL">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary" onclick="validateForm2();">Save</button>
                    </div>
                </div>
                <!-- SOCIAL NETWORK IMAGE 2 START -->
                <br>
                <!-- SOCIAL NETWORK IMAGE 3 START -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Social Network Image 3</p>
                    </div>
                    <div class="col-sm-4">
                        <img id="sn_image_preview_3" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'social_networks' . '/' .  $siteLook['social_network_image_3'] }}">
                        <span class="percentage pl-2" id="pc_sn_image_3" style="display:none;">0 %</span>
                        <input type="file" name="sn_image_input_3" id="sn_image_3" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="col-sm-4 form-group form-control-default required">
                      <label for="title">Social Networks URL 3</label>
                      <input type="url" class="form-control" id="sn_url_3" name="sn_url_3" value="{{ $siteLook['social_network_url_3']  }}" placeholder="Social Network URL">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary" onclick="validateForm3();">Save</button>
                    </div>
                </div>
                <!-- SOCIAL NETWORK IMAGE 3 END -->
                <br>
                <!-- SOCIAL NETWORK IMAGE 4 START -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Social Network Image 4</p>
                    </div>
                    <div class="col-sm-4">
                        <img id="sn_image_preview_4" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'social_networks' . '/' .  $siteLook['social_network_image_4'] }}">
                        <span class="percentage pl-2" id="pc_sn_image_4" style="display:none;">0 %</span>
                        <input type="file" name="sn_image_input_4" id="sn_image_4" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="col-sm-4 form-group form-control-default required">
                      <label for="title">Social Networks URL 4</label>
                      <input type="url" class="form-control" id="sn_url_4" name="sn_url_4" value="{{ $siteLook['social_network_url_4']  }}" placeholder="Social Network URL">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary" onclick="validateForm4();">Save</button>
                    </div>
                </div>
                <!-- SOCIAL NETWORK IMAGE 4 END -->
                <br>
                <!-- SOCIAL NETWORK IMAGE 5 START -->
                <div class="row" style="padding-top: 5px;">
                    <div class="col-md-3">
                        <p for="english_title" class="col-sm-3">Social Network Image 5</p>
                    </div>
                    <div class="col-sm-4">
                        <img id="sn_image_preview_5" style="max-height: 131px" src="{{ env('AKP_STORAGE') . 'social_networks' . '/' .  $siteLook['social_network_image_5'] }}">
                        <span class="percentage pl-2" id="pc_sn_image_5" style="display:none;">0 %</span>
                        <input type="file" name="sn_image_input_5" id="sn_image_5" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="col-sm-4 form-group form-control-default required">
                      <label for="title">Social Networks URL 5</label>
                      <input type="url" class="form-control" id="sn_url_5" name="sn_url_5" value="{{ $siteLook['social_network_url_5']  }}" placeholder="Social Network URL 5">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary" onclick="validateForm5();">Save</button>
                    </div>
                </div>
                <!-- SOCIAL NETWORK IMAGE 5 END -->

@endsection
@section('scripts')
<script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
<script>
    
    //METHOD TO PREVIEW SOCIAL NETWORK IMAGE 1
    $('[name="sn_image_input_1"]').on('change', function() {
        readImg1(this);
    });

    function readImg1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#sn_image_preview_1').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO VALIDATE & CHANGE SOCIAL NETWORK 1
    function validateForm1() {
      let url = document.getElementById('sn_url_1').value;
      if (url == "") {
        alert("URL must be filled out");
        return false;
      }else{
        saveSocialNetwork1();
      }
    }

    function saveSocialNetwork1(){
        var pctSection = document.getElementById(`pc_sn_image_1`);
        pctSection.style.display = "block";

        var fileData = $("#sn_image_1").prop("files")[0];
        var url = document.getElementById('sn_url_1').value;

        var formData = new FormData();

        formData.append('image', fileData);
        formData.append('url', url);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save-social-network-1') }}",
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
            alert('Social Network Image 1 Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Social Network Image 1 Updating Failed');
        });
    }

    //METHOD TO PREVIEW SOCIAL NETWORK 2
    $('[name="sn_image_input_2"]').on('change', function() {
        readImg2(this);
    });

    function readImg2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#sn_image_preview_2').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO VALIDATE & CHANGE SOCIAL NETWORK 2
    function validateForm2() {
      let url = document.getElementById('sn_url_2').value;
      if (url == "") {
        alert("URL must be filled out");
        return false;
      }else{
        saveSocialNetwork2();
      }
    }

    //METHOD TO CHANGE SOCIAL NETWORK 2
    function saveSocialNetwork2(){
        var pctSection = document.getElementById(`pc_sn_image_2`);
        pctSection.style.display = "block";

        var fileData = $("#sn_image_2").prop("files")[0];
        var url = document.getElementById('sn_url_2').value;

        var formData = new FormData();
        formData.append('image', fileData);
        formData.append('url', url);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save-social-network-2') }}",
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
            alert('Social Network Image 2 Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Social Network Image 2 Updating Failed');
        });
    }

    //METHOD TO PREVIEW SOCIAL NETWORK 3
    $('[name="sn_image_input_3"]').on('change', function() {
        readImg3(this);
    });

    function readImg3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#sn_image_preview_3').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO VALIDATE & CHANGE SOCIAL NETWORK 3
    function validateForm3() {
      let url = document.getElementById('sn_url_3').value;
      if (url == "") {
        alert("URL must be filled out");
        return false;
      }else{
        saveSocialNetwork3();
      }
    }

    //METHOD TO CHANGE SOCIAL NETWORK 3
    function saveSocialNetwork3(){
        var pctSection = document.getElementById(`pc_sn_image_3`);
        pctSection.style.display = "block";

        var fileData = $("#sn_image_3").prop("files")[0];
        var url = document.getElementById('sn_url_3').value;

        var formData = new FormData();
        formData.append('image', fileData);
        formData.append('url', url);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save-social-network-3') }}",
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
            alert('Social Network Image 3 Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Social Network Image 3 Updating Failed');
        });
    }


    //METHOD TO PREVIEW SOCIAL NETWORK 4
    $('[name="sn_image_input_4"]').on('change', function() {
        readImg4(this);
    });

    function readImg4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#sn_image_preview_4').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO VALIDATE & CHANGE SOCIAL NETWORK 4
    function validateForm4() {
      let url = document.getElementById('sn_url_4').value;
      if (url == "") {
        alert("URL must be filled out");
        return false;
      }else{
        saveSocialNetwork4();
      }
    }

    //METHOD TO CHANGE SOCIAL NETWORK 4
    function saveSocialNetwork4(){
        var pctSection = document.getElementById(`pc_sn_image_4`);
        pctSection.style.display = "block";

        var fileData = $("#sn_image_4").prop("files")[0];
        var url = document.getElementById('sn_url_4').value;

        var formData = new FormData();
        formData.append('image', fileData);
        formData.append('url', url);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save-social-network-4') }}",
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
            alert('Social Network Image 4 Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Social Network Image 4 Updating Failed');
        });
    }

    //METHOD TO PREVIEW SOCIAL NETWORK 5
    $('[name="sn_image_input_5"]').on('change', function() {
        readImg5(this);
    });

    function readImg5(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#sn_image_preview_5').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    //METHOD TO VALIDATE & CHANGE SOCIAL NETWORK 5
    function validateForm5() {
      let url = document.getElementById('sn_url_5').value;
      if (url == "") {
        alert("URL must be filled out");
        return false;
      }else{
        saveSocialNetwork5();
      }
    }

    //METHOD TO CHANGE SOCIAL NETWORK IMAGE 5
    function saveSocialNetwork5(){
        var pctSection = document.getElementById(`pc_sn_image_5`);
        pctSection.style.display = "block";

        var fileData = $("#sn_image_5").prop("files")[0];
        var url = document.getElementById('sn_url_3').value;

        var formData = new FormData();
        formData.append('image', fileData);
        formData.append('url', url);
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('save-social-network-5') }}",
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
            alert('Social Network Image 5 Updated');
        }).fail(function( jqXHR, textStatus ) {
            pctSection.style.display = "none";
            alert('Social Network Image 5 Updating Failed');
        });
    }

</script>
@stop