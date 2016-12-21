/**
 * Created by Mike on 12/16/2016.
 */

//Route Components
import BandsList from './components/bands/list.vue';
import AlbumsList from './components/albums/list.vue';
import BandForm from './components/bands/form.vue';
import AlbumForm from './components/albums/form.vue';


export default
[
    {path: '/', redirect: '/bands'},
    {path:'/home', redirect: '/bands'},

    //Bands
    {path: '/bands', component: BandsList, name: 'bands'},
    {path: '/bands/add', component: BandForm, name:'add_band', meta: {title: 'Add Band'}},
    {path: '/bands/edit/:id', component: BandForm, name:'edit_band', meta: {title: 'Edit Band'}},

    //Albums
    {path: '/albums', component: AlbumsList, name: 'albums'},
    {path: '/albums/add', component: AlbumForm, name:'add_album', meta: {title: 'Add Album'}},
    {path: '/albums/edit/:id', component: AlbumForm, name:'edit_album', meta: {title: 'Edit Album'}},

    //Catch all
    {path: '/**', redirect: '/bands'}
]
