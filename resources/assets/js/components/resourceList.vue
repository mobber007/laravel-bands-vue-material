<template>
    <div>
        <md-table-card>
            <md-toolbar>
                <h4 class="md-title">{{$root.config[item_type].labels.title}}s</h4>
                <md-input-container md-inline>
                    <label>{{$root.config[item_type].labels.search}}</label>
                    <md-input v-model="filter_input"></md-input>
                </md-input-container>
                <md-button @click.prevent="newItem" class="md-raised primary">
                    <md-icon>add</md-icon>
                    {{$root.config[item_type].labels.add}}
                </md-button>
            </md-toolbar>

            <md-table :md-sort="$root.config[item_type].sort" :md-sort-type="$root.config[item_type].sort_dir"
                      @sort="onSort" @select="editItem">
                <md-table-header>
                    <md-table-row>
                        <md-table-head v-for="field in fields"
                                       :md-sort-by="field.key == 'band_name' ? field.key  : 'albums.'+field.key"
                                       :md-numeric="field.type == 'number'"
                        >{{field.label}}
                        </md-table-head>
                        <md-table-head>Actions</md-table-head>
                    </md-table-row>
                </md-table-header>

                <md-table-body>
                    <md-table-row v-for="(item, index) in list_items" :key="index"
                                  :md-item="item"
                    >
                        <md-table-cell v-for="field in fields" :key="field.key"
                                       :md-numeric="field.type == 'number'"
                        >
                            <span v-if="field.type == 'date'">{{ item[field.key] | niceDate }}</span>
                            <span v-else-if="field.boolean">{{ item[field.key] ? field.yes : field.no }}</span>
                            <span v-else>{{item[field.key]}}</span>
                        </md-table-cell>
                        <md-table-cell key="actions">
                            <md-button class="md-icon-button md-accent md-dense" @click.prevent="editItem(item.id)">
                                <md-icon>edit</md-icon>
                            </md-button>
                            <md-button class="md-icon-button md-warn md-dense" @click.prevent="deleteItem(item)">
                                <md-icon>delete</md-icon>
                            </md-button>
                        </md-table-cell>
                    </md-table-row>
                </md-table-body>
            </md-table>

            <md-table-pagination
                    :md-size="$root.config[item_type].per_page"
                    :md-total="$root.config[item_type].pagination.total"
                    :md-page="$root.config[item_type].pagination.page"
                    :md-label="$root.config[item_type].labels.title+'s'"
                    md-separator="of"
                    :md-page-options="[10, 25, 50]"
                    @pagination="onPagination"></md-table-pagination>
        </md-table-card>
        <md-dialog-confirm :md-title="modal.title"
                           :md-content-html="modal.contentHtml"
                           :md-ok-text="modal.ok"
                           :md-cancel-text="modal.cancel"
                           @close="modalClose"
                           ref="delete_modal"
        ></md-dialog-confirm>
    </div>
</template>

<script>
    window._ = require('lodash');
    export default {
        http: {
            headers: {
                'X-CSRF-TOKEN': Laravel.csrfToken
            }
        },
        components: {},
        props: {
            item_type: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                filter_input: this.$root.config[this.item_type].filter,
                list_items: [],
                url: this.$root.config[this.item_type].url,
                modal: {
                    title: 'Delete ' + this.$root.config[this.item_type].labels.title,
                    contentHtml: 'Filler here',
                    ok: 'Delete',
                    cancel: 'Cancel'
                },
                delete_id: null, //used when an item is chosen for deletion
            }
        },
        computed: {
            params(){
                //start with pagination
                let params = this.$root.config[this.item_type].pagination;

                //we don't need to send total to the server
                delete params.total;

                //append sorting
                params.sort = this.$root.config[this.item_type].sort;
                params.sort_dir = this.$root.config[this.item_type].sort_dir;

                params.query = this.$root.config[this.item_type].filter;

                return params;
            },
            fields(){

                //take the fields array and reduce to a single layer
                //array of objects
                let reduced = this.$root.config[this.item_type].fields
                    .reduce((acc, cur) => acc.concat(cur));

                //then take all fields that have a list_order prop and sort them.
                //don't use a list_order prop if you want to hide a column
                return reduced.filter(field => {
                    return field.hasOwnProperty('list_order');
                }).sort((prev, next) => prev.list_order - next.list_order);

            }
        },
        created(){
            this.spinner(true);
            this.getItems();
        },
        watch: {
            filter_input(search){
                this.$root.config[this.item_type].filter = search;
                this.getItems();
            }
        },
        methods: {
            spinner(show){
                this.$root.showSpinner = show;
            },
            getItems: _.debounce(function () {
                this.spinner(true);
                this.$http.get(this.url, {params: this.params}).then(response => {
                    this.list_items = response.body.data;
                    this.spinner(false);
                    this.$root.config[this.item_type].pagination.page = response.body.current_page;
                    this.$root.config[this.item_type].pagination.per_page = response.body.per_page;
                    this.$root.config[this.item_type].pagination.total = response.body.total;
                }, this.handleFailure);
            }, 1000),
            newItem(){
                this.spinner(true);
                this.$router.push('/' + this.item_type + '/add');
            },
            editItem(id){
                this.spinner(true);
                this.$router.push({name: this.$root.config[this.item_type].edit_route, params: {id: id}});
            },
            deleteItem(item){
                this.delete_id = item.id;
                this.modal.contentHtml = 'Are you sure you want to delete ' + item.name + '?';
                this.$refs.delete_modal.open();
            },
            modalClose(answer){
                if (answer === 'ok') {
                    this.spinner(true);
                    this.$http.delete(this.url + '/' + this.delete_id).then(response => {
                        displayToast(response);

                        if (response.body.hasOwnProperty('message') && response.body.message.type == 'success') {
                            //we got a success message
                            this.getItems();
                        }
                    }, this.handleFailure);
                }
            },
            onSort(sort){
                this.spinner(true);
                this.$root.config[this.item_type].sort = sort.name;
                this.$root.config[this.item_type].sort_dir = sort.type;
                this.getItems();
            },
            onPagination(pagination){
                this.spinner(true);
                this.$root.config[this.item_type].pagination.page = pagination.page;
                this.$root.config[this.item_type].pagination.per_page = pagination.size;
                this.getItems();
            },
            handleFailure(response){
                displayToast(response);
                this.spinner(false);
            }
        }
    }
</script>