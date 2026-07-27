<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppHealthTest extends TestCase
{
    /**
     * Test that the 404 page renders correctly and contains expected content.
     * This simulates an end-user hitting a broken link.
     *
     * @return void
     */
    public function test_404_page_is_displayed_for_invalid_routes()
    {
        // Access a random non-existent route
        $response = $this->get('/route-does-not-exist-' . uniqid());

        $response->assertStatus(404);
        $response->assertViewIs('errors.404');
        
        // Verify the text from your blade file exists
        $response->assertSee('404');
        $response->assertSee('The page you are looking for does not exist');
        $response->assertSee('Back To Home');
    }

    /**
     * Check for broken links on the 404 page.
     * Specifically, the "Back To Home" button uses route('home').
     *
     * @return void
     */
    public function test_404_page_home_link_is_valid()
    {
        // 1. Verify the route name 'home' exists
        try {
            $homeUrl = route('home');
        } catch (\Exception $e) {
            $this->fail('The "Back To Home" link on the 404 page is broken: Route [home] not defined.');
        }

        // 2. Verify the home page actually loads (End-user test)
        $response = $this->get($homeUrl);
        $response->assertStatus(200);
    }

    /**
     * Test APIs with empty payloads/arrays to ensure robustness.
     * Add your specific API routes to the $endpoints array.
     *
     * @return void
     */
    public function test_apis_handle_empty_payloads()
    {
        // TODO: Add your project's API endpoints here
        $endpoints = [
            // ['method' => 'POST', 'uri' => '/api/login'],
            // ['method' => 'POST', 'uri' => '/api/contact'],
        ];

        if (empty($endpoints)) {
            $this->markTestSkipped('No API endpoints defined in AppHealthTest for empty payload testing.');
        }

        foreach ($endpoints as $endpoint) {
            $response = $this->json($endpoint['method'], $endpoint['uri'], []);

            // We expect validation errors (422) or success (200), but NEVER a crash (500)
            $this->assertTrue(
                in_array($response->status(), [200, 401, 403, 404, 422]),
                "API Endpoint {$endpoint['uri']} crashed or returned unexpected status {$response->status()} with empty payload."
            );
        }
    }
}