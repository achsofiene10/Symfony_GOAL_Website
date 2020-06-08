    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#file_upload')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    
    
    function cacherDiv() {
        document.getElementById("notification").style.display = "none";
    }
    var slideIndex = 1;

    showSlide(slideIndex);
    
    
    function plusSlides(n){
    
    showSlide(slideIndex += n);
    
    }
    
    
    function currentSlide(n) {
    
    showSlide(slideIndex = n);
    
    }
    
    
    function showSlide(n){
    
    var i;
    
    var slides = document.getElementsByClassName("myslides");
    
    var dots = document.getElementsByClassName("dots");
    
    if (n > slides.length) { slideIndex = 1};
    
    if (n < 1) { slideIndex = slides.length};
    
    for (i=0;i<slides.length;i++) {
    
    slides[i].style.display = "none";
    
    };
    
    for (i=0;i<dots.length;i++) {
    
    dots[i].className = dots[i].className.replace(" active","");
    
    };
    
    slides[slideIndex-1].style.display = "block";
    
    dots[slideIndex-1].className += " active";
    
    }
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input2').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
            
  
    
    /*==================================================================
    [ Validate ]*/
    var name = $('.validate-input input[name="name"]');
    var email = $('.validate-input input[name="email"]');
    var message = $('.validate-input textarea[name="message"]');


    $('.validate-form').on('submit',function(){
        var check = true;

        if($(name).val().trim() == ''){
            showValidate(name);
            check=false;
        }


        if($(email).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
            showValidate(email);
            check=false;
        }

        if($(message).val().trim() == ''){
            showValidate(message);
            check=false;
        }

        return check;
    });


    $('.validate-form .input2').each(function(){
        $(this).focus(function(){
           hideValidate(this);
       });
    });

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    /*---------------------------------------------------------------------------------------------------------------------*/
    



})(jQuery);