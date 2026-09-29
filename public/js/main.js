(function () {
  "use strict";

  // Preloader
  window.addEventListener('load', function () {
    const preloader = document.getElementById('back__preloader');
    if (preloader) {
      setTimeout(() => {
        preloader.style.transition = 'opacity 0.4s ease';
        preloader.style.opacity = '0';
        setTimeout(() => preloader.style.display = 'none', 400);
      }, 600);
    }
  });

  document.addEventListener('DOMContentLoaded', function () {

    /* ====== Mobile Off Canvas Menu ====== */
    function headermobileAside() {
      const navbarTriggers = document.querySelectorAll('.mobile-aside-button');
      const endTriggers = document.querySelectorAll('.mobile-aside-close');
      const container = document.querySelector('.mobile-off-canvas-active');
      const wrapper = document.querySelector('.wrapper');

      if (wrapper && !wrapper.querySelector('.body-overlay')) {
        wrapper.insertAdjacentHTML('afterbegin', '<div class="body-overlay"></div>');
      }

      const overlays = document.querySelectorAll('.body-overlay');

      navbarTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
          e.preventDefault();
          if (container) container.classList.add('inside');
          if (wrapper) wrapper.classList.add('overlay-active');
        });
      });

      endTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
          if (container) container.classList.remove('inside');
          if (wrapper) wrapper.classList.remove('overlay-active');
        });
      });

      overlays.forEach(overlay => {
        overlay.addEventListener('click', function () {
          if (container) container.classList.remove('inside');
          if (wrapper) wrapper.classList.remove('overlay-active');
        });
      });
    }
    headermobileAside();

    /* ====== Mobile Submenu Accordion ====== */
    // Helper functions for slideUp/slideDown
    const slideUp = (target, duration = 500) => {
      target.style.transitionProperty = 'height, margin, padding';
      target.style.transitionDuration = duration + 'ms';
      target.style.boxSizing = 'border-box';
      target.style.height = target.offsetHeight + 'px';
      target.offsetHeight;
      target.style.overflow = 'hidden';
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      window.setTimeout(() => {
        target.style.display = 'none';
        target.style.removeProperty('height');
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
      }, duration);
    }

    const slideDown = (target, duration = 500) => {
      target.style.removeProperty('display');
      let display = window.getComputedStyle(target).display;
      if (display === 'none') display = 'block';
      target.style.display = display;
      let height = target.offsetHeight;
      target.style.overflow = 'hidden';
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      target.offsetHeight;
      target.style.boxSizing = 'border-box';
      target.style.transitionProperty = "height, margin, padding";
      target.style.transitionDuration = duration + 'ms';
      target.style.height = height + 'px';
      target.style.removeProperty('padding-top');
      target.style.removeProperty('padding-bottom');
      target.style.removeProperty('margin-top');
      target.style.removeProperty('margin-bottom');
      window.setTimeout(() => {
        target.style.removeProperty('height');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
      }, duration);
    }

    const offCanvasNav = document.querySelector('.mobile-menu');
    if (offCanvasNav) {
      const subMenus = offCanvasNav.querySelectorAll('.dropdown');

      subMenus.forEach(subMenu => {
        const parent = subMenu.parentElement;
        if (!parent.querySelector('.menu-expand')) {
          parent.insertAdjacentHTML('afterbegin', '<span class="menu-expand"><i></i></span>');
        }
        subMenu.style.display = 'none';
      });

      offCanvasNav.addEventListener('click', function (e) {
        const target = e.target.closest('a, .menu-expand');
        if (!target) return;

        const parentLi = target.closest('li');
        if (parentLi && parentLi.className.match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/) && (target.getAttribute('href') === '#' || target.classList.contains('menu-expand'))) {
          e.preventDefault();
          const subMenu = parentLi.querySelector('ul');

          if (subMenu && window.getComputedStyle(subMenu).display !== 'none') {
            parentLi.classList.remove('active');
            slideUp(subMenu, 300);
          } else {
            parentLi.classList.add('active');

            // close siblings
            const siblings = Array.from(parentLi.parentElement.children).filter(child => child !== parentLi);
            siblings.forEach(sibling => {
              sibling.classList.remove('active');
              const siblingSubMenu = sibling.querySelector('ul');
              if (siblingSubMenu && window.getComputedStyle(siblingSubMenu).display !== 'none') {
                slideUp(siblingSubMenu, 300);
              }
              // close deep active items
              sibling.querySelectorAll('li.active').forEach(deepLi => deepLi.classList.remove('active'));
            });

            if (subMenu) slideDown(subMenu, 300);
          }
        }
      });
    }

    /* ====== Scroll To Top ====== */
    // Create the button
    const scrollUpBtn = document.createElement('a');
    scrollUpBtn.id = 'scrollUp';
    scrollUpBtn.href = '#top';
    scrollUpBtn.innerHTML = '<i class="icofont-rounded-up"></i>';
    scrollUpBtn.style.display = 'none';
    scrollUpBtn.style.position = 'fixed';
    scrollUpBtn.style.bottom = '20px';
    scrollUpBtn.style.right = '20px';
    scrollUpBtn.style.zIndex = '2147483647';
    scrollUpBtn.style.backgroundColor = 'var(--primaryColor, #007bff)';
    scrollUpBtn.style.color = '#fff';
    scrollUpBtn.style.width = '45px';
    scrollUpBtn.style.height = '45px';
    scrollUpBtn.style.textAlign = 'center';
    scrollUpBtn.style.lineHeight = '45px';
    scrollUpBtn.style.borderRadius = '50%';
    scrollUpBtn.style.cursor = 'pointer';
    document.body.appendChild(scrollUpBtn);

    window.addEventListener('scroll', function () {
      if (window.scrollY > 300) {
        scrollUpBtn.style.display = 'block';
      } else {
        scrollUpBtn.style.display = 'none';
      }
    });

    scrollUpBtn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    /* ====== Sticky Header ====== */
    const headerSticky = document.querySelector(".header__sticky");
    if (headerSticky) {
      window.addEventListener('scroll', function () {
        if (window.scrollY < 245) {
          headerSticky.classList.remove("sticky");
        } else {
          headerSticky.classList.add("sticky");
        }
      });
    }

    /* ====== Search Popup Toggle ====== */
    function sidebarSearch() {
      const searchTriggers = document.querySelectorAll('.header__icon');
      const endTriggersearch = document.querySelectorAll('.header__search__close');
      const container = document.querySelector('.header__main__search__active');

      searchTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
          e.preventDefault();
          if (container) container.classList.add('inside');
        });
      });

      endTriggersearch.forEach(trigger => {
        trigger.addEventListener('click', function () {
          if (container) container.classList.remove('inside');
        });
      });
    }
    sidebarSearch();

    /* ====== CounterUp Stats (Vanilla JS IntersectionObserver) ====== */
    const counters = document.querySelectorAll('.counter');
    if (counters.length > 0) {
      const speed = 1000; // 1 second total animation time

      const animateCount = (counter) => {
        const target = +counter.innerText;
        const inc = target / (speed / 16); // ~60fps
        let count = 0;

        const updateCount = () => {
          count += inc;
          if (count < target) {
            counter.innerText = Math.ceil(count);
            requestAnimationFrame(updateCount);
          } else {
            counter.innerText = target;
          }
        };
        updateCount();
      };

      if (window.IntersectionObserver) {
        const observer = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              animateCount(entry.target);
              observer.unobserve(entry.target);
            }
          });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
          observer.observe(counter);
        });
      } else {
        counters.forEach(counter => animateCount(counter));
      }
    }

    /* ====== Bootstrap Tooltips ====== */
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }

    /* ====== AOS Scroll Animations ====== */
    if (typeof AOS !== 'undefined') {
      AOS.init({
        offset: 40,
        duration: 1000,
        once: true,
        easing: 'ease',
      });
    }

    /* ====== Swiper Sliders ====== */
    if (typeof Swiper !== 'undefined') {
      if (document.querySelector('.university__slider')) {
        var sliderEl = document.querySelector('.university__slider');
        var sliderSection = sliderEl.closest('.herobannerarea__university') || sliderEl.parentElement;
        var pauseBtn = sliderSection ? sliderSection.querySelector('.gallery-pause-btn') : null;
        var autoplayDelay = 5000;
        var isAutoplayPaused = false;

        var universitySwiper = new Swiper(".university__slider", {
          spaceBetween: 0,
          loop: true,
          speed: 1000,
          effect: "fade",
          touchStartPreventDefault: false,
          simulateTouch: true,
          grabCursor: true,
          allowTouchMove: true,
          fadeEffect: {
            crossFade: true,
          },
          autoplay: {
            delay: autoplayDelay,
            disableOnInteraction: false,
          },
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
          pagination: {
            el: ".gallery-progress-bullets",
            clickable: true,
            renderBullet: function (index, className) {
              return '<button type="button" class="' + className + ' gallery-progress-bullet" aria-label="Slide ' + (index + 1) + '"><span class="gallery-progress-fill"></span></button>';
            },
          },
        });

        function goToSlide(targetIdx) {
          if (!universitySwiper) return;
          if (universitySwiper.params.loop) {
            universitySwiper.slideToLoop(targetIdx);
          } else {
            universitySwiper.slideTo(targetIdx);
          }
          if (!isAutoplayPaused && universitySwiper.autoplay) {
            universitySwiper.autoplay.start();
          }
        }

        var bulletsWrap = sliderSection.querySelector('.gallery-progress-bullets');
        if (bulletsWrap) {
          bulletsWrap.addEventListener('click', function (e) {
            var bullet = e.target.closest('.gallery-progress-bullet');
            if (!bullet) return;
            e.preventDefault();
            e.stopPropagation();
            var bulletsList = Array.prototype.slice.call(bulletsWrap.querySelectorAll('.gallery-progress-bullet'));
            var targetIdx = bulletsList.indexOf(bullet);
            if (targetIdx !== -1) {
              goToSlide(targetIdx);
            }
          });
        }

        function updateProgressState() {
          if (!universitySwiper || !sliderSection) return;
          var bullets = sliderSection.querySelectorAll('.gallery-progress-bullet');
          if (!bullets || !bullets.length) return;
          var activeIndex = universitySwiper.realIndex;

          bullets.forEach(function (bullet, idx) {
            if (!bullet.dataset.boundClick) {
              bullet.dataset.boundClick = "true";
              bullet.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                goToSlide(idx);
              });
            }

            var fill = bullet.querySelector('.gallery-progress-fill');
            if (!fill) return;

            // Reset animation
            fill.style.animation = 'none';
            void fill.offsetWidth; // Trigger DOM reflow to reset CSS keyframe

            if (idx < activeIndex) {
              bullet.classList.add('is-completed');
              bullet.classList.remove('is-active', 'is-upcoming');
              fill.style.width = '100%';
            } else if (idx === activeIndex) {
              bullet.classList.add('is-active');
              bullet.classList.remove('is-completed', 'is-upcoming');
              fill.style.width = '0%';
              if (!isAutoplayPaused) {
                fill.style.animation = 'galleryProgressBar ' + autoplayDelay + 'ms linear forwards';
              }
            } else {
              bullet.classList.add('is-upcoming');
              bullet.classList.remove('is-completed', 'is-active');
              fill.style.width = '0%';
            }
          });
        }

        universitySwiper.on('init', updateProgressState);
        universitySwiper.on('slideChange', updateProgressState);
        updateProgressState();

        if (pauseBtn) {
          pauseBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var activeFill = sliderSection.querySelector('.gallery-progress-bullet.is-active .gallery-progress-fill');

            if (isAutoplayPaused) {
              isAutoplayPaused = false;
              sliderSection.classList.remove('is-paused');
              pauseBtn.classList.remove('is-paused');
              pauseBtn.setAttribute('aria-label', 'Pause autoplay');
              universitySwiper.autoplay.start();
              if (activeFill) {
                activeFill.style.animationPlayState = 'running';
              } else {
                updateProgressState();
              }
            } else {
              isAutoplayPaused = true;
              sliderSection.classList.add('is-paused');
              pauseBtn.classList.add('is-paused');
              pauseBtn.setAttribute('aria-label', 'Play autoplay');
              universitySwiper.autoplay.stop();
              if (activeFill) {
                activeFill.style.animationPlayState = 'paused';
              }
            }
          });
        }
      }

      if (document.querySelector('.ecommerce__slider')) {
        const slideCount = document.querySelectorAll('.ecommerce__slider .swiper-slide').length;
        new Swiper(".ecommerce__slider", {
          loop: slideCount > 1,
          rewind: false,
          speed: 600,
          effect: "fade",
          fadeEffect: {
            crossFade: true,
          },
          autoplay: {
            delay: 4500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          },
          navigation: {
            nextEl: ".college-btn-next",
            prevEl: ".college-btn-prev",
          },
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
          observer: true,
          observeParents: true,
        });
      }
    }

  });

})();