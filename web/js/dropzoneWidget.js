Dropzone.autoDiscover = false;
        
        $(document).ready(function() {            
            dropzone.init();                     
        });
        
        var dropzone = {
            selector: '.dropzone',
            removeButtonSelector: '.dz-remove',
            inputSelector: '.dropzone_input',            
            
            init: function(){
                this.bind();                
                $(this.selector).each(function(){               
                    dropzone.add($(this));
                });
            },
            
            bind: function(){
                $(dropzone.removeButtonSelector).click(function(){                    
                    var id = $(this).data('id');                    
                    dropzone.pullId($(this).closest(dropzone.selector), id);
                    $(this).closest('.dz-preview').remove();                             
                   
                });
            },
            
            add: function(element){console.log(element);
                element.dropzone({
                    url: "/apis/v1/places/halls/images",                    
                    addRemoveLinks: true,
                    paramName: "image",
                    init: function() {                        
                        
                        this.on("success", function(file,data) {                                                          
                            var elem = $(file.previewElement);                                                
                            var removeButton  = elem.find('.dz-remove');                             
                            removeButton.data('id', data.id);                                                          
                            dropzone.pushId($('#' + this.element.id), data.id);                                                
                        });
                        
                        this.on("error", function(){
                            
                        });

                        this.on("removedfile", function(file) {                             
                            dropzone.pullId($('#' + this.element.id), $(file._removeLink).data('id'));
                        });
                    }
                });
            },
            
            pushId: function(element, id){               
                var items = element.find(dropzone.inputSelector).attr('value').split(',');
                items.push(id);
                element.find(dropzone.inputSelector).attr('value',items.join(','));                
            },
            
            pullId: function(element, id) {                                
                var items = element.find(dropzone.inputSelector).attr('value').split(',');                
                var index =  items.indexOf(id.toString());                
                items.splice(index, 1);                
                element.find(dropzone.inputSelector).attr('value',items.join(','));                                                
            }   
        }