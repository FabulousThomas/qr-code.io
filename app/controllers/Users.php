<?php
class Users extends Controller
{
   private $data = [];
   private $userModel = '';
   private $formData = [];
   public function __construct()
   {
      $this->userModel = $this->model('User');
   }

   // REGISTER USERS
   public function register()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         // Sanitize input
         $this->formData = filteration($_POST);
         // Initialize this->data array
         $this->data = [
            'title' => 'Create an Account',
            'description' => 'Please, Register to Login',
            'name' => $this->formData['name'] ?? '',
            'username' => $this->formData['username'] ?? '',
            'email' => $this->formData['email'] ?? '',
            'password' => $this->formData['password'] ?? '',
            'name_err' => '',
            'username_err' => '',
            'email_err' => '',
            'password_err' => '',
         ];

         // Validate Inputs
         if (empty($this->data['name'])) {
            $this->data['name_err'] = 'Please, enter your full name';
         }
         if (empty($this->data['username'])) {
            $this->data['username_err'] = 'Please, enter username';
         } elseif ($this->data['username'] == $this->userModel->checkUsername($this->data['username'])) {
            $this->data['username_err'] = 'This username already exist';
         }
         if (empty($this->data['email'])) {
            $this->data['email_err'] = 'Please, enter email';
         } elseif ($this->data['email'] == $this->userModel->checkEmail($this->data['email'])) {
            $this->data['email_err'] = 'This email already exist';
         }
         // if (empty($this->data['password'])) {
         //    $this->data['password_err'] = 'Please, enter password';
         // } elseif (strlen($this->data['password']) < 6) {
         //    $this->data['password_err'] = 'password must be at least 6 characters';
         // }

         // Make sure errors are empty
         if (empty($this->data['name_err']) && empty($this->data['username_err']) && empty($this->data['email_err'])) {

            // Hash password
            // $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);

            if ($this->userModel->register($this->data)) {
               flashMsg('users_msg', '<strong>Success!</strong> You can login');
               redirect('users/login');
            } else {
               die('Something went wrong');
            }
         } else {
            // Load views with error
            $this->view('users/register', $this->data);
         }
      } else {
         $this->data = [
            'title' => 'Create an Account',
            'description' => 'Please, Register to Login',
            'name_err' => '',
            'username_err' => '',
            'email_err' => '',
            'password_err' => '',
         ];
         $this->view('users/register', $this->data);
      }
   }

   // LOGIN USERS
   public function login()
   {

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         // Sanitize inputs
         $this->formData = filteration($_POST);
         // Initialize data array
         $this->data = [
            'title' => 'Login',
            'description' => 'Please, Login to proceed',
            'email' => $this->formData['email'],
            'email_err' => '',
            'password_err' => '',
         ];

         // Validate login inputs
         // Validate email
         if ($this->userModel->checkEmail($this->data['email'])) {
            // Success
         } elseif (empty($this->data['email'])) {
            $this->data['email_err'] = 'Please, enter email';
         } else {
            // Failed
            $this->data['email_err'] = 'No user for this account';
         }

         // Email-only login: if email is valid and exists, log the user in without password

         // Make sure errors are empty
         if (empty($this->data['email_err'])) {
            $loginUsers = $this->userModel->getUserByEmail($this->data['email']);

            if ($loginUsers) {
               $this->createLoggedinSession($loginUsers);
            } else {
               $this->data['email_err'] = 'No user for this email';
               $this->view('users/login', $this->data);
            }
         } else {
            // Load views with error
            $this->view('users/login', $this->data);
         }
      } else {
         $this->data = [
            'title' => 'Login',
            'description' => 'Please, Login to proceed',
            'email' => '',
            'email_err' => '',
            'password_err' => '',
         ];
         $this->view('users/login', $this->data);
      }
   }

   // CHECK LOGGED IN USER
   public function createLoggedinSession($users)
   {
      if (is_object($users)) {
         if (isset($users->id)) {
            $_SESSION['user_id'] = $users->id;
         }
         if (isset($users->email)) {
            $_SESSION['email'] = $users->email;
         }
         if (isset($users->username)) {
            $_SESSION['username'] = $users->username;
         }
         if (isset($users->name)) {
            $_SESSION['name'] = $users->name;
         }
      }

      // redirect URL on SUCCESS ->>>  
      redirect('barcode/myCodes');
   }

   // USER LOGOUT SESSION
   public function logout()
   {
      unset($_SESSION['email']);
      unset($_SESSION['user_id']);
      unset($_SESSION['username']);
      unset($_SESSION['name']);
      session_destroy();
      redirect('users/login');
   }
}
