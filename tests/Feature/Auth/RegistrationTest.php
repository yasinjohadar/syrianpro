<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register as talent', function () {
    $response = $this->post('/register', [
        'account_type' => 'talent',
        'name' => 'Test User',
        'title' => 'مطور Laravel',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('talent.dashboard', absolute: false));
});

test('new users can register as company', function () {
    $response = $this->post('/register', [
        'account_type' => 'company',
        'name' => 'Test Company',
        'sector' => 'تقنية',
        'email' => 'company@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('company.dashboard', absolute: false));
});
