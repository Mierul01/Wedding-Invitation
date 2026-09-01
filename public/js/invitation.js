document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('invite-app');
    const entry = document.getElementById('invite-entry');
    const loading = document.getElementById('invite-entry-loading');
    const gate = document.getElementById('invite-entry-gate');
    const openBtn = document.getElementById('invite-entry-open');

    const initEntry = () => {
        if (!app || !entry) {
            app?.classList.add('invite-app--opened');
            return (callback) => callback();
        }

        const afterOpenCallbacks = [];
        const runAfterOpen = (callback) => {
            if (app.classList.contains('invite-app--opened')) {
                callback();
                return;
            }
            afterOpenCallbacks.push(callback);
        };

        const openInvitation = () => {
            entry.classList.add('invite-entry--leaving');
            app.classList.add('invite-app--opened');
            window.setTimeout(() => entry.remove(), 650);
            afterOpenCallbacks.forEach((callback) => callback());
        };

        openBtn?.addEventListener('click', openInvitation);

        window.setTimeout(() => {
            entry.classList.add('invite-entry--gate');
            loading?.setAttribute('aria-hidden', 'true');
            gate?.removeAttribute('hidden');
        }, 1600);

        return runAfterOpen;
    };

    const runAfterOpen = initEntry();

    const scrollRoot = document.querySelector('.pages');
    const sections = document.querySelectorAll('[data-section]');
    const scrollTriggers = document.querySelectorAll('[data-goto]');
    const modalOpeners = document.querySelectorAll('[data-open-modal]');
    const modalClosers = document.querySelectorAll('[data-close-modal]');
    const modalNodes = document.querySelectorAll('[data-modal]');

    const revealSelector = [
        '.hero-brand',
        '.cover-kicker',
        '.kad-couple',
        '.kad-hero-date',
        '.kad-card > *',
        '.atur-cara-box > *',
        '.countdown-intro > *',
        '.countdown-heading-wrap',
        '.countdown-item',
    ].join(', ');

    const markRevealElements = () => {
        document.querySelectorAll('[data-section]').forEach((section) => {
            const targets = section.querySelectorAll(revealSelector);
            targets.forEach((el, index) => {
                el.classList.add('reveal');
                el.style.setProperty('--reveal-delay', `${Math.min(index * 0.07, 0.45)}s`);
            });
        });
    };

    let revealObserver;
    const sectionRevealTimers = new WeakMap();

    const clearSectionRevealTimers = (section) => {
        const timers = sectionRevealTimers.get(section);
        if (!timers) return;
        timers.forEach((id) => window.clearTimeout(id));
        sectionRevealTimers.delete(section);
    };

    const hideSectionReveal = (section) => {
        clearSectionRevealTimers(section);
        section.querySelectorAll('.reveal').forEach((el) => el.classList.remove('is-visible'));
    };

    const playSectionReveal = (section) => {
        clearSectionRevealTimers(section);
        const targets = [...section.querySelectorAll('.reveal')];
        const timers = targets.map((el, index) =>
            window.setTimeout(() => el.classList.add('is-visible'), index * 90)
        );
        sectionRevealTimers.set(section, timers);
    };

    const initScrollReveal = () => {
        markRevealElements();

        if (!scrollRoot || !('IntersectionObserver' in window)) {
            document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
            return;
        }

        revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        playSectionReveal(entry.target);
                    } else {
                        hideSectionReveal(entry.target);
                    }
                });
            },
            {
                root: scrollRoot,
                threshold: [0, 0.2, 0.35],
                rootMargin: '0px 0px -5% 0px',
            }
        );

        runAfterOpen(() => {
            document.querySelectorAll('[data-section]').forEach((section) => {
                revealObserver.observe(section);
            });

            requestAnimationFrame(() => {
                document.querySelectorAll('[data-section]').forEach((section) => {
                    const rect = section.getBoundingClientRect();
                    const rootRect = scrollRoot.getBoundingClientRect();
                    const visible = rect.top < rootRect.bottom * 0.75 && rect.bottom > rootRect.top + 40;
                    if (visible) playSectionReveal(section);
                });
            });
        });
    };

    initScrollReveal();

    const scrollSectionIds = ['cover', 'walimatulurus', 'aturcara', 'countdown'];
    const modalIds = ['rsvp', 'masa', 'telefon', 'lokasi', 'hadiah'];

    const modals = Object.fromEntries(
        [...modalNodes].map((node) => [node.dataset.modal, node])
    );

    const getOpenModalId = () =>
        modalIds.find((id) => modals[id]?.classList.contains('is-open')) || null;

    const lockScroll = (lock) => {
        if (scrollRoot) scrollRoot.style.overflow = lock ? 'hidden' : '';
    };

    const updateFab = (activeId) => {
        const fab = document.querySelector('.fab-gift');
        if (fab) fab.style.display = activeId === 'hadiah' ? 'none' : '';
    };

    const setActiveTab = (activeId) => {
        document.querySelectorAll('.tab-item').forEach((el) => {
            const modalId = el.dataset.openModal;
            if (modalId) {
                el.classList.toggle('is-active', activeId === modalId);
            }
        });
    };

    const closeAllModals = ({ unlockScroll = true, clearUrl = true } = {}) => {
        modalIds.forEach((id) => {
            const modal = modals[id];
            if (!modal) return;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        });

        if (unlockScroll) lockScroll(false);
        if (clearUrl) history.replaceState(null, '', window.location.pathname);
    };

    const openModal = (modalId) => {
        const modal = modals[modalId];
        if (!modal) return;

        closeAllModals({ unlockScroll: false, clearUrl: modalId !== 'rsvp' });

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        lockScroll(true);
        setActiveTab(modalId);
        updateFab(modalId);

        if (modalId === 'rsvp') {
            history.replaceState(null, '', `${window.location.pathname}?rsvp=1`);
        }
    };

    const closeModal = (modalId) => {
        const modal = modals[modalId];
        if (!modal) return;

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');

        if (!getOpenModalId()) {
            lockScroll(false);
            history.replaceState(null, '', window.location.pathname);

            const visibleSection = scrollSectionIds.find((id) => {
                const node = document.getElementById(`section-${id}`);
                if (!node || !scrollRoot) return false;
                const rect = node.getBoundingClientRect();
                const rootRect = scrollRoot.getBoundingClientRect();
                return rect.top < rootRect.bottom * 0.55 && rect.bottom > rootRect.top + 80;
            }) || 'cover';

            setActiveTab(visibleSection);
            updateFab(visibleSection);
        }
    };

    const scrollToSection = (sectionId, behavior = 'smooth') => {
        const target = document.getElementById(`section-${sectionId}`);
        if (!target || !scrollRoot) return;

        closeAllModals();

        const top = target.offsetTop;

        scrollRoot.scrollTo({
            top: Math.max(0, top),
            behavior,
        });

        setActiveTab(sectionId);
        updateFab(sectionId);

        const path = window.location.pathname;
        history.replaceState(null, '', sectionId === 'cover' ? path : `${path}#${sectionId}`);
    };

    scrollTriggers.forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            scrollToSection(el.dataset.goto);
        });
    });

    modalOpeners.forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(el.dataset.openModal);
        });
    });

    modalClosers.forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal(el.dataset.closeModal);
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        const openId = getOpenModalId();
        if (openId) closeModal(openId);
    });

    if (scrollRoot && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                if (getOpenModalId()) return;

                const visible = entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

                if (visible.length) {
                    const section = visible[0].target.dataset.section;
                    setActiveTab(section);
                    updateFab(section);
                }
            },
            {
                root: scrollRoot,
                rootMargin: '0px',
                threshold: [0.45, 0.55, 0.65],
            }
        );

        sections.forEach((section) => observer.observe(section));
    }

    const params = new URLSearchParams(window.location.search);
    const hashSection = window.location.hash.replace('#', '');
    const shouldOpenRsvp = params.get('rsvp') || hashSection === 'rsvp' || modals.rsvp?.classList.contains('is-open');

    if (shouldOpenRsvp) {
        runAfterOpen(() => openModal('rsvp'));
    } else if (scrollSectionIds.includes(hashSection)) {
        runAfterOpen(() => requestAnimationFrame(() => scrollToSection(hashSection, 'auto')));
    } else {
        setActiveTab(null);
        updateFab('cover');
    }

    const countdown = document.getElementById('countdown-timer');
    if (countdown) {
        const target = new Date(countdown.dataset.target).getTime();

        const tick = () => {
            const now = Date.now();
            let diff = Math.max(0, target - now);

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            diff -= days * 1000 * 60 * 60 * 24;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * 1000 * 60 * 60;
            const minutes = Math.floor(diff / (1000 * 60));
            diff -= minutes * 1000 * 60;
            const seconds = Math.floor(diff / 1000);

            const set = (id, value) => {
                const node = document.getElementById(id);
                if (node) node.textContent = String(value).padStart(2, '0');
            };

            set('cd-days', days);
            set('cd-hours', hours);
            set('cd-minutes', minutes);
            set('cd-seconds', seconds);
        };

        tick();
        setInterval(tick, 1000);
    }

    const attendingSelect = document.getElementById('attending');
    const guestCountGroup = document.getElementById('guest-count-group');
    const guestCountInput = document.getElementById('guest_count');

    const syncGuestCount = () => {
        if (!guestCountGroup || !guestCountInput || !attendingSelect) return;
        const attending = attendingSelect.value === '1';
        guestCountGroup.style.display = attending ? '' : 'none';
        if (!attending) {
            guestCountInput.value = '0';
            guestCountInput.removeAttribute('required');
        } else {
            if (Number(guestCountInput.value) < 1) guestCountInput.value = '1';
            guestCountInput.setAttribute('required', 'required');
        }
    };

    if (attendingSelect) {
        attendingSelect.addEventListener('change', syncGuestCount);
        syncGuestCount();
    }

    const music = document.getElementById('invite-music');
    const musicToggle = document.getElementById('music-toggle');

    if (music && musicToggle) {
        const setMusicState = (playing) => {
            musicToggle.classList.toggle('is-playing', playing);
            musicToggle.setAttribute('aria-pressed', playing ? 'true' : 'false');
            musicToggle.setAttribute('aria-label', playing ? 'Hentikan muzik' : 'Mainkan muzik');
        };

        const playMusic = async () => {
            try {
                await music.play();
                setMusicState(true);
            } catch {
                setMusicState(false);
            }
        };

        const pauseMusic = () => {
            music.pause();
            setMusicState(false);
        };

        musicToggle.addEventListener('click', async (e) => {
            e.preventDefault();
            if (music.paused) {
                await playMusic();
            } else {
                pauseMusic();
            }
        });

        music.addEventListener('play', () => setMusicState(true));
        music.addEventListener('pause', () => setMusicState(false));

        setMusicState(false);
    }
});
