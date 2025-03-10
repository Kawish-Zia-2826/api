<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Add New Post</h2>
            
            <form id="postForm">
                <!-- Title Input -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter title" >
                </div>

                <!-- Description Input -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="4" placeholder="Enter description" ></textarea>
                </div>

                <!-- Image Input -->
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="image" >
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('#postForm').on('submit', async function(event) {
            event.preventDefault(); // Form ko reload hone se roko
            
            const formData = new FormData();
            formData.append('title', $('#title').val());
            formData.append('description', $('#description').val());
            formData.append('image', $('#image')[0].files[0]);

            fetch('/api/posts', {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${localStorage.getItem('api_token')}`
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // alert(data.message);
                console.log(data);
                window.location  .href = '/allpost'; 
                    if(!data.status){
                        let errorMessages = data.data.map(error => `🔸 ${error}`).join('<br>');

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
                    if(data.status){
                        Swal.fire({
    position: 'top-end', 
    icon: 'success', 
    title: 'user add Successful!',
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
                    }


            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
