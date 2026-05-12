// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Parallax System Feature
 * Multi-layer depth system with camera movement and fog
 */

import * as THREE from 'three';
import { aurawpThreeConfig } from '../three-city-config.js';

export class ParallaxSystem {
	constructor(scene, camera, config = {}) {
		this.scene = scene;
		this.camera = camera;
		this.config = { ...aurawpThreeConfig.parallax, ...config };
		this.scrollPosition = 0;
		this.targetScrollPosition = 0;
		this.layers = [];

		if (this.config.enabled) {
			this.init();
		}
	}

	init() {
		this.setupFog();
		this.createParallaxLayers();
		this.setupCamera();
	}

	setupFog() {
		const fogColor = new THREE.Color(this.config.fogColor);
		this.scene.fog = new THREE.FogExp2(fogColor, 0.015);
		this.scene.background = fogColor;
	}

	createParallaxLayers() {
		for (let i = 0; i < this.config.layers; i++) {
			const depth = this.config.baseDepth + (i * this.config.depthMultiplier * 10);
			const geometry = new THREE.PlaneGeometry(100, 100);
			const opacity = 0.1 - (i * 0.03);

			const material = new THREE.MeshBasicMaterial({
				color: 0xffffff,
				transparent: true,
				opacity: Math.max(0.02, opacity),
				side: THREE.DoubleSide,
				depthWrite: false,
			});

			const layer = new THREE.Mesh(geometry, material);
			layer.position.z = -depth;
			layer.position.y = i * 5;
			layer.userData.layerIndex = i;
			layer.userData.baseY = layer.position.y;

			this.scene.add(layer);
			this.layers.push(layer);
		}
	}

	setupCamera() {
		this.camera.position.set(0, this.config.cameraBaseY, 20);
		this.camera.lookAt(0, this.config.cameraBaseY, 0);
	}

	updateScroll(scrollDelta) {
		this.targetScrollPosition += scrollDelta * this.config.scrollSensitivity;
		
		// Clamp scroll position
		const maxY = this.config.cameraRangeY;
		this.targetScrollPosition = Math.max(-maxY, Math.min(maxY, this.targetScrollPosition));
	}

	update(deltaTime) {
		if (!this.config.enabled) return;

		// Smooth interpolation for scroll
		this.scrollPosition += (this.targetScrollPosition - this.scrollPosition) * 5 * deltaTime;

		// Update camera position with parallax
		const targetY = this.config.cameraBaseY + this.scrollPosition;
		this.camera.position.y += (targetY - this.camera.position.y) * 3 * deltaTime;

		// Update layers with parallax effect
		this.layers.forEach((layer, index) => {
			const parallaxFactor = 1 - (index / this.config.layers);
			const layerTargetY = layer.userData.baseY + (this.scrollPosition * parallaxFactor * 0.5);
			
			layer.position.y += (layerTargetY - layer.position.y) * 2 * deltaTime;

			// Subtle rotation for depth perception
			layer.rotation.x = Math.sin(Date.now() * 0.0001 + index) * 0.02;
			layer.rotation.y = Math.cos(Date.now() * 0.00015 + index) * 0.02;
		});

		// Update fog based on camera position
		if (this.scene.fog) {
			const fogDensity = 0.015 + (Math.abs(this.scrollPosition) * 0.001);
			this.scene.fog.density = Math.min(0.03, fogDensity);
		}
	}

	setScrollPosition(position) {
		this.targetScrollPosition = position;
	}

	dispose() {
		this.layers.forEach((layer) => {
			layer.geometry.dispose();
			layer.material.dispose();
			this.scene.remove(layer);
		});

		this.layers = [];

		if (this.scene.fog) {
			this.scene.fog = null;
		}
	}
}

export default ParallaxSystem;
