<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            
            <a href="/adduser" class="btn btn-primary" id="addNew">Add New</a>
            <button class="btn btn-danger" id="logout">Logout</button>
        </div>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="postTableBody">
           
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



{{-- update post modal --}}

<div class="modal fade" id="updatePostModal" tabindex="-1" aria-labelledby="updatePostModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="updatePostModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     
     <form id="updateForm">
        <input type="number" hidden class="form-control mb-3" placeholder="id" id="updateId">
        <input type="text" class="form-control mb-3" placeholder="Title" id="updateTitle">
        <input type="text" class="form-control mb-3" placeholder="Description" id="updateDescription">
        <img src="" alt="" width="100px" id="showImage">
        <input type="file" class="form-control mb-3" placeholder="Image" id="updateImage">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" value="saveChanges" id="updatePost">
      </form>
      </div>
    </div>
  </div>
</div>







 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
if(!localStorage.getItem('api_token')){
window.location.href = '/allpost';
}

$('#logout').on('click', function () {
  const Token = localStorage.getItem('api_token'); 

  fetch('/api/logout', {
    method: 'POST',
    headers: { 
      'Authorization': `Bearer ${Token}`,
      'Content-Type': 'application/json' 
    }
  })
  .then(response => response.json())
  .then(data => {
    // console.log(data);

    localStorage.removeItem('api_token'); 
    Swal.fire({
    position: 'top-end', 
    icon: 'success', 
    title: 'Logout Successful!',
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 

    window.location.href = '/'; 
  })
  .catch(error =>
  { 
    console.error('Error:', error)
    Swal.fire({
    position: 'top-end', 
    icon: 'error', 
    title: error.message,
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
  });
});


(function () {
   fetch('api/posts',
    {
      method :"GET",
        headers:{
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`
        }
    }
   )

   .then(response=>response.json())
   .then(data => {
       
      //  console.log(data.data.post);
  var allpost = data['data']['post'];
       allpost.forEach(post => {
             $('#postTableBody').append(
            `
            <tr>
   <td><img src="/uploads/${post.image}" width="200px" alt=""></td>
              <td>${post.title}</td>
              <td>${post.description}</td>
              <td>
                <button class="btn btn-primary" data-bs-id=${post.id} data-bs-toggle="modal" data-bs-target="#exampleModal">viw</button>
                <button class="btn btn-danger"onclick="deletePost(${post.id})" >delete</button>
                <button class="btn btn-info" data-bs-id=${post.id} data-bs-toggle="modal" data-bs-target="#updatePostModal">update</button>
            </td>
            </tr>
            `
        );
        
       });
   }).catch(error=>{
    console.log("error",error)
   })
})();

const exampleModal = document.getElementById('exampleModal')
if (exampleModal) {
  exampleModal.addEventListener('show.bs.modal', event => {
    $('.modal-body').empty();
    const button = event.relatedTarget
    const id = button.getAttribute('data-bs-id');
    fetch(`api/posts/${id}`,
    {
      method:"GET",
        headers:{
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`
        }
    }
   ).then(response=>response.json()).then(data=>{
    // console.log(data.data.post[0]);
    let post = data.data.post[0];

    $('.modal-body').append(
      `
      title:${post['title']}
      <br>
      description:${post['description']}
      <br>
      image:<img src="/uploads/${post['image']}" width="100px" alt="">
      `
    );
    
   })

  })
}


// update model work

const updatePostModal = document.getElementById('updatePostModal')
if (updatePostModal) {
  updatePostModal.addEventListener('show.bs.modal', event => {
    // $('#updatePostModal .modal-body').empty();
    const button = event.relatedTarget
    const id = button.getAttribute('data-bs-id');
    fetch(`api/posts/${id}`,
    {
      method:"GET",
        headers:{
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`
        }
    }
   ).then(response=>response.json()).then(data=>{
    console.log(data.data.post[0]);
    let post = data.data.post[0];

    $('#updateId').val(post['id']);
    $('#updateTitle').val(post['title']);
    $('#updateDescription').val(post['description']);
    $('#showImage').attr('src', `/uploads/${post.image}`);

    
   })

  })
}
// update post form submit event listener

$('#updateForm').on('submit', async function (e) {
  e.preventDefault();

  let postId = $('#updateId').val();

  const formData = new FormData();
            formData.append('id', $('#updateId').val());
            formData.append('title', $('#updateTitle').val());
            formData.append('description', $('#updateDescription').val());

        
            if( !document.querySelector('#updateImage').files[0] == ""){
              
              formData.append('image', $('#updateImage')[0].files[0]);
            }

            fetch(`/api/posts/${postId}`, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                    'X-HTTP-Method-Override':'PUT'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
              Swal.fire({
    position: 'top-end', 
    icon: 'success', 
    title: 'Post Update Successfully!',
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
                window.location.href = '/allpost'; 
            })
            .catch(error => {
              console.error('Error:', error.message)
              Swal.fire({
    position: 'top-end', 
    icon: 'info', 
    title: error.message,
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
            });
          });


// delete post e
async function deletePost(Id){
// console.log(id);
await fetch(`/api/posts/${Id}`, {
  'method':"DELETE",
  'headers':{
    'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
  }
}).then(response => response.json()).then(data =>{
  // console.log(data);
  Swal.fire({
    position: 'top-end', 
    icon: 'warning', 
    title: 'post delete succesfully',
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
  window.location.href = '/allpost';
}).catch(err=>{
  // console.log(err.message);

  Swal.fire({
    position: 'top-end', 
    icon: 'error', 
    title: err.message,
    showConfirmButton: false, 
    timer: 5000, 
    toast: true, 
    showCloseButton: true, 
  }); 
})
}
    </script>
       
</body>
</html>
