#Laravel Bands App

This is an SPA version of the initial bands app, using Vue and Vue Material.

###Usage
1. Clone/download repo
2. Install dependencies with `composer install`
3. Create .env file and setup DB config or copy .env.example and populate values
4. Generate the key `php artisan key:generate`
5. Populate db `php artisan migrate:refresh --seed`
6. Compile sass and js with `npm run prod`
7. Setup server to point to `public` folder
8. Login with admin account (credentials are in seeder file) -> `database/seeds/DatabaseSeeder.php` 


###Packages
[Laravel 5.3.28](https://laravel.com)

[Vue.js 2.1.6](https://vuejs.org/)

[Vue Material 0.5.1](https://vuematerial.github.io/#/)

[Vue Resource 1.0.3](https://github.com/pagekit/vue-resource)

[Vue Router 2.1.1](http://router.vuejs.org/en/index.html)

[Vue Loader 9.9.5](http://vue-loader.vuejs.org/en/)

[Moment 2.15.0](http://momentjs.com/)