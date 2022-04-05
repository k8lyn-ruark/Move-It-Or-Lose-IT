


//code taken from https://dev.to/codingnepal/automatic-image-slideshow-effect-in-html-css-javascript-3g33
var indexValue = 0;
      function slideShow(){
        setTimeout(slideShow, 5000);
        var x;
        const img = document.getElementsByClassName("slideshow-img");
        for(x = 0; x < img.length; x++){
          img[x].style.display = "none";
        }
        indexValue++;
        if(indexValue > img.length){indexValue = 1}
        img[indexValue -1].style.display = "block";
      }
slideShow();