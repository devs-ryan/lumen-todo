<?php

use App\Models\TodoNote;
use App\Models\User;

class AppTest extends TestCase
{

    /**
     * The version of the api to test for
     *
     * @var string
     */
    protected $api_version = 'v1.0';

    /**
     * For storing the test users api token
     *
     * @var mixed
     */
    protected $api_token = null;

    /**
     * Test user creds
     *
     * @var array
     */
    protected $test_user_creds = [
        'username' => 'test_user',
        'password' => 'password',
    ];

    /**
     * Checks the / route for expected response
     *
     * @return void
     */
    public function testHomeRoute()
    {
        $this->get('/');

        $this->assertEquals(
            env('APP_NAME') . '<BR>' . $this->app->version(), $this->response->getContent()
        );
    }

    /**
     * Register the test user
     *
     * @return void
     */
    public function testRegister()
    {
        // delete user from previous tests
        User::where('username', $this->test_user_creds['username'])->delete();

        $this->json('POST', '/api/' . $this->api_version . '/register', $this->test_user_creds)
            ->seeJson([
                'action' => 'register',
            ]);

        $this->response->dump();
    }

    /**
     * Login the test user
     *
     * @return void
     */
    public function testLogin($dump_response = true)
    {
        $this->json('POST', '/api/' . $this->api_version . '/login', $this->test_user_creds)
            ->seeJson([
                'action' => 'login',
            ]);

        $this->api_token = json_decode($this->response->getContent())->user->api_token;

        if ($dump_response)
            $this->response->dump();
    }

    /**
     * Create a todo Note
     *
     * @return void
     */
    public function testTodoCreate()
    {
        $this->testLogin(false);

        $this->json('POST', '/api/' . $this->api_version . '/todo-notes', [
            'content' => 'Testing ticket submit',
            'api_token' => $this->api_token
        ])
            ->seeJson([
                'action' => 'create',
            ]);

        $this->response->dump();
    }

    /**
     * Update a todo Note
     *
     * @return void
     */
    public function testTodoUpdate()
    {
        $this->testLogin(false);

        $latest_todo = TodoNote::latest()->first();

        $this->json('PATCH', '/api/' . $this->api_version . '/todo-notes/' . $latest_todo->id, [
            'complete' => true,
            'api_token' => $this->api_token
        ])
            ->seeJson([
                'action' => 'update',
            ]);

        $this->response->dump();
    }

    /**
     * Get all Todo notes for the test user
     *
     * @return void
     */
    public function testTodoIndex()
    {
        $this->testLogin(false);

        $this->json('GET', '/api/' . $this->api_version . '/todo-notes', [
            'api_token' => $this->api_token
        ])
            ->seeJson([
                'action' => 'index',
            ]);

        $this->response->dump();
    }

    /**
     * Delete a todo Note
     *
     * @return void
     */
    public function testTodoDelete()
    {
        $this->testLogin(false);

        $latest_todo = TodoNote::latest()->first();

        $this->json('DELETE', '/api/' . $this->api_version . '/todo-notes/' . $latest_todo->id, [
            'api_token' => $this->api_token
        ])
            ->seeJson([
                'action' => 'delete',
            ]);

        $this->response->dump();
    }
}
