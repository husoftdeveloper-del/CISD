// Mobile nav
const burger = document.getElementById('hamburger');
const links = document.getElementById('navLinks');
if (burger && links) {
  burger.addEventListener('click', () => links.classList.toggle('open'));
  links.querySelectorAll('a').forEach(a => a.addEventListener('click', () => links.classList.remove('open')));
}

// Scroll reveal
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));

// Gallery lightbox
const lb = document.getElementById('lightbox');
const lbImg = document.getElementById('lightbox-img');
document.querySelectorAll('.g-item').forEach(item => {
  item.addEventListener('click', () => {
    if (!lb || !lbImg) return;
    lbImg.src = item.querySelector('img').src;
    lb.classList.add('open');
  });
});
if (lb) {
  lb.addEventListener('click', (e) => {
    if (e.target === lb || e.target.classList.contains('lightbox-close')) lb.classList.remove('open');
  });
}

// Stats counting animation
const statsObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const counter = entry.target.querySelector('.counter');
      const target = parseInt(entry.target.getAttribute('data-target'));
      if (counter && target) {
        animateCounter(counter, target);
      }
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat .v').forEach(stat => statsObserver.observe(stat));

function animateCounter(element, target) {
  let current = 1;
  const duration = 2000;
  const increment = target / (duration / 20);
  
  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      element.textContent = target;
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(current);
    }
  }, 20);
}

// Toggle testimonial details
function toggleDetails(button) {
  const details = button.nextElementSibling;
  if (details.classList.contains('show')) {
    details.classList.remove('show');
    button.textContent = 'View More';
  } else {
    details.classList.add('show');
    button.textContent = 'View Less';
  }
}
