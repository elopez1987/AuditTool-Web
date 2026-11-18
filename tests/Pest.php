<?php

declare(strict_types=1);
use Tests\TestCase;

require_once __DIR__.'/TestCase.php';

pest()->extend(TestCase::class)->in('Feature');
