<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/all.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        <div v-show="$root.appLoaded" style="display:none">
            <toolbar-sidenav></toolbar-sidenav>

            <div class="spinner-container" v-if="$root.showSpinner">
                <md-spinner md-indeterminate class="md-accent"></md-spinner>
            </div>
            <div class="content-container">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>

        <div v-show="!$root.appLoaded" style="display:flex;align-items:center;justify-content:center;height:100vh;">Loading...</div>

    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
