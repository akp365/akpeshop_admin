(function ($) {
    $.fn.akpUploader = function (options = {showControls:true, iconsOnly:false }) {
        return this.each(function() {
            //SET ID
            let _id = $(this).attr('id');
            let _existingImage = $(this).data('src');
            let _existingImageName = $(this).data('image-name');
            let _imageExists = _existingImage ? "yes" : "no";

            //-- APPEND MAIN DOM << ICONS ONLY >>
            if(options && options.iconsOnly)
            {
                $(this).append(`
                            <div id="akpWrapper_${_id}">
                                <div id="imageHolder_${_id}" class="row well text-center">
                                    <img id="preview_${_id}" class="img-fluid" style="max-height:100px;margin-bottom:10px;" src="${_existingImage ?? ''}"/>
                                </div>
                            </div>
                        `);
                
                if(options.showControls)
                {
                    $(`#akpWrapper_${_id}`).append(`
                                <div id="controls_${_id}" class="row text-center">
                                    <input type="file" id="fileInput_${_id}" name="photo_${_id}" style="visibility:hidden;">
                                    <input type="hidden" id="existingImageDeleted_${_id}" name="existing_image_deleted_${_id}" value="no">
                                    <input type="hidden" id="existingImage_${_id}" name="image_exists_${_id}" value="yes">
                                    <input type="hidden" id="existingImageName_${_id}" name="existing_image_name_${_id}" value="${ _existingImageName ?? ''}">

                                    <button id="uploadBtn_${_id}" type="button" class="btn btn-info"><i class="fa fa-cloud-upload"></i></button>
                    
                                    <button id="deleteBtn_${_id}" type="button" class="btn btn-danger" disabled><i class="fa fa-trash"></i></button>            
                                </div>
                    `);
                }
            } 
            else{
                //-- APPEND MAIN DOM
                $(this).append(`
                            <div id="akpWrapper_${_id}">
                                <div id="imageHolder_${_id}" class="row well text-center">
                                    <img id="preview_${_id}" class="img-fluid" style="max-height:100px;margin-bottom:10px;" src="${_existingImage ?? ''}"/>
                                </div>
                            </div>
                        `);

                if(options.showControls)
                {
                    $(`#akpWrapper_${_id}`).append(`
                                <div id="controls_${_id}" class="row well text-center">
                                    <input type="file" id="fileInput_${_id}" name="photo_${_id}" style="visibility:hidden;">
                                    <input type="hidden" id="existingImageDeleted_${_id}" name="existing_image_deleted_${_id}" value="no">

                                    <button id="uploadBtn_${_id}" type="button" class="btn btn-info">Upload New <i class="fa fa-cloud-upload"></i></button>
                    
                                    <button id="deleteBtn_${_id}" type="button" class="btn btn-danger" disabled>Delete <i class="fa fa-trash"></i></button>            
                                </div>
                    `);
                }
            }

            //-- IF DOM HAS AN EXISTING IMAGE, ENABLE THE << DELETE >> BUTTON
            if (_existingImage) {
                $(`#deleteBtn_${_id}`).attr('disabled', false);
            }

            //-- IMAGE ATTACHED
            $(`#uploadBtn_${_id}`).on('click', function () {
                $(`#fileInput_${_id}`).trigger('click');
            });


            //-- DELETE EXISTING IMAGE
            $(`#deleteBtn_${_id}`).on('click', function () {
                $(`#preview_${_id}`).remove();
                $(`#imageHolder_${_id}`).append(`<img id="preview_${_id}" class="img-fluid" style="max-height:100px;margin-bottom:10px;" src=""/>`);

                $(`#fileInput_${_id}`).val('');
                $(`#deleteBtn_${_id}`).attr('disabled', true);

                $(`#existingImageDeleted_${_id}`).val("yes");

            });

            //-- PROCESS FILE ATTACHMENT
            $(`#fileInput_${_id}`).change(function (e) {
                if (this.files.length > 0) {
                    var file = this.files[0];
                    readUrl(e.currentTarget);
                    
                    $(`#deleteBtn_${_id}`).attr('disabled', false);
                }
            });

            //-- SET PREVIEW
            function readUrl(input) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(`#preview_${_id}`).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }

        });
    }

    $.fn.value = function () {
        //SET ID
        var _id = $(this).attr('id');

        let fileInput = $(`#fileInput_${_id}`)[0];
        if (fileInput.files.length > 0) {
            return fileInput.files[0];
        }

        return null;
    };


    $.fn.imageDeleted = function () {
        return _existingImageDeleted;
    }
}(jQuery));