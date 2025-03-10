<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Form</title>
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
        <h3 class="text-center">SignUp</h3>
        <form id="loginForm">
            <div class="mb-3">
                <label for="name" class="form-label"><i class="fas fa-envelope"></i> Name</label>
                <input type="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
              <input type="email" class="form-control" id="email" required>
          </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-login w-100">SignUp</button>
        </form>
        <p class="text-center mt-3"><a href="/">Log In</a></p>
    </div>

    <script>
                           
        $(document).ready(function () {
         
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                
                let email = $('#email').val();
                let password = $('#password').val();
                let name = $('#name').val();

                $.ajax({
                    url: "api/signup",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        email: email,
                        password: password,
                        name:name
                    }),
                    success: function (response) {
                       
                        Swal.fire({
  title:`welcome ${response.user.name}` ,
  width: 600,
  padding: "3em",
  color: "#716add",
  background: "#fff url(https://media3.giphy.com/media/YZevTvT9z9ubjeyCck/giphy.gif?cid=6c09b9524lzjz15mxvabaoack7rls966rm9r6knj15v2rv6g&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g)",

}); 

          
                        Swal.fire({
    position: 'top-end', 
    icon: 'success', 
    title: 'SignUp Successfully!',
    text:response.message,
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
                  
                        window.location.href = "/";
                    },
                    error: function (xhr) {
                        alert("SignUp Failed!");
                        console.log(xhr);

                        if(xhr.responseJSON.message = "please check credential"){
                          let errorMessages = xhr.responseJSON.errors.map(error => `🔸 ${error}`).join('<br>');

Swal.fire({
    position: 'top-end',
    icon: 'error',
    title: 'Login Failed!',
    html: errorMessages, 
    showConfirmButton: false,
    timer: 5000,
    toast: true,
    showCloseButton: true,
});
                        }
                    }
                });
            });
        
        });
    </script>

</body>
</html>
