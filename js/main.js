// Manejo del menú móvil
document.addEventListener('DOMContentLoaded', () => {
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
      // Aquí puedes implementar cambio de idioma real
    });
  }

  // Manejar sombra en navbar al hacer scroll
  const navbar = document.querySelector('nav');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 10) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Código del carrusel:
  const slides = document.querySelectorAll('#carousel-images img'); // Cambié selector
  let currentIndex = 0;
  const total = slides.length;
  const intervalTime = 5000; // 5 segundos
  let slideInterval;

  function showSlide(index) {
    slides.forEach((img, i) => {
      img.style.opacity = i === index ? '1' : '0'; // Usamos opacidad
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

  // Inicializar
  showSlide(currentIndex);
  slideInterval = setInterval(nextSlide, intervalTime);

  // Botones
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');

  nextBtn.addEventListener('click', () => {
    nextSlide();
    resetInterval();
  });

  prevBtn.addEventListener('click', () => {
    prevSlide();
    resetInterval();
  });

  function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, intervalTime);
  }
});
