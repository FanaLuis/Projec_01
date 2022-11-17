<?php 
    class RegisterController {
        function create() {
            $customerRepository = new CustomerRepository();
            $data = [
                "name" => $_POST["fullname"],
            "password" => password_hash($_POST["password"], PASSWORD_BCRYPT),
            "mobile" => $_POST["mobile"],
            "email" => $_POST["email"],
            "login_by" => "form",
            "is_active" => 0,
            "shipping_name" => "",
            "shipping_mobile" => "",
            "ward_id" => null,
            "housenumber_street" => "",
            ];
            if($customerRepository->save($data)){
                $_SESSION['success'] = "Đăng ký thành công";

            }else{
                $_SESSION['error'] = $customerRepository->getError();
            }
            header("location:index.php");
        }
    }
?>