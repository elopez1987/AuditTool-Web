<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    // Since the root route redirects (likely to login), we expect a 302 redirect
    $response->assertStatus(302);
});
