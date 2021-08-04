## Install laravel 8 vue.js (2) spa
1. composer create-project laravel/laravel vuejs-laravel-app
2.  
    1. cd ./vuejs-laravel-app 
    2.  composer require laravel/ui 
3. php artisan ui vue --auth
4. npm install
5. 
    1. npm i vue@next 
    2. npm i vue-router@next
6. npm i -D @vue/compiler-sfc vue-loader@next
npm install
7. Database Connection  
8. php artisan migrate
9. routes/web.php Route::view('/{path?}', 'index')->where('path', '.*');
10.

    
### sanctum https://laravel.com/docs/8.x/sanctum#installation
1. composer require laravel/sanctum
2. php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
3. php artisan migrate
4. app/Http/Kernel.php  

### bootsrap5
1. npm install bootstrap@next 
2. npm install @popperjs/core --save-dev