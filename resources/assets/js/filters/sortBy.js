/**
 * Created by Mike on 11/10/2016.
 */
Vue.filter('sortBy', function (arr, by, reverse, date) {
    console.log('Sorting', arr);
    var sorted = []; // use for return

    if (arr && arr.length > 0) {
        // we have items to sort

        if (!by) {
            // no sort by
            if (typeof arr[0] === 'string') {
                // array of strings
                sorted = arr.sort(function (item1, item2) {
                    if (item1 < item2) {
                        return -1;
                    }

                    if (item1 > item2) {
                        return 1;
                    }

                    return 0;
                });
            }
        } else {
            if (arr[0].hasOwnProperty(by)) {
                console.log('Sorting by '+by);
                if (date === true) {
                    //sorting by date
                    var date_format = 'MM/DD/YY';

                    if( by == 'created_at' || by == 'updated_at' || by == 'deleted_at' ){
                        date_format = null;
                    }

                    sorted = arr.sort(function (obj1, obj2) {

                        var date1 = Moment(obj1[by], date_format);
                        var date2 = Moment(obj2[by], date_format);

                        if ( Moment(date1).isBefore(date2) ) {
                            return -1;
                        }

                        if ( !Moment(date1).isBefore(date2) ) {
                            return 1;
                        }

                        return 0;
                    });
                } else {
                    sorted = arr.sort(function (obj1, obj2) {
                        var sort1 = obj1[by];
                        var sort2 = obj2[by];

                        if( typeof obj1[by] === 'string' ){
                            //if sorting by strings, mutate to lower case
                            sort1 = sort1.toLowerCase();
                            sort2 = sort2.toLowerCase();
                        }

                        if (sort1 < sort2) {
                            return -1;
                        }

                        if (sort1 > sort2) {
                            return 1;
                        }

                        return 0;
                    });
                }

            } else {
                console.log('Sort by does not exist', by);
            }
        }

        if( reverse == true ){
            //reverse the array
            sorted = sorted.reverse();
        }

    } else {
        console.log('Nothing to sort.', arr);
    }

    return sorted;

});