<?php

/*
|--------------------------------------------------------------------------
| Authentication Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

return [
    'internal_server_error' =>          'Internal Server Error',
    //authentication
    'verification_link_sent'=>          'Verification link sent to your email',
    'login_success'=>                   'Login Successfully',
    'logout_success'=>                  'Logout Successfully',
    'email_verified'=>                  'Email already Verified',
    'email_has_verified'=>              'Email has been verified',
    'email_not_verified'=>              'Email not Verified',
    'wrong_credential' =>               'Email or Password are incorrect',
    'send_forget_password_code' =>      'A Verification Code Send to Your email',
    'user_not_found_email' =>           'No User Found with this email',

    //forget password
    'code_invalid' =>                   'Verification code invalid',
    'code_correct' =>                   'Verification code correct',
    'new_password'=>                    'Password Updated Successfully',
    'code_not_verified'=>               'Code is not verified',

    //reset password
    'old_password_req'=>                'Old password required',
    'new_password_req'=>                'New password required',
    'old_password_min'=>                'Old password must be greater than 8 character',
    'new_password_min'=>                'New password must be greater than 8 character',
    'invalid_password'=>                'Old password is incorrect',
    'password_reset_success'=>          'Password rested successfully',

    //picnic
    'picnic_created'=>                  'Picnic Created Successfully',
    'member_added'=>                    'Members added Successfully',
    'not_admin_friend'=>                ':user is not a friend of picnic admin',

    //friends
    "add_friend"=>                     'User added to your friend list',
    "remove_friend"=>                  'User removed from your friend list'
];
