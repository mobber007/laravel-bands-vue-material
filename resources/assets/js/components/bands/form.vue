<template>
    <div v-if="band.hasOwnProperty('name')">
        <dynamic-form form_type="bands" :item="band" @saved="saveBand" @cancel="$router.push({name:'bands'})"></dynamic-form>
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
                band:{}
            }
        },
        created(){
            this.spinner(true);
            if(this.$route.name == 'add_band'){
                //adding a new band
                //we init with a blank band form
                this.band = {
                    name: null,
                    website: null,
                    start_date: '',
                    still_active: 1
                };
                this.spinner(false);
            } else {
                //editing a band
                //we init the form with band from the server
                this.$http.get(this.$root.config.bands.url+'/'+this.$route.params.id)
                    .then(response => {
                        this.band = response.body;
                        this.spinner(false);
                    });
            }
        },
        methods:{
            spinner(show){
                this.$root.showSpinner = show;
            },
            saveBand(){
                this.spinner(true);

                //we assume we're adding a band
                let method = 'post';
                let url = this.$root.config.bands.url;

                //if we are editing, we need to change method and url
                if(this.$route.name == 'edit_band'){
                    method = 'put';
                    url += '/'+this.$route.params.id;
                }

                this.$http[method](url, this.band).then(response => {
                    displayToast(response);
                    this.spinner(false);
                    this.$router.push({name:'bands'});
                }, this.handleFailure);
            },
            handleFailure(response){
                displayToast(response);
                this.spinner(false);
            }
        }
    }
</script>