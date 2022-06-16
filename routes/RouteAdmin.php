<?php
Route::get('admin/login','Admin\AdminController@getLogin');
Route::post('admin/login','Admin\AdminController@postLogin')->name("admin.login");
Route::get('admin/logout','Admin\AdminController@getLogout')->name('admin.logout');
Route::group(['namespace'=>'Admin','prefix'=>'/admin','middleware'=>'checkAdminLogin'],function(){
    
    Route::get('/','AdminController@index')->name('admin.home');
    Route::get('/test','AdminTestController@index')->name('admin.test');
    Route::group(['prefix'=>'category'],function(){
        Route::get('/','AdminCategoryController@index')->name('admin.category.index');
        Route::get('/create','AdminCategoryController@create')->name('admin.category.create');
        Route::post('/create','AdminCategoryController@store');
        Route::get('/update/{id}','AdminCategoryController@edit')->name('admin.category.edit');
        Route::post('/update/{id}','AdminCategoryController@update');
        Route::get('/{action}/{id}','AdminCategoryController@handle')->name('admin.category.handle');
    });

    Route::group(['prefix'=>'attribute'],function(){
        Route::get('/','AdminAttributeController@index')->name('admin.attribute.index');
        Route::get('/create','AdminAttributeController@create')->name('admin.attribute.create');
        Route::post('/create','AdminAttributeController@store');
        Route::get('/update/{id}','AdminAttributeController@edit')->name('admin.attribute.edit');
        Route::post('/update/{id}','AdminAttributeController@update');
        Route::get('/{action}/{id}','AdminAttributeController@handle')->name('admin.attribute.handle');
    });

    Route::group(['prefix'=>'product'],function(){
        Route::get('/','AdminProductController@index')->name('admin.product.index');
        Route::get('/create','AdminProductController@create')->name('admin.product.create');
        Route::post('/create','AdminProductController@store');
        Route::get('/update/{id}','AdminProductController@edit')->name('admin.product.edit');
        Route::post('/update/{id}','AdminProductController@update');
        Route::get('/{action}/{id}','AdminProductController@handle')->name('admin.product.handle');
        Route::get('/getAttribute','AdminProductController@getAttribute')->name('admin.ajax.get.attribute');
    });

    Route::group(['prefix'=>'article'],function(){
        Route::get('/','AdminArticleController@index')->name('admin.article.index');
        Route::get('/create','AdminArticleController@create')->name('admin.article.create');
        Route::post('/create','AdminArticleController@store');
        Route::get('/update/{id}','AdminArticleController@edit')->name('admin.article.edit');
        Route::post('/update/{id}','AdminArticleController@update');
        Route::get('/{action}/{id}','AdminArticleController@handle')->name('admin.article.handle');
    });

    Route::group(['prefix'=>'transaction'],function(){
        
        Route::get('/','AdminTransactionController@index')->name('admin.transaction.index');
        Route::get('/orderItem/{id}','AdminTransactionController@getOrderItem')->name('admin.get.order.item');
        Route::get('/paid/{id}','AdminTransactionController@transactionPaid')->name('admin.transaction.paid');
        Route::get('/{action}/{id}','AdminTransactionController@handle')->name('admin.transaction.handle');
        Route::get('/export/transaction-pdf/{id}','AdminTransactionController@exportTransactionPdf')->name('admin.get.export.transaction');
    });

    Route::group(['prefix'=>'comment'],function(){
        Route::get('/','AdminCommentController@index')->name('admin.comment.index');
        Route::get('/{action}/{id}','AdminCommentController@action')->name('admin.comment.action');
    });

    Route::group(['prefix'=>'user'],function(){
        Route::get('/','AdminUserController@index')->name('admin.user.index');
        Route::get('/create','AdminUserController@create')->name('admin.user.create');
        Route::post('/create','AdminUserController@store');
        Route::get('/edit/{id}','AdminUserController@edit')->name('admin.user.edit');
        Route::post('/edit/{id}','AdminUserController@update');
        Route::post('/changepassword/{id}','AdminUserController@changePassword')->name('admin.change.password');
        Route::get('/{action}/{id}','AdminUserController@action')->name('admin.user.action');
    });

    Route::group(['prefix'=>'statistics'],function(){
        Route::get('/','AdminStatisticsController@index')->name('admin.statistics.index');
        Route::get('/list','AdminStatisticsController@getStatistics')->name('admin.get.list.statistical');
        Route::get('/export-pdf','AdminStatisticsController@exportPdf')->name('admin.get.export.statistical');
    });
    Route::group(['prefix'=>'slide'],function(){
        Route::get('/','AdminSlideController@index')->name('admin.slide.index');
        Route::get('/create','AdminSlideController@create')->name('admin.slide.create');
        Route::post('/create','AdminSlideController@store');
        Route::get('/update/{id}','AdminSlideController@edit')->name('admin.slide.edit');
        Route::post('/update/{id}','AdminSlideController@update');
        Route::get('/{action}/{id}','AdminSlideController@handle')->name('admin.slide.handle');
    });
    Route::group(['prefix'=>'warehouse'],function(){
        Route::get('/','AdminWarehouseController@import')->name('admin.warehouse.import');
        Route::get('/import/{id}','AdminWarehouseController@importProduct')->name('admin.warehouse.import.product');
        Route::get('/importWhProduct','AdminWarehouseController@importWhPro')->name('admin.warehouse.import.WhPro');
        Route::get('/history','AdminWarehouseController@history')->name('admin.warehouse.history');
        Route::get('/stock','AdminWarehouseController@stock')->name('admin.warehouse.stock');
        Route::get('/create','AdminWarehouseController@create')->name('admin.warehouse.create');
        Route::get('/transfer','AdminWarehouseController@transfer')->name('admin.warehouse.transfer');
        Route::post('/create','AdminWarehouseController@store')->name('admin.warehouse.store');
        Route::get('/iventory','AdminWarehouseController@iventory')->name('admin.warehouse.iventory');
        Route::get('/bestseller','AdminWarehouseController@bestSeller')->name('admin.warehouse.bestseller');
        Route::get('/hotproduct/{id}','AdminWarehouseController@hotProduct')->name('admin.warehouse.hotproduct');
    });
});