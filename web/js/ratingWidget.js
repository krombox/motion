$(document).ready(function(){
    var ratingWidget = $('.rating-widget');
    
    var target = document.getElementById('rating')    
    spinner = new Spinner();
    $('#barRating').barrating('show', {
        theme: 'bars-1to10',
        onSelect:function(value, text) {
            //alert('Selected rating: ' + value);
            spinner.spin(target);
            $.post(ratingWidget.data('url-rate'), {'rating' : value}, changeRating);            
        }
    });
    
    function changeRating(data, status){
        if(data.rating){
            var progressBar = ratingWidget.find('.progress-bar');
            progressBar.attr('style', 'width: ' + data.rating + '%');
            progressBar.html(data.rating + '%');
        }
        spinner.stop();
    }    
    
    ratingWidget.find('.unset-button').click(function(e){
        console.log('click!');
        e.preventDefault();
        spinner.spin(target);
        $.post(ratingWidget.data('url-unrate'),true, changeRating);
    });
})