<template>
    <md-layout>
        <md-layout v-if="field.type == 'text' || field.type == 'date' || field.type == 'number'">
            <md-layout v-if="field.type != 'date'">
                <md-input-container>
                    <label>{{field.label}}</label>
                    <md-input v-model="model[field.key]" :type="field.type" :required="field.required"></md-input>
                </md-input-container>
            </md-layout>
            <md-layout v-else>
                <div class="md-input-container md-theme-default">
                    <label>{{field.label}}</label>
                    <input type="text" class="md-input"
                           :required="field.required"
                           v-model="model[field.key]"
                           v-date="{obj:model, prop: field.key}">
                </div>
            </md-layout>
        </md-layout>
        <md-layout v-if="field.type == 'select'">
            <md-input-container v-if="field.key !== 'band_id'">
                <label :for="field.key">{{_fetching ? 'Loading...' : field.label}}</label>
                <md-select :name="field.key" :id="field.key"
                           v-model="model[field.key]"
                           :required="field.required"
                           >
                    <md-option v-if="field.options"
                               v-for="option in field.options"
                               :value="option[field.option_value]"
                               >{{option[field.option_label]}}</md-option>
                </md-select>
            </md-input-container>

            <!-- we use a regular select for the bands dropdown because the md-select renders very slow -->
            <div v-else class="md-input-container md-theme-default md-has-value force-md">
                <label :for="field.key">{{field.label}}</label>
                <select :name="field.key"
                        :id="field.key"
                        class="md-select"
                        v-model="model[field.key]"
                        :required="field.required"
                        >
                    <option v-if="field.options"
                            v-for="option in field.options"
                            :value="option[field.option_value]"
                            >{{option[field.option_label]}}</option>
                </select>
            </div>
        </md-layout>
        <md-layout v-if="false && field.type == 'boolean'">
            <md-switch v-model="model[field.key]"
                       :id="field.key"
                       :name="field.key"
                       class="md-primary"
                       >{{field.label}}</md-switch>
        </md-layout>
    </md-layout>
</template>

<script>
    export default {
        props:{
            field: {
                validator(field){
                    if(    !field.type == 'text'
                        && !field.type == 'date'
                        && !field.type == 'number'
                        && !field.type == 'select'
                        && !field.type == 'boolean'
                    ){
                        //field is not a valid type
                        return false;
                    }

                    if(field.type == 'select'){
                        if(    !field.hasOwnProperty('fetch')
                            && !field.hasOwnProperty('options')
                        ){
                            //select fields need a fetch or options prop to populate options
                            return false;
                        }
                    }

                    return true;
                }
            },
            model:{
                type: Object,

            }
        },
        data(){
            return {
                _fetching: false //switch to true when loading options from server
            }
        },
        created(){
            if(this.field.type == 'select'){
                //this field is a select dropdown
                //we might need to fetch
                if(this.field.hasOwnProperty('fetch')){
                    this.fetching(true);
                    this.spinner(true);

                    //we need to fetch options from the server
                    this.$http.get(this.field.fetch.url).then( response => {
                        this.field.options = response.body;
                        this.$nextTick(()=>{
                            //force update or the DOM will not show any options
                            this.$forceUpdate();
                            this.fetching(false);
                            this.spinner(false);
                        });
                    }  );
                }
            }
        },
        methods:{
            spinner(show){
                this.$root.showSpinner = show;
            },
            fetching(bool){
                this._fetching = bool;
            }
        }
    }
</script>