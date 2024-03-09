<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Galerie d'images</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body{
            background-color: black;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
            margin-top: 20px;
        }

        .gallery img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid white;
        }

        .img-container {
            position: relative;
            display: inline-block;
        }

        .delete-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: red;
            font-size: 24px;
            background: #fff;
            border-radius: 50%;
            padding: 0 5px;
        }

        #lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
        }

        #lightbox img {
            max-width: 90%;
            max-height: 90%;
        }

        #close {
            position: absolute;
            top: 20px;
            right: 30px;
            cursor: pointer;
            color: #fff;
            font-size: 40px;
        }

        .delete-icon .fas {
    color: red; 
    cursor: pointer; 
  
    
}



    </style>
</head>
<body>

<div class="gallery">
    <?php
    $directory = 'uploads/';
    $images = glob($directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach($images as $image) {
        $imageName = basename($image);
        echo '<div class="img-container">';
        echo '<img src="'. $image .'" alt="" class="gallery-image">';

        echo '<span class="delete-icon" data-image="'.$imageName.'"><i class="fas fa-trash"></i></span>';
        echo '</div>';
    }
    ?>
</div>


<div id="lightbox">
    <span id="close">&times;</span>
    <img id="lightbox-img" src="">
</div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll('.gallery-image');

    images.forEach(image => {
        image.addEventListener('click', function() {
            const lightboxImage = document.getElementById('lightbox-img');
            lightboxImage.src = this.src;
            document.getElementById('lightbox').style.display = 'flex';
        });
    });

    document.getElementById('close').addEventListener('click', function() {
        document.getElementById('lightbox').style.display = 'none';
    });

    document.querySelectorAll('.delete-icon').forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const imageName = this.getAttribute('data-image');
            if (confirm("Êtes-vous sûr de vouloir supprimer cette image ?")) {
                fetch('delete_image.php', {
                    method: 'POST',
                    body: JSON.stringify({ image: imageName }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.parentElement.remove();
                    } else {
                        alert("Erreur lors de la suppression de l'image.");
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>
