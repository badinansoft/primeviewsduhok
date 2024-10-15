<?php

use App\Http\Controllers\PrintController;
use App\Livewire\ProfileAboutPage;
use App\Livewire\ProfileGasPage;
use App\Livewire\ProfileHomePage;
use App\Livewire\ProfileServicePage;
use Illuminate\Support\Facades\Route;

Route::redirect('/', config('nova.path'));

Route::get('print/service/{service}', [PrintController::class, 'service'])->name('print.service');
Route::get('print/gas/{gas}', [PrintController::class, 'gas'])->name('print.gas');

Route::get('/profile/{uuid}', ProfileHomePage::class)->name('profile.show');
Route::get('/profile/{uuid}/about', ProfileAboutPage::class)->name('profile.about');
Route::get('/profile/{uuid}/gas', ProfileGasPage::class)->name('profile.gas');
Route::get('/profile/{uuid}/service', ProfileServicePage::class)->name('profile.service');
Route::get('/profile/{uuid}/gas/{id}/invoice', [PrintController::class, 'gasProfile'])->name('profile.invoice.gas');
Route::get('/profile/{uuid}/service/{id}/invoice', [PrintController::class, 'serviceProfile'])->name('profile.invoice.service');
