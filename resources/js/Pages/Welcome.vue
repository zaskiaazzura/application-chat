<template>
    <div class="welcome-wrapper">
        <!-- Background bubbles -->
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>

        <div class="welcome-card">
            <!-- Logo / Brand -->
            <div class="brand">
                <div class="brand-icon">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <circle cx="16" cy="16" r="16" fill="url(#grad)"/>
                        <path d="M9 12h14M9 16h10M9 20h12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="grad" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#6366f1"/>
                                <stop offset="1" stop-color="#8b5cf6"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <span class="brand-name">ChatApp</span>
            </div>

            <h1 class="headline">Selamat Datang 👋</h1>
            <p class="subheadline">Terhubung dengan siapa saja, kapan saja, secara real-time.</p>

            <!-- Action Buttons -->
            <div class="actions">
                <Link :href="route('login')" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Masuk
                </Link>
                <Link :href="route('register')" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                        <line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                    Daftar Akun Baru
                </Link>
            </div>

            <!-- Features -->
            <div class="features">
                <div class="feature">
                    <span class="feature-icon">⚡</span>
                    <span>Real-time</span>
                </div>
                <div class="feature">
                    <span class="feature-icon">🔒</span>
                    <span>Aman</span>
                </div>
                <div class="feature">
                    <span class="feature-icon">💬</span>
                    <span>Mudah</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.welcome-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0f0f1a;
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
}

/* Animated background bubbles */
.bubble {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.25;
    animation: float 8s ease-in-out infinite;
}
.bubble-1 {
    width: 400px; height: 400px;
    background: #6366f1;
    top: -100px; left: -100px;
    animation-delay: 0s;
}
.bubble-2 {
    width: 300px; height: 300px;
    background: #8b5cf6;
    bottom: -80px; right: -80px;
    animation-delay: 2s;
}
.bubble-3 {
    width: 250px; height: 250px;
    background: #06b6d4;
    top: 50%; left: 60%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-30px) scale(1.05); }
}

/* Card */
.welcome-card {
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    width: 100%;
    max-width: 420px;
    text-align: center;
    position: relative;
    z-index: 1;
    animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Brand */
.brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    margin-bottom: 1.8rem;
}
.brand-icon {
    display: flex;
    align-items: center;
}
.brand-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
}

/* Text */
.headline {
    font-size: 1.9rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.03em;
    margin-bottom: 0.6rem;
    line-height: 1.2;
}
.subheadline {
    color: rgba(255,255,255,0.5);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

/* Buttons */
.actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.85rem 1.5rem;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(99, 102, 241, 0.55);
}
.btn-primary:active { transform: translateY(0); }

.btn-secondary {
    background: rgba(255,255,255,0.07);
    color: rgba(255,255,255,0.85);
    border: 1px solid rgba(255,255,255,0.1);
}
.btn-secondary:hover {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}
.btn-secondary:active { transform: translateY(0); }

/* Features */
.features {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.feature {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.35);
    font-weight: 500;
}
.feature-icon {
    font-size: 1.1rem;
}
</style>