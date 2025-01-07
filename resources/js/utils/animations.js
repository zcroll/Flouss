import anime from 'animejs/lib/anime.es.js';

const ANIMATION_CONFIG = {
    duration: 300,
    easing: 'cubicBezier(.4, 0, 0.2, 1)',
    transitions: {
        next: {
            exit: { translateY: [0, '-50%'], scale: [1, 0.98], opacity: [1, 0] },
            enter: { translateY: ['50%', 0], scale: [0.98, 1], opacity: [0, 1] }
        },
        back: {
            exit: { translateY: [0, '50%'], scale: [1, 0.98], opacity: [1, 0] },
            enter: { translateY: ['-50%', 0], scale: [0.98, 1], opacity: [0, 1] }
        }
    },
    reset: {
        translateY: 0,
        scale: 1,
        opacity: 1,
        duration: 100,
        easing: 'easeOutQuad'
    }
};

export const animateTransition = async (element, direction, { onComplete } = {}) => {
    if (!element) return;

    // Remove any existing animations
    anime.remove(element);

    const { exit, enter } = ANIMATION_CONFIG.transitions[direction];
    const timing = {
        duration: ANIMATION_CONFIG.duration,
        easing: ANIMATION_CONFIG.easing
    };

    try {
        // Exit animation
        await new Promise(resolve => {
            anime({
                targets: element,
                ...exit,
                ...timing,
                complete: resolve
            });
        });

        // Execute callback if provided
        if (onComplete) {
            await onComplete();
        }

        // Enter animation
        await new Promise(resolve => {
            requestAnimationFrame(() => {
                anime({
                    targets: element,
                    ...enter,
                    ...timing,
                    complete: resolve
                });
            });
        });
    } catch (error) {
        // Reset animation on error
        anime({
            targets: element,
            ...ANIMATION_CONFIG.reset
        });
        throw error;
    }
};

export const resetAnimation = (element) => {
    if (!element) return;
    
    anime.remove(element);
    anime({
        targets: element,
        ...ANIMATION_CONFIG.reset
    });
};

export const useAnimations = () => {
    const animateContent = async (element, direction, callback) => {
        if (!element) {
            if (callback) await callback();
            return;
        }

        try {
            await animateTransition(element, direction, {
                onComplete: callback
            });
        } catch (error) {
            resetAnimation(element);
            throw error;
        }
    };

    return {
        animateContent
    };
}; 