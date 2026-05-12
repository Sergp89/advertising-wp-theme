// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Scroll Sync Utility
 * Smooth scroll interpolation with RAF optimization
 */

import { aurawpThreeConfig } from '../three-city-config.js';

export class ScrollSync {
	constructor(onScrollCallback) {
		this.onScrollCallback = onScrollCallback;
		this.currentScroll = 0;
		this.targetScroll = 0;
		this.scrollDelta = 0;
		this.lastScrollTime = 0;
		this.rafId = null;
		this.isThrottled = false;

		this.bindEvents();
	}

	bindEvents() {
		window.addEventListener('scroll', this.handleScroll.bind(this), { passive: true });
		window.addEventListener('resize', this.handleResize.bind(this), { passive: true });
		window.addEventListener('wheel', this.handleWheel.bind(this), { passive: true });
	}

	handleScroll(event) {
		const now = performance.now();
		
		// Throttle scroll events for performance
		if (now - this.lastScrollTime < 16) { // ~60fps
			return;
		}

		this.lastScrollTime = now;
		this.targetScroll = window.scrollY || window.pageYOffset;
		this.scrollDelta = this.targetScroll - this.currentScroll;

		if (!this.rafId) {
			this.rafId = requestAnimationFrame(this.update.bind(this));
		}
	}

	handleWheel(event) {
		if (aurawpThreeConfig.general.reducedMotion) return;

		this.targetScroll += event.deltaY * 0.5;
		this.targetScroll = Math.max(0, this.targetScroll);
	}

	handleResize() {
		// Recalculate scroll position on resize
		this.targetScroll = window.scrollY || window.pageYOffset;
		this.currentScroll = this.targetScroll;
	}

	update() {
		// Smooth interpolation (lerp)
		const lerpFactor = aurawpThreeConfig.general.reducedMotion ? 1 : 0.1;
		this.currentScroll += (this.targetScroll - this.currentScroll) * lerpFactor;

		// Calculate normalized scroll (0 to 1)
		const maxScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
		const normalizedScroll = this.currentScroll / maxScroll;

		// Notify callback with scroll data
		if (this.onScrollCallback) {
			this.onScrollCallback({
				current: this.currentScroll,
				target: this.targetScroll,
				delta: this.scrollDelta,
				normalized: normalizedScroll,
			});
		}

		// Continue animation if there's still difference
		if (Math.abs(this.targetScroll - this.currentScroll) > 0.1) {
			this.rafId = requestAnimationFrame(this.update.bind(this));
		} else {
			this.currentScroll = this.targetScroll;
			this.scrollDelta = 0;
			this.rafId = null;
		}
	}

	getScrollData() {
		const maxScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
		
		return {
			current: this.currentScroll,
			target: this.targetScroll,
			delta: this.scrollDelta,
			normalized: this.currentScroll / maxScroll,
			maxScroll,
		};
	}

	setScrollPosition(position) {
		this.targetScroll = Math.max(0, position);
	}

	destroy() {
		if (this.rafId) {
			cancelAnimationFrame(this.rafId);
			this.rafId = null;
		}

		window.removeEventListener('scroll', this.handleScroll.bind(this));
		window.removeEventListener('resize', this.handleResize.bind(this));
		window.removeEventListener('wheel', this.handleWheel.bind(this));
	}
}

export default ScrollSync;
