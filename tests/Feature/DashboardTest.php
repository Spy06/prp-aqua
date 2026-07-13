<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated karyawan is redirected to beranda', function () {
    $user = User::factory()->create(['role' => 'karyawan']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('beranda'));
});

test('authenticated qa is redirected to qa dashboard', function () {
    $user = User::factory()->create(['role' => 'qa']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('qa.dashboard'));
});