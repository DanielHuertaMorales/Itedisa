// Manejo general del sitio web
document.addEventListener('DOMContentLoaded', () => {
  // Menú móvil
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Cambio de idioma
  const langSelect = document.getElementById('langSelect');
  if (langSelect) {
    langSelect.addEventListener('change', (e) => {
      const lang = e.target.value;
      alert(`Idioma seleccionado: ${lang === 'es' ? 'Español' : 'English'}`);
      // Implementa aquí el cambio real de idioma si lo deseas
    });
  }

  // Sombra al hacer scroll
  const navbar = document.querySelector('nav');
  if (navbar) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // Carrusel de imágenes
  const slides = document.querySelectorAll('#carousel-images img');
  if (slides.length > 0) {
    let currentIndex = 0;
    const total = slides.length;
    const intervalTime = 5000;
    let slideInterval;

    function showSlide(index) {
      slides.forEach((img, i) => {
        img.style.opacity = i === index ? '1' : '0';
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % total;
      showSlide(currentIndex);
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + total) % total;
      showSlide(currentIndex);
    }

    function resetInterval() {
      clearInterval(slideInterval);
      slideInterval = setInterval(nextSlide, intervalTime);
    }

    // Inicializar carrusel
    showSlide(currentIndex);
    slideInterval = setInterval(nextSlide, intervalTime);

    // Botones del carrusel
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        resetInterval();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        resetInterval();
      });
    }
  }
});
