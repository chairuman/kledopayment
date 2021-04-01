## KLEDO PAYMENT

#### Endpoint

[GET] 		/api/payments

[POST] 	 /api/payments

- Dengan request body payment_name

[DELETE] /api/payments

- dengan parameter payment_od
- ex: ?payment_id=1,2,3,4



#### Cara instal

1. git clone https://github.com/chairuman/kledopayment.git
2. `cd kledopayment`
3. `composer install`
4. sesuaikan konfigurasi database pada file `.env`
5. jalankan migration `php artisan migrate`
6. jalankan seeder `php artisan db:seed`
7. `php artisan serve`
8. `php artisan queue:work`



#### Unit Testing

1. `cp .env .env.testing`
2. `.vendor\bin\phpunit`
3. Jika terjadi error sesuaikan parameter id dengan data yang ada