<?php

it('redirects when accessing system center distributions without authentication', function () {
    $response = $this->get('systemcenterdistributions');
    
    $response->assertStatus(302);
});
