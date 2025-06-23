<?php

namespace Tests\Feature\Http\Controllers\Configuration;

use Tests\TestCase;

class SystemCenterDistributionControllerTest extends TestCase
{
    /** @test */
    public function show_view_index()
    {
        $this->get('systemcenterdistributions')->assertStatus(302);
    }


}
