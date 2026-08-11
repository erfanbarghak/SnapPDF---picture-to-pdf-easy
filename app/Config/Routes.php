<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// =============================================
// احراز هویت
// =============================================
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('doLogin', 'Auth::doLogin');           // ← این مسیر باید باشد
    $routes->get('doRegister', 'Auth::doRegister');
    $routes->get('register', 'Auth::register');
    $routes->get('reset-password', 'Auth::resetPassword');
    $routes->get('verify-phone', 'Auth::verifyPhone');
    
    $routes->post('login', 'Auth::doLogin');
    $routes->post('doRegister', 'Auth::doRegister');
    $routes->post('register', 'Auth::register');
    $routes->post('doVerifyPhone', 'Auth::doVerifyPhone');
    $routes->post('resendCode', 'Auth::resendCode');
    $routes->post('reset-password', 'Auth::doResetPassword');
    
    $routes->get('logout', 'Auth::logout');
});

// =============================================
// داشبورد (نیاز به احراز هویت)
// =============================================
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('history', 'Dashboard::history');
    $routes->get('stats', 'Dashboard::stats');
    $routes->get('pdf-ready/(:segment)', 'Dashboard::pdfReady/$1');  // ← مسیر جدید
    $routes->get('download/(:segment)', 'Dashboard::download/$1');
    $routes->get('cleanup', 'Dashboard::cleanup');
    
    $routes->post('convert', 'Dashboard::convert');
    $routes->post('delete-conversion/(:num)', 'Dashboard::deleteConversion');
});


// =============================================
// پروفایل (نیاز به احراز هویت)
// =============================================
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->post('update', 'Profile::update');
    $routes->post('change-password', 'Profile::changePassword');
    $routes->post('delete', 'Profile::delete');
});

// =============================================
// تست (فقط توسعه)
// =============================================
if (ENVIRONMENT === 'development') {
    $routes->get('test-sms', 'Auth::testSms');
    $routes->get('test-bale', 'Auth::testBale');
}