<?php

    
class forgotpassword extends REST {

    public function __construct() {
        /* Parent Constructor */
        parent::__construct();    
    }

    public function forgotpassword() {
        if ($this->get_request_method() != "PUT") {
            $this->response('', 406);
        } else {
            /* Calling Reset functions to reset password */
            self::resetpassword();
        }
    }

    public function resetpassword() {
        /* Getting Email Address from request vaiable */
        $email = $this->_request['user_email'];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            /* Logic for genrating random code */
            $random = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*_';
            $randomString = '';
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $random[rand(0, strlen($random) - 1)];
            }
            $u_id = db_get_row("select user_id from cscart_users where email='$email'");
            if(empty($u_id))
            {
                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_email_not_exists'));
                $this->response($this->json($error), 200);
            }
            /* Assigning random code to api_recover_password in api_recover_password.tpl */
            Registry::get('view_mail')->assign('api_recover_password', $randomString);
            /* Sending Recovery mail to user email, api_recover_password.tpl */
            fn_instant_mail($email, Registry::get('settings.Company.company_users_department'), 'profiles/recover_password_subj.tpl', 'profiles/api_recover_password.tpl');
            try {
                /* Getting user id from email */
                $user_id = $u_id['user_id'];
                /* Updating user_password */
                $pass_update = db_query("update cscart_users set password = md5('$randomString') where 
                                                            user_id= $user_id");
            } catch (Exception $e) {
                $error = array('status' => "Failed", "msg" => $e);
                $this->response($this->json($error), 200);
            }
            /* Success Message */
            $error = array('status' => "Success", "msg" => fn_get_lang_var('api_password_sent'));
            $this->response($this->json($error), 200);
        } else {
            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_email'));
            $this->response($this->json($error), 200);
        }
    }

                    /*
         *  Encode array into JSON
         */

        function json($data){
            if(is_array($data)){
                return json_encode($data);
            }
        }

}

?>
