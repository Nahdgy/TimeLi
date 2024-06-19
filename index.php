<!DOCTYPE html>
<html lang="en">

<head>
  <title>TimeLI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    html,
    body {
      position: relative;
      height: 100%;
    }

    body {
      background: #613A91;
      font-family: "Chango", sans-serif;
      font-size: 14px;
      color: #FFF8ED;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    swiper-container {
      width: 80%;
      height: 70%; 
      background-color: #8F49E6;
    }

    swiper-slide {
      text-align: center;
      font-size: 32px;
      background: #8F49E6;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
</head>
  <body>
  <swiper-container class="mySwiper" init="false">
    <swiper-slide>Bienvenue sur TimeLI, ta plateforme qui te permet de créer ta playlist parfaite</swiper-slide>
    <swiper-slide>
    <i class="fa-regular fa-face-laugh-beam"></i><i class="fa-regular fa-face-grin-stars"></i><i class="fa-regular fa-face-frown-open"></i>
        <p>Choisis ton mood du moment.</p>
    </swiper-slide>
    <swiper-slide>Choisis ton style de musique</swiper-slide>
    <swiper-slide>Choisis ton pays de référence musicale</swiper-slide>
    <swiper-slide>Et enfin choisis la durée de ta playlist
    Let’s GO !!</swiper-slide>
  </swiper-container>
  
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
  <!-- Initialize Swiper -->
  <script>
    const swiperEl = document.querySelector('swiper-container')

    const params = {
      injectStyles: [`
      .swiper-pagination-bullet {
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        font-size: 12px;
        color: #000;
        opacity: 1;
        background: #9D92F0;
      }

      .swiper-pagination-bullet-active {
        width: 30px;
        height: 30px;
        color: #fff;
        background: #7D6DEB;
      }
      `],
      pagination: {
        clickable: true,
        renderBullet: function (index, className) {
          return '<span class="' + className + '">' + "</span>";
        },
      },
    }

    Object.assign(swiperEl, params)

    swiperEl.initialize();
  </script>
  </body>
</html>