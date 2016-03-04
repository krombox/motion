function getTimeRemaining(endtime){
    var t = Date.parse(endtime) - Date.parse(new Date());
    var seconds = Math.floor( (t/1000) % 60 );
    var minutes = Math.floor( (t/1000/60) % 60 );
    var hours = Math.floor( (t/(1000*60*60)) % 24 );
    var fullHours = Math.floor( (t/(1000*60*60)) );
    var days = Math.floor( t/(1000*60*60*24) );
    return {
      'total': t,
      'days': days,
      'hours': hours,
      'fullHours': fullHours < 10 ? '0' + fullHours : fullHours,
      'minutes': minutes < 10 ? '0' + minutes : minutes ,
      'seconds': seconds < 10 ? '0' + seconds : seconds
    };
}