<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Galerie d'images</title>
    <style>
    body {
        background-color: black;
        color: white; 
    }
    form {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background: #f4f4f4;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    form label, .navbar a {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }
    form p {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px; 
    }
    input[type="file"] {
        border: 2px solid #ddd;
        padding: 4px;
        display: block;
        margin-bottom: 20px;
        cursor: pointer;
    }
    input[type="submit"] {
        background: #007bff;
        color: #ffffff;
        border: 0;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    input[type="submit"]:hover {
        background: #0056b3;
    }
    #imagePreview img {
        max-width: 200px; 
        max-height: 200px; 
        margin-right: 10px; 
        padding: 10px;

    }

    form p {
    font-size: 16px;
    color: #333; 
    margin-bottom: 10px; 
}

</style>

</head>
<body>
 
    <nav class="navbar">
        <div class="navbar-container">
            <a href="galerie.php" class="nav-link">Galerie</a>
         
        </div>
    </nav>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <p>Sélectionnez les images à uploader :</p>
        <input type="file" name="images[]" multiple onchange="previewImages();">
        <input type="submit" value="Uploader les images" name="submit">
        <div id="imagePreview"></div>
    </form>
    

    <script>
        function previewImages() {
            var imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = ''; 

            var files = document.querySelector('input[type="file"]').files; 

            for(var i = 0; i < files.length; i++) {
                var file = files[i];
                
                if(file.type.startsWith('image/')){
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        var img = document.createElement('img'); 
                        img.src = e.target.result; 
                        imagePreview.appendChild(img); 
                    }
                    
                    reader.readAsDataURL(file); 
                }
            }
        }
    </script>
</body>
</html>
