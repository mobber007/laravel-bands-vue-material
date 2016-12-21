<template>
    <div v-if="album.hasOwnProperty('name')">
        <dynamic-form form_type="albums" :item="album" @saved="saveAlbum" @cancel="$router.push({name:'albums'})"></dynamic-form>
    </div>
</template>

<script>
    import DynamicForm from '../form/dynamicForm.vue';
    export default {
        http:{
            headers:{
                'X-CSRF-TOKEN': Laravel.csrfToken
            }
        },
        components:{
            DynamicForm
        },
        data(){
            return {
                album:{}
            }
        },
        created(){
            this.spinner(true);
            if(this.$route.name == 'add_album'){
                //adding a new album
                //we init with a blank album form
                this.album = {
                    name: null,
                    recorded_date: null,
                    release_date: null,
                    number_of_tracks: null,
                    label: null,
                    producer: null,
                    genre: null,
                };
                this.spinner(false);
            } else {
                //editing a album
                //we init the form with album from the server
                this.$http.get(this.$root.config.albums.url+'/'+this.$route.params.id)
                    .then(response => {
                        this.album = response.body;
                        this.spinner(false);
                    });
            }
        },
        methods:{
            spinner(show){
                this.$root.showSpinner = show;
            },
            saveAlbum(){
                this.spinner(true);

                //first we assume we're adding an album
                let method = 'post';
                let url = this.$root.config.albums.url;

                //if we are editing, we need to change method and url
                if(this.$route.name == 'edit_album'){
                    method = 'put';
                    url += '/'+this.$route.params.id;
                }

                this.$http[method](url, this.album).then(response => {
                    displayToast(response);
                    this.spinner(false);
                    this.$router.push({name:'albums'});
                }, this.handleFailure);
            },
            handleFailure(response){
                displayToast(response);
                this.spinner(false);
            }
        }
    }
</script>