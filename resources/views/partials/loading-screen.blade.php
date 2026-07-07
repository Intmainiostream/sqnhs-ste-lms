<div id="loadingScreen" class="loading-screen">
    <div id="moleculeField" class="molecule-field"></div>

    <div class="loading-core">
        <p class="loading-text">SQNHS STE LMS</p>
    </div>
</div>

<style>
    .loading-screen {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: #071a10;
        overflow: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .loading-screen.fade-out {
        opacity: 0;
        visibility: hidden;
    }

    .loading-screen.skip {
        transition: none;
        opacity: 0;
        visibility: hidden;
    }

    .molecule-field {
        position: absolute;
        inset: 0;
    }

    .mol {
        position: absolute;
        opacity: 0;
    }

    .mol svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .loading-core {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
    }

    .loading-text {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.2em;
        color: #bbf7d0;
        text-shadow: 0 0 12px rgba(74, 222, 128, 0.8);
    }
</style>

<script>
    (function () {
        const loader = document.getElementById('loadingScreen');

        // Detect actual browser refresh/reload vs normal link navigation
        const navEntries = performance.getEntriesByType('navigation');
        const navType = navEntries.length ? navEntries[0].type : 'navigate';

        const isReload = navType === 'reload';
        const isFirstVisit = !sessionStorage.getItem('sqnhs_loaded_once');

        if (!isReload && !isFirstVisit) {
            // Normal link click navigation — skip animation entirely
            loader.classList.add('skip');
            return;
        }

        sessionStorage.setItem('sqnhs_loaded_once', '1');

        const field = document.getElementById('moleculeField');

        function atomSVG(color) {
            return `<svg viewBox="0 0 100 100">
                <ellipse cx="50" cy="50" rx="42" ry="16" fill="none" stroke="${color}" stroke-width="2.5"/>
                <ellipse cx="50" cy="50" rx="42" ry="16" fill="none" stroke="${color}" stroke-width="2.5" transform="rotate(60 50 50)"/>
                <ellipse cx="50" cy="50" rx="42" ry="16" fill="none" stroke="${color}" stroke-width="2.5" transform="rotate(120 50 50)"/>
                <circle cx="50" cy="50" r="7" fill="${color}"/>
            </svg>`;
        }

        function moleculeSVG(color) {
            return `<svg viewBox="0 0 120 60">
                <line x1="15" y1="30" x2="60" y2="10" stroke="${color}" stroke-width="2.5"/>
                <line x1="60" y1="10" x2="105" y2="30" stroke="${color}" stroke-width="2.5"/>
                <line x1="60" y1="10" x2="60" y2="50" stroke="${color}" stroke-width="2.5"/>
                <line x1="60" y1="50" x2="20" y2="55" stroke="${color}" stroke-width="2.5"/>
                <circle cx="15" cy="30" r="7" fill="${color}"/>
                <circle cx="60" cy="10" r="8" fill="${color}"/>
                <circle cx="105" cy="30" r="7" fill="${color}"/>
                <circle cx="60" cy="50" r="7" fill="${color}"/>
                <circle cx="20" cy="55" r="6" fill="${color}"/>
            </svg>`;
        }

        const colors = ['#4ade80', '#86efac', '#22c55e', '#bbf7d0'];
        const count = 30;

        function rand(min, max) {
            return Math.random() * (max - min) + min;
        }

        for (let i = 0; i < count; i++) {
            const el = document.createElement('div');
            el.className = 'mol';

            const isAtom = Math.random() > 0.45;
            const color = colors[Math.floor(Math.random() * colors.length)];
            el.innerHTML = isAtom ? atomSVG(color) : moleculeSVG(color);

            const size = isAtom ? rand(30, 80) : rand(50, 120);
            el.style.width = size + 'px';
            el.style.height = (isAtom ? size : size * 0.55) + 'px';

            el.style.left = rand(0, 100) + 'vw';
            el.style.top = rand(0, 100) + 'vh';

            field.appendChild(el);

            const dx = rand(-160, 160);
            const dy = rand(-160, 160);
            const duration = rand(700, 1000);
            const delay = rand(0, 200);

            el.animate([
                { transform: 'translate(0px, 0px) scale(0.2) rotate(0deg)', opacity: 0 },
                { transform: `translate(${dx * 0.3}px, ${dy * 0.3}px) scale(1) rotate(90deg)`, opacity: 1, offset: 0.2 },
                { transform: `translate(${dx * 0.6}px, ${dy * 0.6}px) scale(1.1) rotate(180deg)`, opacity: 0.95, offset: 0.55 },
                { transform: `translate(${dx}px, ${dy}px) scale(0.7) rotate(360deg)`, opacity: 0 },
            ], {
                duration: duration,
                delay: delay,
                easing: 'ease-in-out',
                fill: 'forwards',
            });

            const svgEl = el.querySelector('svg');
            svgEl.animate([
                { filter: 'drop-shadow(0 0 4px rgba(74,222,128,0.6)) drop-shadow(0 0 8px rgba(34,197,94,0.35))' },
                { filter: 'drop-shadow(0 0 16px rgba(74,222,128,1)) drop-shadow(0 0 34px rgba(34,197,94,0.8))' },
                { filter: 'drop-shadow(0 0 4px rgba(74,222,128,0.6)) drop-shadow(0 0 8px rgba(34,197,94,0.35))' },
            ], {
                duration: rand(500, 800),
                delay: delay,
                iterations: Infinity,
            });
        }

        setTimeout(function () {
            loader.classList.add('fade-out');
        }, 900);
    })();
</script>