<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
//调用extend的路由类
$Route = new \Route;
$Route->route_rule();
//首页
Route::get('/home', function () {
    return view('app/shop/view/index.html');
});
//商品详情页
Route::get("/product", function () {
    return view('app/shop/view/product.html');
});
//购物车
Route::get("/checkout", function () {
    return view('app/shop/view/checkout.html');
});
//登录
Route::get("/login", function () {
    return view('app/shop/view/login.html');
});
//contact
Route::get("/contact", function () {
    return view('app/shop/view/contact.html');
});
//collections
Route::get("/collections", function () {
    return view('app/shop/view/collections.html');
});
//payment
Route::get("/payment", function () {
    return view('app/shop/view/payment.html');
});
//register
Route::get("/register", function () {
    return view('app/shop/view/register.html');
});
//terms
Route::get("/terms", function () {
    return view('app/shop/view/terms.html');
});

//分类
Route::group('/admin/category/', function () {
    Route::get("delete/:category_id", '/admin/category/delete');
    Route::get("edit/:category_id", '/admin/category/edit');
    Route::post("setShow", '/admin/category/setShow');
});

//商品管理
Route::group("/admin/Goods/", function () {
    Route::post("setInfo", '/admin/Goods/setInfo');
    Route::get("edit/:goods_id", '/admin/Goods/edit');
    Route::get("delete/:goods_id", '/admin/Goods/delete');
});

//上传
Route::any("/admin/upload/preview", '/admin/upload/preview');

return [
    //也可以这里添加路由规则
];
