<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f4f4f4;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-login {
            background: #007bff;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h3 class="text-center">Login</h3>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-login w-100">Login</button>
        </form>
        <p class="text-center mt-3"><a href="/signup">Sign up</a></p>
    </div>

    <script>
 
        $(document).ready(function () {
            if(localStorage.getItem('api_token')){
                    window.location.href = "/allpost"
            }else{
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                
                let email = $('#email').val();
                let password = $('#password').val();

                $.ajax({
                    url: "api/login",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        email: email,
                        password: password
                    }),
                    success: function (response) {
                 
                    Swal.fire({
    position: 'top-end', 
    icon: 'success', 
    title: 'Login Successful!',
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  });   
                        localStorage.setItem('api_token', response.token);
                        window.location.href = "/allpost";
                    },
                    error: function (xhr) {
    console.log(xhr.responseJSON);
    
    if (xhr.responseJSON.message === "you are not our user please register yourself") {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Login Failed!',
            text: "You are not our user, please register yourself",
            showConfirmButton: false,
            timer: 5000,
            toast: true,
            showCloseButton: true,
        });
    } else {
       
        let errorMessages = xhr.responseJSON.errors.map(error => `🔸 ${error}`).join('<br>');

        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Login Failed!',
            html: errorMessages, // errors ko HTML me show karna
            showConfirmButton: false,
            timer: 5000,
            toast: true,
            showCloseButton: true,
        });
    }
}

                });
            });
        }
        });
    </script>

</body>
</html>

