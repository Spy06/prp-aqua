<?php

test('registration screen returns 404', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('register post store returns 404', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(404);
});