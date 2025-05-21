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

<h4 class="page-section-heading">Banner & Adds</h4>
<div class="panel panel-default">
    <div class="panel-body">
            <!-- TOP BANNER -->
            <form method="POST" id="topBannerForm" enctype="multipart/form-data" action="{{ route('save_top_banner') }}">
                @csrf
                <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                    <h4 class="page-section-heading"><b>Top Banner</b></h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-control-default required">
                                <!-- SHIPPING COUNTRY SELECTION -->
                                <div class="form-group form-control-default">
                                    <!-- LABEL -->
                                    <label for="topBannerSelectedCountries">Select Countries</label>

                                    <!-- SELECTED COUNTRIES -->
                                    <select style="width: 100%;" id="topBannerSelectedCountries" name="selected_countries[]" data-toggle="select2" data-placeholder="Select Countries ..." data-allow-clear="false" data-live-search="true" multiple="multiple" required>
                                        <option></option>
                                        <option value="99999" @if( in_array( 99999, explode(",", $siteLook['top_banner_selected_countries'])) ) selected @endif>Worldwide</option>
                                        @foreach($countryList as $key => $data)
                                            <option value="{{ $data->id }}" @if( in_array( $data->id, explode(",", $siteLook['top_banner_selected_countries'])) ) selected @endif>{{ $data->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WHEN MODE IS 'IMAGE' -->
                    <div class="row" id="topBannerImageDiv">
                        <div class="col-md-6">
                            <div class="form-group form-control-default">

                                <!-- LABEL -->
                                <label for="brandLogoFileInput">Primary <small><font style="color:blue;">(for selected countries)</font></small></label>
                                <h6>preferred dimension (1248px x 80px)</h6>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="topBannerPrimary" class="akp-photo-uploader" @if(isset($siteLook['top_banner_primary'])) data-src="{{ env('AKP_STORAGE') . 'banners' . '/' .  $siteLook['top_banner_primary'] }}" data-image-name="{{ $siteLook['top_banner_primary'] }}" @endif ></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-control-default">

                                <!-- LABEL -->
                                <label for="brandLogoFileInput">Default <small><font style="color:blue;">(for other countries)</font></small></label>
                                <h6>preferred dimension (1248px x 80px)</h6>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="topBannerDefault" class="akp-photo-uploader" @if($siteLook['top_banner_default'] != "NA") data-src="{{ env('AKP_STORAGE') . 'banners' . '/' .  $siteLook['top_banner_default'] }}" data-image-name="{{ $siteLook['top_banner_default'] }}" @endif ></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="topBannerUrlDiv">
                        <div class="col-md">
                            <div class="form-group form-control-default">
                                <label for="brandLogoFileInput">Primary URL <small><font style="color:blue;">(for selected countries)</font></small></label>
                                <input type="text" name="url_topBannerPrimary" class="form-control" placeholder="input primary url here.." value="{{ $siteLook['top_banner_primary_url'] == 'NA' ? '' : $siteLook['top_banner_primary_url']  }}">
                            </div>
                        </div>

                        <div class="col-md">
                            <div class="form-group form-control-default">
                                <label for="brandLogoFileInput">Default URL <small><font style="color:blue;">(for other countries)</font></small></label>
                                <input type="text" name="url_topBannerDefault" class="form-control" placeholder="input default url here.." value="{{ $siteLook['top_banner_default_url'] == 'NA' ? '' : $siteLook['top_banner_default_url'] }}">
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