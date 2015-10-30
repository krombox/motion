$(document).ready(function(){
    
    
    $('.likes-widget .like-vote-buttons a').click(function(e){
        e.preventDefault();        
        var elem = $(this).parents('.likes-widget');
        //console.log(elem);
        var url = $(this).prop('href');        
        var likeType = $(this).data('like-type');
        var likeValue = $(this).data('like-value');
        //console.log('liketype-',likeType);        
        elem.find('.place-user-like').removeClass('active');
        /*show block depend on vote button*/
        $('.place-user-like.' + likeType).addClass('active');
                
        if($(this).hasClass('decrease')){            
            elem.find('.progress-info span.' + likeValue).html(parseInt($('.progress-info span.' + likeValue).html(), 10)-1);            
        }
        else{
            elem.find('.progress-info span.' + likeValue).html(parseInt($('.progress-info span.' + likeValue).html(), 10)+1);
            
        }
        
        var persent = 100;
        var positiveLikesCount = parseInt(elem.find('.progress-info span.positive').html());
        var negativeLikesCount = parseInt(elem.find('.progress-info span.negative').html());
        var totalLikesCount = positiveLikesCount + negativeLikesCount;
        if(totalLikesCount) persent = positiveLikesCount / totalLikesCount * 100;
        //console.log(positiveLikesCount, negativeLikesCount, totalLikesCount, persent);
        elem.find('.progress-bar').css('width', persent + '%');
        
        ajaxCall('get',url, null, function(data){
            console.log(data);            
//            console.log('dd',$('.like-vote-buttons'));         
//            console.log($('.place-user-like.' + likeType));
//            console.log('.place-user-like.' + likeType);
            
        });
    });
        
});

function ajaxCall(method, url, data, callback){
    $.ajax({
         url:url,
         type: method,
         data: data,
         success: callback
    });
}
