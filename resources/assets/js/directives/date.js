/**
 * Created by Mike on 11/10/2016.
 */

Vue.directive('date', {
    twoWay: true,
    bind: function (el, binding, vnode) {
        var el = $(el);
        el.on('input change', function () {
            binding.value.obj[binding.value.prop] = el.val();
            Vue.nextTick(()=>{
                el.parent().addClass('md-has-value');
            })
        });
    },
    inserted: function (el, binding, vnode) {
        var el = $(el);
        var options = {
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: true,
            autoclose: true,
            format: 'mm/dd/yyyy'
        };

        el.datepicker(options);

        if(el.val()){
            el.parent().addClass('md-has-value');
        }
    }
});