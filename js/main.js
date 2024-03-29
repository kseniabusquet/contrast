(function ($) {
  'use strict';

  // Spinner
  var spinner = function () {
    setTimeout(function () {
      if ($('#spinner').length > 0) {
        $('#spinner').removeClass('show');
      }
    }, 1);
  };
  spinner();

  // Initiate the wowjs
  new WOW().init();

  // Sticky Navbar
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $('.sticky-top').addClass('shadow-sm').css('top', '0px');
    } else {
      $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
    }
  });

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });
  $('.back-to-top').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
    return false;
  });

  // Modal Video
  var $videoSrc;
  $('.btn-play').click(function () {
    $videoSrc = $(this).data('src');
  });
  console.log($videoSrc);
  $('#videoModal').on('shown.bs.modal', function (e) {
    $('#video').attr(
      'src',
      $videoSrc + '?autoplay=1&amp;modestbranding=1&amp;showinfo=0'
    );
  });
  $('#videoModal').on('hide.bs.modal', function (e) {
    $('#video').attr('src', $videoSrc);
  });

  // Project and Testimonial carousel
  $('.project-carousel, .testimonial-carousel').owlCarousel({
    autoplay: true,
    smartSpeed: 1000,
    margin: 25,
    loop: true,
    center: true,
    dots: false,
    nav: true,
    navText: [
      '<i class="bi bi-chevron-left"></i>',
      '<i class="bi bi-chevron-right"></i>',
    ],
    responsive: {
      0: {
        items: 1,
      },
      576: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
    },
  });

  function displayErrorMessage(element, message) {
    element.siblings('.help-block').text(message);
  }

  function sendEmail() {
    // Check if form fields are non-empty
    var name = $('#name').val();
    var company = $('#company').val();
    var email = $('#email').val();
    var subject = $('#subject').val();
    var message = $('#message').val();
    var attachment = $('#attachment')[0].files[0]; // Get the file object

    if (name === '' || email === '' || subject === '' || message === '') {
      // Handle the case where some fields are empty
      alert('Пожалуйста, заполните обязательные поля перед отправкой.');
      return;
    }

    var formData = new FormData();
    formData.append('name', name);
    formData.append('company', company);
    formData.append('email', email);
    formData.append('subject', subject);
    formData.append('message', message);
    formData.append('attachment', attachment); // Append the file

    $('#sendMessageButton').prop('disabled', true); // Disable the button
    $('#sendMessageButton span').text('Отправка...'); // Change button text

    $.ajax({
      type: 'POST',
      url: 'php/contact.php',
      data: formData,
      contentType: false, // Don't set content type
      processData: false, // Don't process data
      success: function (response) {
        $('#alertMessage').html(response);
        $('#sendMessageButton').prop('disabled', false); // Enable the button
        $('#sendMessageButton span').text('Отправить'); // Reset button text
      },
    });
  }

  // Binding the email sending function to the button click event
  $(document).ready(function () {
    $('#sendMessageButton').click(function (event) {
      event.preventDefault(); // Prevent default form submission
      sendEmail(); // Call the function to send the email
    });

    // Additional focus function to clear alert message
    $('#name, #email, #subject, #message').focus(function () {
      $('#alertMessage').html('');
    });
  });
})(jQuery);

const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

navLinks.forEach(link => {
    link.addEventListener('click', function () {
        navLinks.forEach(navLink => navLink.parentNode.classList.remove('active'));

        this.parentNode.classList.add('active');
    });
});

const currentPage = window.location.pathname;
navLinks.forEach(link => {
    if (link.href.includes(currentPage)) {
        link.parentNode.classList.add('active');
    }
});

const buttons = document.querySelectorAll('.btn-primary');
buttons.forEach(button => {
  button.addEventListener('click', () => {
    const targetId = button.getAttribute('data-details-target');
    const target = document.querySelector(targetId);
    target.classList.toggle('collapse');
  });
});

window.addEventListener('resize', resizePdfViewer);

function resizePdfViewer() {
    var pdfViewer = document.getElementById('pdfViewer');
    var aspectRatio = 3 / 4; // Change this if your PDF has a different aspect ratio
    var containerWidth = pdfViewer.parentNode.offsetWidth;
    var containerHeight = containerWidth / aspectRatio;
    pdfViewer.style.height = containerHeight + 'px';
}

// Initial call to resize PDF viewer
resizePdfViewer();

function validateFileSize(input) {
  var maxSizeMB = 20; // Maximum file size in MB
  var maxSizeBytes = maxSizeMB * 1024 * 1024; // Convert MB to bytes
  var fileSize = input.files[0].size; // Get the size of the selected file

  var fileSizeMessage = document.getElementById('fileSizeMessage');

  if (fileSize > maxSizeBytes) {
      fileSizeMessage.innerText = 'Размер файла превышает допустимые ' + maxSizeMB + ' MB.';
      input.value = ''; // Clear the file input
  } else {
      fileSizeMessage.innerText = ''; // Clear any previous error message
  }
}
