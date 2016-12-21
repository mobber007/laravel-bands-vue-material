/**
 * Created by Mike on 12/17/2016.
 */

const band_label = 'Band';

export default {
    url: '/bands',
    edit_route: 'edit_band',
    filter: null,
    sort: 'name',
    sort_dir: 'asc',
    pagination: {
        per_page: 10
    },
    labels: {
        title: band_label,
        search: 'Search...',
        add: 'New ' + band_label,
        edit: 'Edit ' + band_label,
        delete: 'Delete ' + band_label,
    },

    /**
     * The fields array is what the form and the list view
     * runs off of. Use this to lay out the items properties.
     *
     * The structure is an array of arrays (rows) of objects (form fields)
     * Columns you don't want displayed should be a top level object in the array
     *
     * Form fields can be either text, number, date, or select dropdowns
     *
     * For text, number and date the format is the same
     * {
     *      key: string,
     *      label: string,
     *      type: 'text' | 'number' | 'date',
     *      list_order: integer, (don't include this prop if you want to hide the column on the list page)
     *      required?: boolean
     *      flex: { (these are the flex values for the fields at each screen size)
     *          default: integer,
     *          xsmall?: integer,
     *          small?: integer,
     *          medium?: integer,
     *          large?: integer,
     *          xlarge?: integer
     *      }
     *  }
     *
     *  For selects the format is:
     *  {
     *      key: string,
     *      label: string,
     *      type: 'select',
     *      list_order: integer,
     *      required?: boolean,
     *      option_val: string,
     *      option_label: string,
     *      options?: Object[]
     *      fetch?: string (url to fetch options from)
     *      flex: { ...
     *  }
     *  Notice the options and fetch properties are optional, but at least one must be present.
     *  The option_val and option_label are the property keys in each option object to use for
     *  the values and labels of the options.
     *
     */
    fields:[
        [
            {
                key: 'name',
                label: band_label + ' Name',
                type: 'text',
                list_order:10,
                required:true,
                flex:{
                    default:50,
                    small:100,
                    xsmall:100,
                }
            },
            {
                key: 'website',
                label: 'Website',
                type: 'text',
                list_order:20,
                flex:{
                    default:50,
                    small:100,
                    xsmall:100
                }
            },
        ],
        [
            {
                key: 'start_date',
                label: 'Founded',
                type: 'date',
                list_order:30,
                flex:{
                    default:50,
                    small:100,
                    xsmall:100
                }
            },
            {
                key: 'still_active',
                label: 'Active?',
                type: 'select',
                boolean: true,
                yes: 'Active',
                no: 'Inactive',
                option_value: 'val',
                option_label: 'label',
                options:[
                    {val:1, label: 'Active'},
                    {val:0, label: 'Inactive'}
                ],
                list_order:40,
                flex:{
                    default:50,
                    small:100,
                    xsmall:100
                }
            },
        ],
        {key: 'created_at', label: 'Created', type: 'date', list_order:50, filter: 'dateFromNow'},
        {key: 'updated_at', label: 'Updated', type: 'date', list_order:60},
    ]
};