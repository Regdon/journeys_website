<?php
    class Users extends Controller {
        public function __construct() {
            $this->userModel = $this->model('User');
        }

        public function register() {
            //Check for php POST
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Process form

                //Sanitise POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Init data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => ''
                ];

                //Validate Email
                if (empty($data['email'])) {
                    $data['email_error'] = 'Please enter email';
                } else {
                    //check email
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_error'] = 'Email is already taken';
                    }
                }

                //Validate Name
                if (empty($data['name'])) {
                    $data['name_error'] = 'Please enter name';
                }

                //Validate Password
                if (empty($data['password'])) {
                    $data['password_error'] = 'Please enter password';
                } elseif(strlen($data['password']) < 6) {
                    $data['password_error'] = 'Password must be atleast 6 characters';                    
                }

                //Validate Confirm Password
                if (empty($data['confirm_password'])) {
                    $data['confirm_password_error'] = 'Please confirm password';
                } elseif($data['password'] <> $data['confirm_password']) {
                    $data['confirm_password_error'] = 'Password do not match';                   
                }

                //Make sure errors are empty
                if (
                       empty($data['name_error'])
                    && empty($data['email_error'])
                    && empty($data['password_error'])
                    && empty($data['confirm_password_error'])
                ) {
                    //Validated
                    
                    //Hash Password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    //Register User
                    if ($this->userModel->register($data)) {
                        redirect('users/login');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    //Validation failed
                    $this->view('users/register', $data);
                }

            } else {
                //Init data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => ''
                ];

                //Load view
                $this->view('users/register', $data);
            }
        }

        public function login() {
            //Check for php POST
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Process form
                
                //Sanitise POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Init data
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_error' => '',
                    'password_error' => ''
                ];

                //Validate Email
                if (empty($data['email'])) {
                    $data['email_error'] = 'Please enter email';
                }

                //Validate Password
                if (empty($data['password'])) {
                    $data['password_error'] = 'Please enter password';
                } elseif(strlen($data['password']) < 6) {
                    $data['password_error'] = 'Password must be atleast 6 characters';                    
                }

                //Make sure errors are empty
                if (
                       empty($data['email_error'])
                    && empty($data['password_error'])
                ) {
                    //Validated
                    die('Success');
                } else {
                    //Validation failed
                    $this->view('users/login', $data);
                }

            } else {
                //Init data
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_error' => '',
                    'password_error' => ''
                ];

                //Load view
                $this->view('users/login', $data);
            }
        }
    }
?>