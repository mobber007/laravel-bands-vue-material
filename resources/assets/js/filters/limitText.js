/**
 * Created by Mike on 11/9/2016.
 */
Vue.filter('limitText', function(str, len){
    if(str){
        return str.length > len ? str.substring(0,len)+'...' : str;
    }
});