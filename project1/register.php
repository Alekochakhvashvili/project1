<?php
require_once "core/init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
    $validation = new Validation();
    $validation = $validation->check($_POST, array(
        'Username' => array(
            'required' => true,
            'min' => 3,
            'max' => 20,
            'unique' => 'users'
        ),
        'Password' => array(
            'required' => true,
            'min' => 6
        ),
        'Confirm_Password' => array(
            'required' => true,
            'matches' => 'Password'
        ),
        'First_Name' => array(
            'required' => true,
            'min' => 3,
            'max' => 50
        ),
        'Last_Name' => array(
            'required' => true,
            'min' => 3,
            'max' => 50
        )
        ));
    if($validation->passed()) {
       $user = new User();

       $salt = Hash::salt(32);

        try {
            $user->create(array(
                'username' => Input::get('Username'),
                'password' => Hash::make(Input::get('Password'), $salt),
                'salt' => $salt,
                'first_name' => Input::get('First_Name'),
                'last_name' => Input::get('Last_Name'),
                'joined' => date('Y-m-d H:i:s'),
                'group' => 1
            ));

            Session::flash('home', 'You have been registered and can now log in!');
            Redirect::to('index.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    } else {
        foreach($validation->errors() as $error) { //error output
            echo $error, '<br>';
        }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<form action="" method="post">
    <div class="field">
        <label for="Username">Username</label>
        <input type="text" name="Username" id="Username" placeholder="Type your Username" value="<?php echo escape(Input::get('Username'));?>">
    </div>

    <div class="field">
        <label for="Password">Password</label>
        <input type="password" name="Password" id="Password" placeholder="Type your password">
    </div>

    <div class="field">
        <label for="Confirm_Password">Confirm Password</label>
        <input type="password" name="Confirm_Password" id="Confirm_Password" placeholder="Type your password again">
    </div>

    <div class="field">
        <label for="First_Name">First name</label>
        <input type="text" name="First_Name" id="First_Name" placeholder="Type your first name" value="<?php echo escape(Input::get('First_Name'));?>">
    </div>

    <div class="field">
        <label for="Last_Name">Last name</label>
        <input type="text" name="Last_Name" id="Last_Name" placeholder="Type your last name" value="<?php echo escape(Input::get('Last_Name'));?>">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" name="submit" value="Register">
</form>
</body>
</html>