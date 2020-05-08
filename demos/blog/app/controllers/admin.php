<?php

class AdminController
{
	public function __construct()
	{
		$this->admin = load_model('admin');
	}

	public function index(): void
	{
		if (session_exists('admin')) {
			redirect_to('dashboard');
		}

		load_template(
			'login',
			'login',
			array(
				'page_title' => 'The Mount Everest Blog - Login',
				'page_description' => 'Administration login page'
			)
		);
	}

	public function login(): void
	{
        $username = HttpRequests::post('username');
        $password = HttpRequests::post('password');

        if (empty($username) || empty($password)) {
            redirect_to('admin');
        }

		$username = sanitize_input($username);
		$password = sanitize_input($password);

		if (!$this->admin->login($username, $password)) {
			redirect_to('admin');
		}

		create_session('admin', '');
		redirect_to('dashboard');
	}

	public function logout(): void
	{
		close_session('admin');
		redirect_to('admin');
	}
}
