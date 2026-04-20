import './bootstrap';

const zoomParallaxElements = document.querySelectorAll('[data-zoom-parallax]');

if (zoomParallaxElements.length > 0) {
    const updateZoomParallax = () => {
        zoomParallaxElements.forEach((element) => {
            const rect = element.getBoundingClientRect();
            const offset = Math.max(-12, Math.min(12, rect.top * -0.03));

            element.style.setProperty('--zoom-parallax-offset', `${offset}px`);
        });
    };

    updateZoomParallax();
    window.addEventListener('scroll', updateZoomParallax, { passive: true });
    window.addEventListener('resize', updateZoomParallax);
}
