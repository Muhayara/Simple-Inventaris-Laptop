<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_halaman_utama_diarahkan_ke_daftar_laptop(): void
    {
        $this->get('/')->assertRedirect('/laptop');
    }
}
