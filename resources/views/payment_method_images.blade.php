@extends('layout')

@section('content')

 <div class="panel panel-default">
    <div class="panel-body">
        <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
            <h4 class="page-section-heading">Payment Methods</h4>
            <form id="paymentMethodForm" action="{{ route('save_payment_methods') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div id="wrapperDiv" class="row">
                    @foreach($paymentMethods as $key => $paymentMethod)
                        <div id="{{ 'paymentMethod_' . $key }}" class="col-md-4">
                            <span id='remove_{{$key}}' class="remove_div" onclick='deletePm("{{ $paymentMethod->id }}")'>X</span>
                            <div class="form-group form-control-default" style="height:250px;">
                                <!-- LABEL -->
                                <label>Payment Method # {{ $key+1 }}</label>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="paymentMethodImage_{{$key}}" class="akpUploader" data-src="{{ env('AKP_STORAGE') . 'payment_methods/' . $paymentMethod->footer_payment_image }}" data-image-name="{{ $paymentMethod->footer_payment_image }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CONTROLS -->
                <div class="row text-center nonPrintables" style="margin-top:10px;">
                    <button class="btn btn-info" id="addMorePm" onclick="addNewPm()" type="button"><i class="fa fa-plus"></i> Add new</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>

            </form>

            <div class="row text-center">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('akpUploader.js') }}"></script>
<script>
    var originalData;
    $(document).ready(function() {
        originalData = $('#paymentMethodForm').serialize();
    });

    $('.akpUploader').akpUploader({
        iconsOnly: true,
        showControls: true
    });

    function addNewPm(){
        let idIndex = 0;

        if($('#wrapperDiv').children().length > 0){
            idIndex = parseInt(($('#wrapperDiv').children().last().attr('id')).split("_")[1]) + 1;
            console.log("last index: " + idIndex);
        }

        $('#wrapperDiv').append(`<div id="paymentMethod_${idIndex}" class="col-md-4">
                            <div class="form-group form-control-default" style="height:250px;">
                                <!-- LABEL -->
                                <label>Payment Method # ${idIndex}</label>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="paymentMethodImage_${idIndex}" class="akpUploader"></div>
                            </div>
                        </div>`);

        $(`#paymentMethodImage_${idIndex}`).akpUploader({
            iconsOnly: true,
            showControls: true
        });
    }

    //APPEND SOME NEW FIELDS UPON FORM SUBMIT
    $('#paymentMethodForm').submit(function(e) {
        //PREVENT FORM SUBMIT
        //e.preventDefault();

        //GRAB CURRENT FORM DATA
        newData = $('#paymentMethodForm').serialize();

        if (originalData == newData) {
            $.alert({
                title: 'Warning',
                icon: 'fa fa-warning',
                content: "No changes to save",
                type: 'red'
            });

            return false;
        } else {
            //APPEND STOCK VARIETY COUNT WITH FORM DATA
            $("<input />").attr("type", "hidden")
                .attr("name", "pm_count")
                .attr("value", $('#wrapperDiv').children().length)
                .appendTo("#paymentMethodForm");

            return true;
        }
    });


    function deletePm(pmId){
     $.ajax({
            method: 'POST',
            dataType: 'json',
            url: "{{ route('delete-pm') }}",
            data: {'id' : pmId}
        }).done(function( data ) {
            console.log(data);
            if(data.status == 1) location.reload();
        }).fail(function( jqXHR, textStatus ) {
            alert('Backend error');
        });
    }
</script>
@stop