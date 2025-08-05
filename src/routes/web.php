<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminFormController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

// PG01: トップ画面
Route::get('/', [ItemController::class, 'index'])->name('home');

// PG02: マイリスト画面
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

// PG03: 会員登録画面 (Fortifyで設定済み)
Fortify::registerView(function () {
    return view('auth.register');
});

// PG04: ログイン画面 (Fortifyで設定済み)
Fortify::loginView(function () {
    return view('auth.login');
});

// 認証関連
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// PG05: 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// 認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    // 商品購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'process'])->name('purchase.process');
    Route::get('/purchase/{item}/address', [PurchaseController::class, 'showAddress'])->name('purchase.address');
    Route::post('/purchase/{item}/address', [PurchaseController::class, 'updateAddress'])->name('purchase.update_address');
    Route::get('/purchase/{item}/complete', [PurchaseController::class, 'complete'])->name('purchase.complete');
    Route::get('/purchase/konbini', [PurchaseController::class, 'showKonbini'])->name('purchase.konbini');
    
    // PG07: 送付先住所変更画面
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'showAddress'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
    
    // PG08: 商品出品画面
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
    
    // PG09: プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    
    // PG10: プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // PG11: プロフィール画面_購入した商品一覧
    Route::get('/mypage/purchases', [ProfileController::class, 'purchases'])->name('profile.purchases');
    
    // PG12: プロフィール画面_出品した商品一覧
    Route::get('/mypage/sales', [ProfileController::class, 'sales'])->name('profile.sales');
    
    // 管理者用ルート
    Route::get('/auth/admin', [AdminFormController::class, 'showAdminForm'])->name('showAdmin');
    Route::get('/auth/admin/search', [AdminFormController::class, 'searchContact'])->name('searchContact');
});

// お問い合わせ関連
Route::get('/contact', [ContactController::class, 'showContact'])->name('showContact');
Route::post('/confirm', [ContactController::class, 'confirm'])->name('confirm');
Route::post('/thanks', [ContactController::class, 'store'])->name('store');
Route::get('/contact/export', [AdminFormController::class, 'export'])->name('contact.export');
Route::delete('/contact/{id}', [AdminFormController::class, 'destroy'])->name('contact.destroy');

Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');

// いいね機能のルート
Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('items.like');
