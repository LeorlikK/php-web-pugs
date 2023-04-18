<?php
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Media\AudioAdminController;
use App\Http\Controllers\Admin\Media\PhotosAdminController;
use App\Http\Controllers\Admin\Media\VideoAdminController;
use App\Http\Controllers\Admin\News\NewsAdminController;
use App\Http\Controllers\Admin\Nurseries\NurseriesAdminController;
use App\Http\Controllers\Admin\Peculiarities\PeculiaritiesAdminController;
use App\Http\Controllers\Admin\Users\UsersAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Comments\CommentsController;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Media\AudioController;
use App\Http\Controllers\Media\PhotosController;
use App\Http\Controllers\Media\VideoController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Nurseries\NurseriesController;
use App\Http\Controllers\Peculiarities\PeculiaritiesController;
use App\Http\Controllers\PersonalArea\PersonalAreaController;
use Routes\Router;

// Main
Router::get('/', MainController::class, 'main');

// Auth
Router::get('/login', LoginController::class, 'loginShow');
Router::post('/login', LoginController::class, 'loginCreate');
Router::post('/logout', LoginController::class, 'logout');
Router::get('/registration', RegistrationController::class, 'registrationShow');
Router::post('/registration', RegistrationController::class, 'registrationCreate');
Router::get('/registration/verify', RegistrationController::class, 'verify');

// Peculiarities
Router::get('/peculiarities', PeculiaritiesController::class, 'peculiarities');
Router::get('/peculiarities/care', PeculiaritiesController::class, 'care');
Router::get('/peculiarities/nutrition', PeculiaritiesController::class, 'nutrition');
Router::get('/peculiarities/health', PeculiaritiesController::class, 'health');
Router::get('/peculiarities/paddock', PeculiaritiesController::class, 'paddock');

// Nurseries
Router::get('/nurseries', NurseriesController::class, 'index');

// News
Router::get('/news', NewsController::class, 'index');
Router::get('/news/show', NewsController::class, 'show');
Router::get('/news/show/dop-comments', NewsController::class, 'dopComments');

// News Comments
Router::post('/news/comments/create', CommentsController::class, 'create');
Router::post('/news/comments/create-dop', CommentsController::class, 'createDop');

// Media
Router::get('/media/photos', PhotosController::class, 'index');
Router::post('/media/photos/store', PhotosController::class, 'store');
Router::post('/media/photos/delete', PhotosController::class, 'delete');

Router::get('/media/video', VideoController::class, 'index');
Router::post('/media/video/store', VideoController::class, 'store');
Router::post('/media/video/delete', VideoController::class, 'delete');

Router::get('/media/audio', AudioController::class, 'index');
Router::post('/media/audio/store', AudioController::class, 'store');
Router::post('/media/audio/delete', AudioController::class, 'delete');

// Personal Area
Router::get('/office', PersonalAreaController::class, 'main');
Router::post('/office/login/update', PersonalAreaController::class, 'loginUpdate');
Router::post('/office/avatar/update', PersonalAreaController::class, 'avatarUpdate');
Router::post('/office/email/send', PersonalAreaController::class, 'emailSend');

// Admin
Router::get('/admin', AdminController::class, 'main');

Router::get('/admin/peculiarities', PeculiaritiesAdminController::class, 'main');
Router::get('/admin/peculiarities/edit', PeculiaritiesAdminController::class, 'edit');
Router::post('/admin/peculiarities/update', PeculiaritiesAdminController::class, 'update');

Router::get('/admin/users', UsersAdminController::class, 'main');
Router::get('/admin/users/edit', UsersAdminController::class, 'edit');
Router::post('/admin/users/update', UsersAdminController::class, 'update');
Router::post('/admin/users/delete', UsersAdminController::class, 'delete');

Router::get('/admin/photos', PhotosAdminController::class, 'main');
Router::post('/admin/photos/store', PhotosAdminController::class, 'store');
Router::get('/admin/photos/edit', PhotosAdminController::class, 'edit');
Router::post('/admin/photos/update', PhotosAdminController::class, 'update');
Router::post('/admin/photos/delete', PhotosAdminController::class, 'delete');

Router::get('/admin/video', VideoAdminController::class, 'main');
Router::post('/admin/video/store', VideoAdminController::class, 'store');
Router::get('/admin/video/edit', VideoAdminController::class, 'edit');
Router::post('/admin/video/update', VideoAdminController::class, 'update');
Router::post('/admin/video/delete', VideoAdminController::class, 'delete');

Router::get('/admin/audio', AudioAdminController::class, 'main');
Router::post('/admin/audio/store', AudioAdminController::class, 'store');
Router::get('/admin/audio/edit', AudioAdminController::class, 'edit');
Router::post('/admin/audio/update', AudioAdminController::class, 'update');
Router::post('/admin/audio/delete', AudioAdminController::class, 'delete');

Router::get('/admin/news', NewsAdminController::class, 'main');
Router::get('/admin/news/create', NewsAdminController::class, 'create');
Router::post('/admin/news/store', NewsAdminController::class, 'store');
Router::get('/admin/news/edit', NewsAdminController::class, 'edit');
Router::post('/admin/news/update', NewsAdminController::class, 'update');
Router::post('/admin/news/delete', NewsAdminController::class, 'delete');

Router::get('/admin/nurseries', NurseriesAdminController::class, 'main');
Router::get('/admin/nurseries/create', NurseriesAdminController::class, 'create');
Router::post('/admin/nurseries/store', NurseriesAdminController::class, 'store');
Router::get('/admin/nurseries/edit', NurseriesAdminController::class, 'edit');
Router::post('/admin/nurseries/update', NurseriesAdminController::class, 'update');
Router::post('/admin/nurseries/delete', NurseriesAdminController::class, 'delete');

Router::get('/test', \App\Http\Controllers\Test::class, 'sayTest');
Router::exec();