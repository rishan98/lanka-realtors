<script>
(function () {
    var roots = document.querySelectorAll('[data-hero-carousel]');
    if (!roots.length) return;

    Array.prototype.forEach.call(roots, function (root) {
        var slides = Array.prototype.slice.call(root.querySelectorAll('[data-hero-carousel-slide]'));
        var dots = Array.prototype.slice.call(root.querySelectorAll('[data-hero-carousel-dot]'));
        if (slides.length < 2) return;

        var index = 0;
        var timer = null;
        var delay = 5000;
        var paused = false;

        function show(next) {
            index = (next + slides.length) % slides.length;
            slides.forEach(function (slide, i) {
                var active = i === index;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
            });
            dots.forEach(function (dot, i) {
                var active = i === index;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-selected', active ? 'true' : 'false');
            });
        }

        function next() {
            show(index + 1);
        }

        function start() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            stop();
            timer = window.setInterval(function () {
                if (!paused) next();
            }, delay);
        }

        function stop() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () {
                show(i);
                start();
            });
        });

        root.addEventListener('mouseenter', function () { paused = true; });
        root.addEventListener('mouseleave', function () { paused = false; });
        root.addEventListener('focusin', function () { paused = true; });
        root.addEventListener('focusout', function () { paused = false; });

        start();
    });
})();
</script>
