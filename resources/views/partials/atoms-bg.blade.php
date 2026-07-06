<div class="atoms-bg" aria-hidden="true">
    <svg class="atom atom-1" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="4" fill="#15803d"/>
        <ellipse cx="50" cy="50" rx="40" ry="16" fill="none" stroke="#22c55e" stroke-width="1.5"/>
        <ellipse cx="50" cy="50" rx="40" ry="16" fill="none" stroke="#22c55e" stroke-width="1.5" transform="rotate(60 50 50)"/>
        <ellipse cx="50" cy="50" rx="40" ry="16" fill="none" stroke="#22c55e" stroke-width="1.5" transform="rotate(120 50 50)"/>
    </svg>

    <svg class="atom atom-2" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="3" fill="#15803d"/>
        <ellipse cx="50" cy="50" rx="35" ry="14" fill="none" stroke="#4ade80" stroke-width="1.2"/>
        <ellipse cx="50" cy="50" rx="35" ry="14" fill="none" stroke="#4ade80" stroke-width="1.2" transform="rotate(60 50 50)"/>
        <ellipse cx="50" cy="50" rx="35" ry="14" fill="none" stroke="#4ade80" stroke-width="1.2" transform="rotate(120 50 50)"/>
    </svg>

    <svg class="atom atom-3" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="3.5" fill="#15803d"/>
        <ellipse cx="50" cy="50" rx="38" ry="15" fill="none" stroke="#22c55e" stroke-width="1.3"/>
        <ellipse cx="50" cy="50" rx="38" ry="15" fill="none" stroke="#22c55e" stroke-width="1.3" transform="rotate(60 50 50)"/>
        <ellipse cx="50" cy="50" rx="38" ry="15" fill="none" stroke="#22c55e" stroke-width="1.3" transform="rotate(120 50 50)"/>
    </svg>

    <svg class="molecule molecule-1" viewBox="0 0 120 60">
        <line x1="20" y1="30" x2="60" y2="15" stroke="#86efac" stroke-width="1.5"/>
        <line x1="60" y1="15" x2="100" y2="30" stroke="#86efac" stroke-width="1.5"/>
        <line x1="60" y1="15" x2="60" y2="45" stroke="#86efac" stroke-width="1.5"/>
        <circle cx="20" cy="30" r="4" fill="#16a34a"/>
        <circle cx="60" cy="15" r="5" fill="#15803d"/>
        <circle cx="100" cy="30" r="4" fill="#16a34a"/>
        <circle cx="60" cy="45" r="4" fill="#16a34a"/>
    </svg>
</div>

<style>
    .atoms-bg {
        position: fixed;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .atoms-bg .atom,
    .atoms-bg .molecule {
        position: absolute;
        opacity: 0.35;
        filter: drop-shadow(0 0 6px rgba(34, 197, 94, 0.55));
    }

    .atom-1 {
        width: 140px;
        top: 8%;
        left: 6%;
        animation: spin-float-1 18s linear infinite, glow-pulse 4s ease-in-out infinite;
    }

    .atom-2 {
        width: 100px;
        bottom: 12%;
        right: 8%;
        animation: spin-float-2 22s linear infinite reverse, glow-pulse 5s ease-in-out infinite 1s;
    }

    .atom-3 {
        width: 90px;
        top: 55%;
        left: 82%;
        animation: spin-float-1 26s linear infinite, glow-pulse 6s ease-in-out infinite 2s;
    }

    .molecule-1 {
        width: 160px;
        top: 70%;
        left: 4%;
        animation: float-drift 14s ease-in-out infinite, glow-pulse 5s ease-in-out infinite 0.5s;
    }

    @keyframes spin-float-1 {
        from { transform: rotate(0deg) translateY(0); }
        50%  { transform: rotate(180deg) translateY(-14px); }
        to   { transform: rotate(360deg) translateY(0); }
    }

    @keyframes spin-float-2 {
        from { transform: rotate(0deg) translateY(0); }
        50%  { transform: rotate(180deg) translateY(12px); }
        to   { transform: rotate(360deg) translateY(0); }
    }

    @keyframes float-drift {
        0%, 100% { transform: translate(0, 0); }
        50%      { transform: translate(10px, -10px); }
    }

    @keyframes glow-pulse {
        0%, 100% { opacity: 0.25; }
        50%      { opacity: 0.5; }
    }

    @media (prefers-reduced-motion: reduce) {
        .atoms-bg .atom,
        .atoms-bg .molecule {
            animation: none;
        }
    }
</style>