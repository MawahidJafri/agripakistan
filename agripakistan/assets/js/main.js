(function () {
  "use strict";

  /**
   * Helper function to check element existence and add event listener
   */
  function addEventListenerIfExists(selector, event, callback) {
    const element = document.querySelector(selector);
    if (element) {
      element.addEventListener(event, callback);
    } else {
      console.warn(`Element with selector "${selector}" not found.`);
    }
  }

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (
      selectHeader &&
      (selectHeader.classList.contains('scroll-up-sticky') ||
        selectHeader.classList.contains('sticky-top') ||
        selectHeader.classList.contains('fixed-top'))
    ) {
      window.scrollY > 100
        ? selectBody.classList.add('scrolled')
        : selectBody.classList.remove('scrolled');
    }
  }
  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const addMobileNavListener = () => {
    const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
    if (mobileNavToggleBtn) {
      console.log('Adding mobile nav listener.');
      mobileNavToggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('mobile-nav-active');
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
      });
    } else {
      console.warn('Mobile nav toggle button not found!');
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    addMobileNavListener();

    // Observe DOM changes for dynamically added mobile nav toggle button
    const observer = new MutationObserver(() => {
      addMobileNavListener();
    });
    observer.observe(document.body, { childList: true, subtree: true });
  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach((navmenu) => {
    navmenu.addEventListener('click', function (e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
    });
  });

  // Initialize Swiper after page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for your specific element
    var swiper = new Swiper('.swiper', {
        loop: true,
        speed: 600,
        autoplay: {
            delay: 5000
        },
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true
        },
        navigation: {
            nextEl: '.js-custom-next',
            prevEl: '.js-custom-prev'
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 40
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 40
            }
        }
    });
});

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => preloader.remove());
  }

  /**
   * Auto-generate carousel indicators
   */
  document.querySelectorAll('.carousel-indicators').forEach((carouselIndicator) => {
    const carousel = carouselIndicator.closest('.carousel');
    if (carousel) {
      carousel.querySelectorAll('.carousel-item').forEach((carouselItem, index) => {
        const isActive = index === 0 ? 'class="active"' : '';
        carouselIndicator.innerHTML += `<li data-bs-target="#${carousel.id}" data-bs-slide-to="${index}" ${isActive}></li>`;
      });
    }
  });

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({ selector: '.glightbox' });

  /**
   * Update dropdowns based on user role
   */
  function updateDropdowns(role) {
    document.querySelectorAll('.buyer-only').forEach((el) => {
      el.style.display = role === 'buyer' ? 'block' : 'none';
    });
    document.querySelectorAll('.seller-only').forEach((el) => {
      el.style.display = role === 'seller' ? 'block' : 'none';
    });
  }

  const userRole = sessionStorage.getItem('userRole') || 'buyer';
  updateDropdowns(userRole);
})();
