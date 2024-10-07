<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleReviewController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\KnowledgeAreaController;
use App\Http\Controllers\KnowledgeSubAreaController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');
Route::get('/comite', [WelcomeController::class, 'committee'])->name('welcome.committee');
Route::get('/lugar', [WelcomeController::class, 'place'])->name('welcome.place');
Route::get('/programa', [WelcomeController::class, 'program'])->name('welcome.program');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard', ['users' => User::all(), 'modulos' => Module::all()]);
    // })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-photo', [ProfileController::class, 'destroyPhoto'])->name('profile.destroyPhoto');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Seguridad
    Route::resource('module', ModuleController::class)->parameters(['module' => 'module']);
    Route::resource('permission', PermissionController::class)->names('permission');
    Route::resource('role', RoleController::class)->parameters(['role' => 'role']);
    Route::resource('user', UserController::class)->parameters(['user' => 'user']);

    // Catalogos
    Route::resource('call', CallController::class)->parameters(['call' => 'call']);
    Route::resource('knowledgeArea', KnowledgeAreaController::class)->parameters(['knowledgeArea' => 'knowledgeArea']);
    Route::resource('criterion', CriterionController::class)->parameters(['criterion' => 'criterion']);
    Route::resource('knowledgeSubArea', KnowledgeSubAreaController::class)->parameters(['knowledgeSubArea' => 'knowledgeSubArea']);
    Route::resource('institution', InstitutionController::class)->parameters(['institution' => 'institution']);

    // Proyectos
    Route::resource('project', ProjectController::class)->parameters(['project' => 'project']);
    Route::resource('projectReview', ArticleReviewController::class)->parameters(['projectReview' => 'projectReview']);

    //
    Route::resource('article', ArticleController::class)->parameters(['article' => 'article']);
    Route::resource('articleReview', ArticleReviewController::class)->parameters(['articleReview' => 'articleReview']);
    // apis
    Route::delete('destroy-author/{id}', [AuthorController::class, 'destroy'])->name('author.destroy');
    Route::patch('update-evaluation-editor/{article}', [ArticleController::class, 'updateEvaluation'])->name('article.updateEvaluation');

    Route::get('/sign-pdf/{id}', [ArticleController::class, 'signPdf'])->name('article.signPdf');

});

require __DIR__ . '/auth.php';