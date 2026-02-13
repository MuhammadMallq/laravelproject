<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

// Create a concrete class for testing the abstract Controller
class TestableController extends Controller
{
    public function testSuccessRedirect(string $route, string $message, string $fragment = '')
    {
        return $this->successRedirect($route, $message, $fragment);
    }

    public function testErrorRedirect(string $route, string $message)
    {
        return $this->errorRedirect($route, $message);
    }

    public function testJsonSuccess(mixed $data = null, string $message = 'Berhasil', int $code = 200)
    {
        return $this->jsonSuccess($data, $message, $code);
    }

    public function testJsonError(string $message = 'Gagal', int $code = 500)
    {
        return $this->jsonError($message, $code);
    }
}

class ControllerTest extends TestCase
{
    private TestableController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new TestableController();
    }

    public function test_success_redirect_returns_redirect_response()
    {
        $response = $this->controller->testSuccessRedirect('home', 'Success message');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Success message', session('success'));
    }

    public function test_success_redirect_with_fragment()
    {
        $response = $this->controller->testSuccessRedirect('home', 'Success', 'section');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContains('#section', $response->getTargetUrl());
    }

    public function test_error_redirect_returns_redirect_response()
    {
        $response = $this->controller->testErrorRedirect('home', 'Error message');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Error message', session('error'));
    }

    public function test_json_success_returns_json_response()
    {
        $response = $this->controller->testJsonSuccess(['id' => 1], 'Data loaded');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Data loaded', $data['message']);
        $this->assertEquals(['id' => 1], $data['data']);
    }

    public function test_json_success_with_custom_code()
    {
        $response = $this->controller->testJsonSuccess(null, 'Created', 201);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_json_error_returns_json_response()
    {
        $response = $this->controller->testJsonError('Something went wrong', 500);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Something went wrong', $data['message']);
    }

    public function test_json_error_with_custom_code()
    {
        $response = $this->controller->testJsonError('Not found', 404);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Helper method to check if string contains substring
     */
    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' contains '$needle'"
        );
    }
}
