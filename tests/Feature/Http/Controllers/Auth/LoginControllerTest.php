<?php

it('can access login page', function () {
    $response = $this->get('/');

    // The root route likely redirects to login, so expect 302 or 200 depending on your setup
    $response->assertStatus(302); // or use assertRedirect() if redirecting to a specific route
});
