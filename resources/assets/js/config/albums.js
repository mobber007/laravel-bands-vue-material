/**
 * Created by Mike on 12/17/2016.
 */

const band_label = 'Band';
const album_label = 'Album';

export default {
    url: '/albums',
    edit_route: 'edit_album',
    filter: null,
    sort: 'name',
    sort_dir: 'asc',
    pagination: {
        per_page: 10
    },
    labels: {
        title: album_label,
        search: 'Search...',
        add: 'New ' + album_label,
        edit: 'Edit ' + album_label,
        delete: 'Delete ' + album_label,
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
     *      list_order?: integer, (don't include this prop if you want to hide the column on the list page)
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
     *      list_order?: integer,
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
    fields: [

        [
            {
                required:true,
                key: 'name',
                label: album_label + ' Name',
                type: 'text',
                list_order: 10,
                flex:{
                    default: 50,
                    small:100
                }
            },
            {
                required:true,
                key: 'band_id',
                label: band_label,
                type: 'select',
                fetch: {
                    url: '/bands?per_page=-1'
                },
                option_value: 'id',
                option_label: 'name',
                flex:{
                    default: 50,
                    small:100
                }
            }
        ],
        [
            {
                key: 'recorded_date',
                label: 'Recorded',
                type: 'date',
                list_order: 30,
                flex:{
                    default: 40,
                    small:100,
                }
            },
            {
                key: 'release_date',
                label: 'Released',
                type: 'date',
                list_order: 40,
                flex:{
                    default: 40,
                    small:100
                }
            },
            {
                key: 'number_of_tracks',
                label: '# Tracks',
                type: 'number',
                list_order: 50,
                flex:{
                    default: 20,
                    small:100
                }
            },
        ],
        [
            {
                key: 'label',
                label: 'Record Label',
                type: 'text',
                list_order: 60,
                flex:{
                    default: 33,
                    small:100
                }
            },
            {
                key: 'producer',
                label: 'Producer',
                type: 'text',
                list_order: 70,
                flex:{
                    default: 33,
                    small:100
                }
            },
            {
                key: 'genre',
                label: 'Genre',
                type: 'text',
                list_order: 80,
                flex:{
                    default: 33,
                    small:100
                }
            },
        ],

        //dont include these in form
        {key: 'band_name', label: band_label + ' Name', type: 'text', list_order:20},
        {key: 'created_at', label: 'Created', type: 'date', list_order:90},
        {key: 'updated_at', label: 'Updated', type: 'date', list_order:100}
    ]
};