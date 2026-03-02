<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_today_requires_authentication(): void
    {
        $response = $this->get('/admin/today');
        $response->assertRedirect('/login');
    }

    public function test_admin_bookings_requires_authentication(): void
    {
        $response = $this->get('/admin/bookings');
        $response->assertRedirect('/login');
    }

    public function test_admin_calendar_requires_authentication(): void
    {
        $response = $this->get('/admin/calendar');
        $response->assertRedirect('/login');
    }

    public function test_admin_settings_requires_authentication(): void
    {
        $response = $this->get('/admin/settings');
        $response->assertRedirect('/login');
    }
}
