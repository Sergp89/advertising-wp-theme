// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Floating Platforms Feature
 * Creates InstancedMesh platforms with hover animation and optional bridges
 */

import * as THREE from 'three';
import { aurawpThreeConfig } from '../three-city-config.js';

export class FloatingPlatforms {
	constructor(scene, config = {}) {
		this.scene = scene;
		this.config = { ...aurawpThreeConfig.platforms, ...config };
		this.platforms = null;
		this.bridges = null;
		this.dummy = new THREE.Object3D();
		this.time = 0;
		this.platformData = [];

		if (this.config.enabled && !aurawpThreeConfig.general.reducedMotion) {
			this.init();
		}
	}

	init() {
		const count = this.getPlatformCount();
		this.createPlatforms(count);
		
		if (this.config.bridgesEnabled) {
			this.createBridges();
		}
	}

	getPlatformCount() {
		const { isMobile } = aurawpThreeConfig.general;
		const { performance } = aurawpThreeConfig;

		if (isMobile) {
			return Math.min(this.config.count, performance.maxPlatformsMobile);
		}

		// Tablet detection
		if (/iPad|Tablet/i.test(navigator.userAgent)) {
			return Math.min(this.config.count, performance.maxPlatformsTablet);
		}

		return this.config.count;
	}

	createPlatforms(count) {
		const geometry = new THREE.BoxGeometry(1, 0.5, 1);
		const material = new THREE.MeshStandardMaterial({
			color: this.config.color,
			transparent: true,
			opacity: this.config.opacity,
			roughness: 0.7,
			metalness: 0.3,
			side: THREE.DoubleSide,
		});

		this.platforms = new THREE.InstancedMesh(geometry, material, count);
		this.platforms.instanceMatrix.setUsage(THREE.DynamicDrawUsage);

		for (let i = 0; i < count; i++) {
			const size = this.randomRange(this.config.minSize, this.config.maxSize);
			const x = this.randomRange(-this.config.spreadX / 2, this.config.spreadX / 2);
			const z = this.randomRange(-this.config.spreadZ / 2, this.config.spreadZ / 2);
			const y = this.randomRange(this.config.minHeight, this.config.maxHeight);

			this.dummy.position.set(x, y, z);
			this.dummy.scale.set(size, 1, size);
			this.dummy.rotation.y = Math.random() * Math.PI * 2;
			this.dummy.updateMatrix();

			this.platforms.setMatrixAt(i, this.dummy.matrix);
			this.platformData.push({
				baseY: y,
				size,
				offset: Math.random() * Math.PI * 2,
				rotationSpeed: this.config.rotationSpeed * (Math.random() > 0.5 ? 1 : -1),
			});
		}

		this.platforms.castShadow = !aurawpThreeConfig.performance.disableShadows;
		this.platforms.receiveShadow = !aurawpThreeConfig.performance.disableShadows;
		this.scene.add(this.platforms);
	}

	createBridges() {
		if (this.platformData.length < 2) return;

		const bridgePoints = [];
		const sortedData = [...this.platformData].sort((a, b) => a.baseY - b.baseY);

		// Create bridges between nearby platforms at similar heights
		for (let i = 0; i < sortedData.length - 1; i++) {
			const current = sortedData[i];
			const next = sortedData[i + 1];

			if (Math.abs(current.baseY - next.baseY) < 5 && Math.random() > 0.5) {
				bridgePoints.push(new THREE.Vector3(
					this.randomRange(-this.config.spreadX / 2, this.config.spreadX / 2),
					current.baseY,
					this.randomRange(-this.config.spreadZ / 2, this.config.spreadZ / 2)
				));
			}
		}

		if (bridgePoints.length < 2) return;

		const curve = new THREE.CatmullRomCurve3(bridgePoints, false, 'catmullrom', 0.5);
		const geometry = new THREE.TubeGeometry(curve, 64, 0.2, 8, false);
		const material = new THREE.MeshStandardMaterial({
			color: this.config.bridgeColor,
			transparent: true,
			opacity: this.config.bridgeOpacity,
			emissive: this.config.bridgeColor,
			emissiveIntensity: 0.3,
		});

		this.bridges = new THREE.Mesh(geometry, material);
		this.scene.add(this.bridges);
	}

	update(deltaTime) {
		if (!this.platforms || aurawpThreeConfig.general.reducedMotion) return;

		this.time += deltaTime * this.config.hoverSpeed;

		for (let i = 0; i < this.platformData.length; i++) {
			const data = this.platformData[i];
			const hoverOffset = Math.sin(this.time + data.offset) * this.config.hoverAmplitude;

			this.dummy.position.set(0, data.baseY + hoverOffset, 0);
			this.dummy.rotation.y += data.rotationSpeed * deltaTime * 60;
			this.dummy.scale.set(data.size, 1, data.size);
			this.dummy.updateMatrix();

			this.platforms.setMatrixAt(i, this.dummy.matrix);
		}

		this.platforms.instanceMatrix.needsUpdate = true;
	}

	randomRange(min, max) {
		return Math.random() * (max - min) + min;
	}

	dispose() {
		if (this.platforms) {
			this.platforms.geometry.dispose();
			this.platforms.material.dispose();
			this.scene.remove(this.platforms);
			this.platforms = null;
		}

		if (this.bridges) {
			this.bridges.geometry.dispose();
			this.bridges.material.dispose();
			this.scene.remove(this.bridges);
			this.bridges = null;
		}

		this.platformData = [];
	}
}

export default FloatingPlatforms;
