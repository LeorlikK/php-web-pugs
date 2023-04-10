<?php
use App\Http\Controllers\Admin\AdminController;
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
Router::post('/media/photos/save', PhotosController::class, 'create');
Router::post('/media/photos/delete', PhotosController::class, 'delete');

Router::get('/media/video', VideoController::class, 'index');
Router::post('/media/video/save', VideoController::class, 'create');
Router::post('/media/video/delete', VideoController::class, 'delete');

Router::get('/media/audio', AudioController::class, 'index');
Router::post('/media/audio/save', AudioController::class, 'create');
Router::post('/media/audio/delete', AudioController::class, 'delete');

// Personal Area
Router::get('/office', PersonalAreaController::class, 'main');
Router::post('/office/login/update', PersonalAreaController::class, 'loginUpdate');
Router::post('/office/avatar/update', PersonalAreaController::class, 'avatarUpdate');
Router::post('/office/email/send', PersonalAreaController::class, 'emailSend');

// Admin
Router::get('/admin', AdminController::class, 'main');
Router::get('/admin/peculiarities', PeculiaritiesAdminController::class, 'main');

Router::get('/admin/users', UsersAdminController::class, 'main');
Router::get('/admin/users/edit', UsersAdminController::class, 'edit');
Router::post('/admin/users/update', UsersAdminController::class, 'update');
Router::post('/admin/users/delete', UsersAdminController::class, 'delete');

Router::get('/test', \App\Http\Controllers\Test::class, 'sayTest');
Router::exec();