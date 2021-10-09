# Evermos Test

## Requirement
- PHP 7.3 ^

## Installation
### Commands
1. Run `composer install`
2.  Create .env file
3. Run `php artisan key:generate`
4. Run `php artisan migrate --seed`
5. Run `php artisan jwt:secret`

### Run Application
- Run `php artisan serve`

## Packages
- [JWT by Sean Tymon](https://github.com/tymondesigns/jwt-auth).
- [Faker Provide by mbezhanov](https://github.com/mbezhanov/faker-provider-collection).

## Table Status Information
| Status         | Means                   |
|----------------|-------------------------|
| 1              | Cart                 |
| 2              | Checkout        |
| 3              | Pay                   |
| 4              | Cancel        |

## Description
- Pada setiap endpoint transaction (cart, checkout dan pay) dilakukan pengecekan pada stock dan diberikat expired at
- Pengurangan stock akan dilakukan ketika proses pay telah berhasil
- Log disimpan pada file laravel.log yang ada pada folder \storage\logs

## Documentation API
https://documenter.getpostman.com/view/3047490/UUy7c53N

