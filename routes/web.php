<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;



//View Page Routs
Route:: get('/', [HomeController::class, 'homePage']);

Route:: get('/userRegistration', [UserController::class, 'userRegistrationPage']);
Route:: get('/userLogin', [UserController::class, 'userLoginPage']);
Route:: get('/resetPassword', [UserController::class, 'resetPasswordPage']);
Route:: get('/sendOtp', [UserController::class, 'sendOtpPage']);
Route:: get('/verifyOtp', [UserController::class, 'verifyOtpPage']);
Route:: get('/userProfile', [UserController::class, 'profilePage'])->name('userProfile')->middleware(TokenVerificationMiddleware::class);


Route:: get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboard')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/customerPage', [CustomerController::class, 'customerPage'])->name('customer')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/categoryPage', [CategoryController::class, 'categoryPage'])->name('category')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/productPage', [productController::class, 'productPage'])->name('product')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/invoicePage', [InvoiceController::class, 'invoicePage'])->name('invoice')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/salePage', [SaleController::class, 'salePage'])->name('salePage')->middleware(TokenVerificationMiddleware::class);;
Route:: get('/reportPage', [ReportController::class, 'reportPage'])->name('report')->middleware(TokenVerificationMiddleware::class);;


// Backend Routs
// User
Route:: post('/user-registration', [UserController::class, 'userRegistration']);
Route:: post('/user-login', [UserController::class, 'userLogin']);
Route:: get('/logout', [UserController::class, 'logOut']);
Route:: post('/send-OTP', [UserController::class, 'sendOTP']);
Route:: post('/verify-OTP', [UserController::class, 'verifyOTP']);
Route:: post('/reset-password', [UserController::class, 'resetPassword']);

Route:: get('/get-user', [UserController::class, 'userProfile'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/update-profile', [UserController::class, 'updateUserProfile'])->middleware(TokenVerificationMiddleware::class);


// Category
Route:: post('/create-category', [CategoryController::class, 'createCategory'])->middleware(TokenVerificationMiddleware::class);
Route:: get('/all-category', [CategoryController::class, 'categoryList'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/category-by-id', [CategoryController::class, 'categoryById'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/update-category', [CategoryController::class, 'categoryUpdate'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/delete-category', [CategoryController::class, 'categoryDelete'])->middleware(TokenVerificationMiddleware::class);


// Customer
Route:: post('/create-customer', [CustomerController::class, 'createCustomer'])->middleware(TokenVerificationMiddleware::class);
Route:: get('/all-customer', [CustomerController::class, 'customerList'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/customer-by-id', [CustomerController::class, 'customerById'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/update-customer', [CustomerController::class, 'customerUpdate'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/delete-customer', [CustomerController::class, 'customerDelete'])->middleware(TokenVerificationMiddleware::class);


// Products
Route:: post('/create-product', [ProductController::class, 'productCreate'])->middleware(TokenVerificationMiddleware::class);
Route:: get('/all-product', [ProductController::class, 'productList'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/product-by-id', [ProductController::class, 'productById'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/update-product', [ProductController::class, 'productUpdate'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/delete-product', [ProductController::class, 'productDelete'])->middleware(TokenVerificationMiddleware::class);

Route:: get('/product-with-stock', [ProductController::class, 'hasStock'])->middleware(TokenVerificationMiddleware::class);


// Invoice
Route:: post('/create-invoice', [InvoiceController::class, 'invoiceCreate'])->middleware(TokenVerificationMiddleware::class);
Route:: get('/all-invoice', [InvoiceController::class, 'invoiceSelect'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/detail-invoice', [InvoiceController::class, 'invoiceDetails'])->middleware(TokenVerificationMiddleware::class);
Route:: post('/delete-invoice', [InvoiceController::class, 'invoiceDelete'])->middleware(TokenVerificationMiddleware::class);


// Dashboard Summary
Route::get("/summary",[DashboardController::class,'summary'])->middleware([TokenVerificationMiddleware::class]);


// Report Generation
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'salesReport'])->middleware([TokenVerificationMiddleware::class]);
