/**
 * Created by Mike on 11/7/2016.
 */
Vue.filter('niceDate', function( date ){
    var date_regex = /^\d{2}\/\d{2}\/\d{4}$/ ;

    if( date ){
        if(date_regex.test(date)){
            return date;
        } else {
            return Moment( date ).format('MM/DD/YYYY');
        }
    }
});

Vue.filter('niceDateLong', function( date ){
    if( date )
        return Moment(date).format("dddd, MMMM Do YYYY, h:mm:ss a");
});

Vue.filter('dateFromNow', function( date ){
    if( date )
        return Moment(date).fromNow();
});