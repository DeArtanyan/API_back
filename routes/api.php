<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingServiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HotelReviewController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/hotel-reviews', [HotelReviewController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // Пользователь
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::delete('/user', [UserController::class, 'destroy']);

    // Бронирования
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);

    // Рейтинг номеров
    Route::post('/rooms/{id}/rate', [RoomController::class, 'rate']);
    Route::delete('/rooms/{room}/rating', [RoomController::class, 'deleteRating']);

    //Отзывы
    Route::post('/hotel-reviews', [HotelReviewController::class, 'store']);
    Route::delete('/hotel-reviews/{review}', [HotelReviewController::class, 'destroy']);

    // Услуги для бронирований
    Route::post('/bookings/{booking}/services/{service}', [BookingServiceController::class, 'addService']);
    Route::delete('/bookings/{booking}/services/{service}', [BookingServiceController::class, 'removeService']);
    Route::get('/bookings/{booking}/services', [BookingServiceController::class, 'listServices']);
});
