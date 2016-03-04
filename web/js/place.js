function initEditLogoUpload(formId){    
    id = formId;
    initFns();
    var input = $('#' + id + '_image');     
    input.change(function(){
        console.log('change-new');                        
        var preview = $('#logo-preview');
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function (event) {
            console.log('onload')
            //$('#logo_modal').modal('show');
            preview.get(0).src = event.target.result;
            $('#' + id + '-upload').hide();
            $('#' + id + '-crop').fadeIn();
            $('#' + id + '-submit').show();
            initCrop();
            
            
        };
    });
}

function initCrop(){    
    var preview = $('#logo-preview');
    console.log('iniCropFn', id, preview);
    console.log('end');
        preview.cropper({
                aspectRatio: 1 / 1,
                autoCropArea: 0.65,
//                responsive: false,
                  strict: true,
//                guides: false,
//                highlight: false,
//                dragCrop: false,
//                movable: true,
                resizable: true,
                crop: function(data){
                    console.log('crop_data',data);
                    $('#' + id + '_x').val(data.x);
                    $('#' + id + '_y').val(data.y);
                    $('#' + id + '_h').val(data.height);
                    $('#' + id + '_w').val(data.width);
                }
            });
}

function initLogoUpload(formId, fileFieldId){
    id = formId;    
    var input = $('#' + fileFieldId + '_logo_image');     
    input.change(function(){                    
        var preview = $('#logo-preview');        
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function (event) {
            console.log('onload');
            $('#logo_modal').modal('show');
            preview.get(0).src = event.target.result;
            $('#logo-upload').hide();
            $('#logo-crop').fadeIn();
            //$('#' + id + '-submit').show();            
            
        };    
    });
    
    $("#logo_modal").on("shown.bs.modal", function() {  
        initCrop();
        initFns();
        console.log($('#' + id + '_x'), 'modal Open!!!');        
    });
    
     $('#crop-button').click(function(){
        console.log('click');
        $('#logo_modal').modal('hide');
    });
}


function initFns(){       
    $('#logo_modal [data-dismiss=modal]').click(function(){
        console.log('click-dismiss-acTION')
        $('#logo-upload').show();
        $('#logo-crop').hide();
        $('#logo-submit').hide();
        $('#logo-crop-container').html('<img id="logo-preview"/>');
        //$('form[name=' + id + ']')[0].reset();
        console.log($('#' + id));
        $('#' + id).find('input').prop('value','')
    })
    
    
    
    $('#' + id + '-submit').click(function(){
	var formData = new FormData($('form[name=' + id + ']')[0]);
        var btn = $(this);
        btn.html('Uploading...');
        btn.parent().find('div.alert-danger').remove();
        console.log(formData);
	$.ajax({
		url: $(this).data('url'),
		type: 'POST',
		data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data){
                    $('#' + id + '-img').attr('src',data);
                    btn.html('Upload');
                    $('#' + id + '_modal [data-dismiss=modal]').click();
                },
            statusCode: {
               400: function(data, textStatus, jqXHR) {
                   var str = data.responseJSON.message + ':<br/>' + data.responseJSON.errors.children.id.File.errors.join('<br/>');
                   btn.parent().prepend('<div class="alert alert-danger">'+ str+'</div>');
                   btn.html('Upload');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if((xhr.status == 201) || (xhr.status == 400))
                    return;
                btn.parent().prepend('<div class="alert alert-danger">An application error as occurred ('+xhr.status + ' '+ thrownError+')</div>');
                btn.html('Upload');
            }
       });
    });
}