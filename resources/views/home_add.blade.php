@extends('layout')
@section('page_title', '')
@section('content')

<style>
    .ck-editor__editable_inline {
        min-height: 550px;
    }

    .error {
        color: red;
    }
</style>

<h4 class="page-section-heading">Home Add</h4>
    @for($divCount=0; $divCount<7; $divCount++)
        @php 
            $rowIndex = $divCount+1; 

            $primaryImageIndex = "home_add_image_" . $rowIndex . "_primary";
            $defaultImageIndex = "home_add_image_" . $rowIndex . "_default";

            $primaryUrlIndex = "home_add_url_" . $rowIndex . "_primary";
            $defaultUrlIndex = "home_add_url_" . $rowIndex . "_default";

            $selectedCountryIndex = "home_add_image_" . $rowIndex . "_selected_countries";

            //dd($rowIndex, $primaryImageIndex, $defaultImageIndex, $selectedCountryIndex, $primaryUrlIndex, $defaultUrlIndex);
        @endphp
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="POST" id="topBannerForm" enctype="multipart/form-data" action="{{ route('save_home_add') }}">
                    @csrf
                    <input class="form-control" required name="row_index" value="{{ $rowIndex }}" type="hidden"/>
                    <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                        <h4 class="page-section-heading"><b>Add #{{$rowIndex}}</b></h4>

                        <!-- COUNTRY SELECTION -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-control-default required">
                                    <!-- COUNTRY SELECTION -->
                                    <div class="form-group form-control-default">
                                        <!-- LABEL -->
                                        <label for="{{ $selectedCountryIndex }}">Select Countries</label>

                                        <!-- SELECTED COUNTRIES -->
                                        <select style="width: 100%;" id="{{ $selectedCountryIndex }}" name="{{$selectedCountryIndex}}[]" data-toggle="select2" data-placeholder="Select Countries ..." data-allow-clear="false" data-live-search="true" multiple="multiple" required>
                                            <option></option>
                                            <option value="99999" @if( in_array( 99999, explode(",", $siteLook[$selectedCountryIndex])) ) selected @endif>Worldwide</option>
                                            @foreach($countryList as $key => $data)
                                                <option value="{{ $data->id }}" @if( in_array( $data->id, explode(",", $siteLook[$selectedCountryIndex])) ) selected @endif>{{ $data->text }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IMAGE -->
                        <div class="row">
                            <!-- PRIMARY IMAGE -->
                            <div class="col-md-6">
                                <div class="form-group form-control-default">

                                    <!-- LABEL -->
                                    <label>Primary <small><font style="color:blue;">(for selected countries)</font></small></label>
                                    @if($rowIndex == 1)
                                        <h6>preferred dimension (1248px x 80px)</h6>
                                    @elseif($rowIndex < 6)
                                        <h6>preferred dimension (600px x 500px)</h6>
                                    @else
                                        <h6>preferred dimension (970px x 250px)</h6>
                                    @endif

                                    <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                    <div id="{{ $primaryImageIndex }}" class="akp-photo-uploader" @if(isset($siteLook[$primaryImageIndex])) data-src="{{ env('AKP_STORAGE') . 'home_add' . '/' .  $siteLook[$primaryImageIndex] }}" data-image-name="{{ $siteLook[$primaryImageIndex] }}" @endif ></div>
                                </div>
                            </div>

                            <!-- DEFAULT IMAGE -->
                            <div class="col-md-6">
                                <div class="form-group form-control-default">

                                    <!-- LABEL -->
                                    <label>Default <small><font style="color:blue;">(for other countries)</font></small></label>
                                    @if($rowIndex == 1)
                                        <h6>preferred dimension (1248px x 80px)</h6>
                                    @elseif($rowIndex < 6)
                                        <h6>preferred dimension (600px x 500px)</h6>
                                    @else
                                        <h6>preferred dimension (970px x 250px)</h6>
                                    @endif

                                    <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                    <div id="{{ $defaultImageIndex }}" class="akp-photo-uploader" @if($siteLook[$defaultImageIndex] != "NA") data-src="{{ env('AKP_STORAGE') . 'home_add' . '/' .  $siteLook[$defaultImageIndex] }}" data-image-name="{{ $siteLook[$defaultImageIndex] }}" @endif ></div>
                                </div>
                            </div>
                        </div>

                        <!-- URL -->
                        <div class="row">
                            <!-- PRIMARY -->
                            <div class="col-md">
                                <div class="form-group form-control-default">
                                    <label for="{{ $primaryUrlIndex }}">Primary URL <small><font style="color:blue;">(for selected countries)</font></small></label>
                                    <input id="{{ $primaryUrlIndex }}" type="text" name="{{ $primaryUrlIndex }}" class="form-control" placeholder="input primary url here.." value="{{ $siteLook[$primaryUrlIndex] == 'NA' ? '' : $siteLook[$primaryUrlIndex] }}">
                                </div>
                            </div>

                            <!-- DEFAULT -->
                            <div class="col-md">
                                <div class="form-group form-control-default">
                                    <label for="{{ $defaultUrlIndex }}">Default URL <small><font style="color:blue;">(for other countries)</font></small></label>
                                    <input id="{{ $defaultUrlIndex }}" type="text" name="{{ $defaultUrlIndex }}" class="form-control" placeholder="input default url here.." value="{{ $siteLook[$defaultUrlIndex] == 'NA' ? '' : $siteLook[$defaultUrlIndex] }}">
                                </div>
                            </div>
                        </div>

                        <!-- CONTROLS -->
                        <div class="row text-center nonPrintables" style="margin-top:10px;">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="#" class="btn btn-info">Cancel</a>
                        </div>
                    </div>
                </form>             
            </div>
        </div>
    @endfor


@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('akpUploader.js') }}"></script>

<!-- CKEDITOR CDN -->
<!-- <script src="{{ asset('admin_assets/ckeditor/ckeditor.js') }}"></script> -->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

    let showControls = true;
    //INITIATE PHOTO UPLOADER FOR TOP-BANNER-PRIMARY
    $('.akp-photo-uploader').akpUploader({
        showControls: showControls
    });


    $('#topBannerForm').validate({
        debug: false,
        onSubmit: true,
        ignore: [],
        rules: {
            selected_countries: {
                required: true
            },
        },
        messages: {
            selected_countries: {
                required: "This field is required"
            },
        }
    });

</script>
@endsection