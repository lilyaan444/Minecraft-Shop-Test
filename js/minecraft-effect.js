document.addEventListener('DOMContentLoaded', function() {

  
    // Add hover sound effect to buttons
    const buttons = document.querySelectorAll('.btn-minecraft');
    const hoverSound = new Audio('/sounds/minecraft_click.mp3');
  
    buttons.forEach(button => {
      button.addEventListener('mouseenter', () => {
        hoverSound.currentTime = 0;
        hoverSound.play();
      });
    });
  
    // Add breaking animation to product cards
    const productCards = document.querySelectorAll('.product-card');
    const breakSound = new Audio('/sounds/minecraft_break.mp3');
  
    productCards.forEach(card => {
      card.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON') {
          e.preventDefault();
          breakSound.currentTime = 0;
          breakSound.play();
          card.style.transition = 'none';
          card.style.opacity = '0';
          
          setTimeout(() => {
            card.style.transition = 'opacity 0.5s';
            card.style.opacity = '1';
          }, 50);
        }
      });
    });
  
    // Add particle effect on click
    document.addEventListener('click', (e) => {
      createParticles(e.clientX, e.clientY);
    });
  
    function createParticles(x, y) {
      const particles = document.createElement('div');
      particles.className = 'particles';
      for (let i = 0; i < 5; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = x + 'px';
        particle.style.top = y + 'px';
        particle.style.setProperty('--x', (Math.random() - 0.5) * 40 + 'px');
        particle.style.setProperty('--y', (Math.random() - 0.5) * 40 + 'px');
        particles.appendChild(particle);
      }
      document.body.appendChild(particles);
      setTimeout(() => {
        document.body.removeChild(particles);
      }, 1000);
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect to Minecraft-style buttons
    const buttons = document.querySelectorAll('.btn-minecraft');
    buttons.forEach(button => {
        button.addEventListener('mouseover', () => {
            button.style.transform = 'scale(1.05)';
        });
        button.addEventListener('mouseout', () => {
            button.style.transform = 'scale(1)';
        });
    });

    // Add parallax effect to background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        document.body.style.backgroundPositionY = -(scrolled * 0.1) + 'px';
    });
});