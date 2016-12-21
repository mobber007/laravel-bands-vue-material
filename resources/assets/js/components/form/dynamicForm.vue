<template>
    <div>
        <md-card>
            <md-card-header>
                <md-card-header-text>
                    <span class="md-title">{{$route.meta.title}}</span>
                </md-card-header-text>
            </md-card-header>

            <md-card-content>
                <form @submit.prevent="submitForm" v-if="form">
                    <md-layout v-for="row in form" md-gutter>
                        <md-layout v-for="field in row"
                                   :md-flex="field.flex.default"
                                   :md-flex-xsmall="field.flex.xsmall"
                                   :md-flex-small="field.flex.small"
                                   :md-flex-medium="field.flex.medium"
                                   :md-flex-large="field.flex.large"
                                   :md-flex-xlarge="field.flex.xlarge"
                                   >
                            <form-field :field="field" :model="item"></form-field>
                        </md-layout>
                    </md-layout>
                    <md-layout class="align-center">
                        <md-button type="submit" class="md-raised md-primary">Save {{$root.config[form_type].labels.title}}</md-button>
                        <md-button type="button" class="md-raised" @click.prevent="goBack">Cancel</md-button>
                    </md-layout>
                </form>
            </md-card-content>
        </md-card>
    </div>
</template>

<script>
    import FormField from './formField.vue';

    export default {
        components:{
            FormField
        },
        props:{
            form_type:{
                type: String,
                required: true
            },
            item:{
                type: Object,
                required: true
            }
        },
        data(){
            return {

            }
        },
        computed:{
            form(){
                let rows = this.$root.config[this.form_type].fields;

                return rows.filter( row => {
                    return row instanceof Array;
                });
            }
        },
        created(){
            console.log('dynamic form started');
        },
        methods:{
            submitForm(){
                this.$emit('saved');
            },
            goBack(){
                this.$emit('cancel');
            }
        }
    }
</script>