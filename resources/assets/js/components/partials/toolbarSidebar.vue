<template>
    <div>
        <md-toolbar>
            <md-button class="md-icon-button"
                       v-on:click="toggleSideNav"
            >
                <md-icon>menu</md-icon>
            </md-button>
            <md-layout md-flex><h2 class="md-title" md-flex>{{$root.title}}</h2></md-layout>
            <md-button onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span>Logout</span>
            </md-button>
        </md-toolbar>

        <md-sidenav class="md-left" ref="sideNav">
            <md-toolbar class="md-large">
                <div class="md-toolbar-container">
                    <h3 class="md-title">{{$root.title}}</h3>
                    <h4 class="md-subheading">Menu</h4>
                </div>
            </md-toolbar>
            <md-list>
                <md-list-item href="#/bands" @click="toggleSideNav">
                    <md-icon>recent_actors</md-icon>
                    <span>Bands</span>
                </md-list-item>
                <md-list-item href="#/albums" @click="toggleSideNav">
                    <md-icon>album</md-icon>
                    <span>Albums</span>
                </md-list-item>
            </md-list>
        </md-sidenav>
    </div>
</template>
<script>
    export default {
        data(){
            return {

            }
        },
        methods:{
            toggleSideNav(){
                this.$refs.sideNav.toggle();
            }
        }
    }
</script>