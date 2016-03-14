//$(document).ready(function(){
//    var ratingWidget = $('.rating-widget');
//    
//    var target = document.getElementById('rating')    
//    spinner = new Spinner();
//    $('#barRating').barrating('show', {
//        theme: 'bars-1to10',
//        onSelect:function(value, text) {
//            //alert('Selected rating: ' + value);
//            spinner.spin(target);
//            $.post(ratingWidget.data('url-rate'), {'rating' : value}, changeRating);            
//        }
//    });
//    
//    function changeRating(data, status){
//        if(data.rating){
//            var progressBar = ratingWidget.find('.progress-bar');
//            progressBar.attr('style', 'width: ' + data.rating + '%');
//            progressBar.html(data.rating + '%');
//        }
//        spinner.stop();
//    }    
//    
//    ratingWidget.find('.unset-button').click(function(e){
//        console.log('click!');
//        e.preventDefault();
//        spinner.spin(target);
//        $.post(ratingWidget.data('url-unrate'),true, changeRating);
//    });

var ratingWidget = {
    selector: '.rating-widget',
    circle: {
        selector: '#circle',
        value: 0,
        gradient: ["green", "orange"]
    },
    rangeSlider : {
        selector : '#range-slider'    
    },
    isAuth: false,
    userRating: null,
    userRatingHolder: '.userRating-holder',
    spinner:  new Spinner(),
    
    init: function(circleValue, userRating, isAuth){
        this.circle.value = circleValue;
        this.userRating = userRating;
        this.isAuth = isAuth;
                
        this.initCircleProgress();
        this.initRangeSlider();
        
        this.bind();
    },
    
    checkIsAuth : function(){
        if(!this.isAuth){
            ratingWidget.updateRangeSlider({disable: true});
        }
    },
    
    initCircleProgress : function(){
        var circle = $(ratingWidget.circle.selector).circleProgress({
            startAngle: -90 * (Math.PI/180),
            value: ratingWidget.circle.value,
            size: 1000,
            fill: {
                gradient: ratingWidget.circle.gradient
            }
        }).on('circle-animation-progress', function(event, progress, stepValue) {
            //console.log(stepValue);
            $(this).find('strong').text(String(stepValue.toFixed(2)).substr(2) + '%');
        }); 
    },
    
    initRangeSlider: function(){
        var disable = false;
        if(ratingWidget.userRating){
            disable = true;
        }
        $(ratingWidget.rangeSlider.selector).ionRangeSlider({
            from: 0,
            min:0,
            disable: disable,
            onFinish: function(data){
                console.log(data);
                var selectedValue  = data.from;
                //circle.circleProgress({ value: selectedValue });
                //spinner.spin(target);
                $.post($(ratingWidget.selector).data('url-rate'), {'rating' : selectedValue}, ratingWidget.changeRating);
                ratingWidget.toggleUserRatingBlock(selectedValue);
            }
        });
        this.checkIsAuth();
    },
    
    changeRating: function(data, status){
        if(data.rating >= 0){
            console.log('rating?!', data.rating);
            var rating = data.rating / 100;
            ratingWidget.changeCircleRating(rating);
            var disable = true;            
            if($(ratingWidget.rangeSlider.selector).prop(['disabled']))
            { 
                disable = false;
                console.log('first?');
            }
            //var slider = $(ratingWidget.rangeSlider.selector).data("ionRangeSlider");
            //console.log('disable', disable);
            //slider.update({disable: disable});
            ratingWidget.updateRangeSlider({disable: disable});
        }
        //spinner.stop();
    },
    
    changeCircleRating: function(value, disable){
        //if(data.rating){
            //var rating = data.rating / 100;
            $(ratingWidget.circle.selector).circleProgress({value: value});
        //}        
    },
    
    bind : function(){
        $(ratingWidget.selector).find('.unset-button').click(function(e){
            //console.log('click!');
            e.preventDefault();
            //spinner.spin(target);
            $.post($(ratingWidget.selector).data('url-unrate'),true, ratingWidget.changeRating);
            ratingWidget.updateRangeSlider({from:0});
            ratingWidget.toggleUserRatingBlock(0);
        });
    },
    
    updateRangeSlider : function(params){
        var slider = $(ratingWidget.rangeSlider.selector).data("ionRangeSlider");
        //console.log('disable', disable);
        slider.update(params);
    },
    
    check: function(){
//        if(ratingWidget.userRating){
//            
//        }
    },
    
    toggleUserRatingBlock: function(userrating)
    {   
        var userRatingHolder = $(ratingWidget.userRatingHolder);
        userRatingHolder.find('.rating').text(userrating);
//        if(userRatingHolder.hasClass('hide')){
//            userRatingHolder.removeClass('hide');
//        } else {
//            userRatingHolder.addClass('hide');
//        }
        $('#user-rating-collapse-btn').click();
    }
}
    
    //var ratingWidget = $('.rating-widget');
    
    
    
    //var target = document.getElementById('rating')    
    //spinner = new Spinner();
//    $('#barRating').barrating('show', {
//        theme: 'bars-1to10',
//        onSelect:function(value, text) {
//            //alert('Selected rating: ' + value);
//            spinner.spin(target);
//            $.post(ratingWidget.data('url-rate'), {'rating' : value}, changeRating);            
//        }
//    });
    
//    $('#range-slider').ionRangeSlider({
//        onFinish: function(data){
//            console.log(data);
//            var selectedValue  = data.from / 100;
//            //circle.circleProgress({ value: selectedValue });
//            spinner.spin(target);
//            $.post(ratingWidget.data('url-rate'), {'rating' : selectedValue}, changeRating);
//        }
//    });
    
//    function changeRating(data, status){
//        if(data.rating){
////            var progressBar = ratingWidget.find('.progress-bar');
////            progressBar.attr('style', 'width: ' + data.rating + '%');
////            progressBar.html(data.rating + '%');
//        circle.circleProgress({value: data.rating})
//        }
//        spinner.stop();
//    }    
//    
//    ratingWidget.find('.unset-button').click(function(e){
//        console.log('click!');
//        e.preventDefault();
//        spinner.spin(target);
//        $.post(ratingWidget.data('url-unrate'),true, changeRating);
//    });
//})