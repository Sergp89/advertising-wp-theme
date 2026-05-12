// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Path Markers Feature
 * Glowing flow paths using CatmullRomCurve3 and TubeGeometry with AdditiveBlending
 */

import * as THREE from 'three';
import { aurawpThreeConfig } from '../three-city-config.js';

export class PathMarkers {
	constructor(scene, config = {}) {
		this.scene = scene;
		this.config = { ...aurawpThreeConfig.pathMarkers, ...config };
		this.pathMesh = null;
		this.glowMesh = null;
		this.flowOffset = 0;
		this.pulseTime = 0;

		if (this.config.enabled && !aurawpThreeConfig.general.reducedMotion) {
			this.init();
		}
	}

	init() {
		this.createPath();
		this.createGlow();
	}

	createPath() {
		const points = this.generatePathPoints(this.config.pointCount);
		const curve = new THREE.CatmullRomCurve3(
			points,
			false,
			'catmullrom',
			this.config.curveTension
		);

		const geometry = new THREE.TubeGeometry(
			curve,
			this.config.tubeSegments,
			this.config.tubeRadius,
			8,
			false
		);

		const material = new THREE.ShaderMaterial({
			uniforms: {
				color: { value: new THREE.Color(this.config.color) },
				opacity: { value: this.config.opacity },
				flowOffset: { value: 0 },
				time: { value: 0 },
			},
			vertexShader: `
				varying vec2 vUv;
				varying float vZ;
				
				void main() {
					vUv = uv;
					vZ = position.z;
					gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
				}
			`,
			fragmentShader: `
				uniform vec3 color;
				uniform float opacity;
				uniform float flowOffset;
				uniform float time;
				
				varying vec2 vUv;
				varying float vZ;
				
				void main() {
					float flow = sin(vUv.x * 20.0 + flowOffset + time) * 0.5 + 0.5;
					float pulse = sin(time * 2.0) * 0.3 + 0.7;
					float alpha = opacity * pulse * (0.7 + flow * 0.3);
					
					gl_FragColor = vec4(color, alpha);
				}
			`,
			transparent: true,
			blending: THREE.AdditiveBlending,
			depthWrite: false,
			side: THREE.DoubleSide,
		});

		this.pathMesh = new THREE.Mesh(geometry, material);
		this.pathMesh.frustumCulled = false;
		this.scene.add(this.pathMesh);
	}

	createGlow() {
		if (!this.pathMesh) return;

		const glowGeometry = new THREE.TubeGeometry(
			this.pathMesh.geometry.parameters.path,
			this.config.tubeSegments,
			this.config.tubeRadius * 2.5,
			8,
			false
		);

		const glowMaterial = new THREE.ShaderMaterial({
			uniforms: {
				color: { value: new THREE.Color(this.config.color) },
				intensity: { value: this.config.glowIntensity },
				time: { value: 0 },
			},
			vertexShader: `
				varying vec3 vNormal;
				varying vec3 vPosition;
				
				void main() {
					vNormal = normalize(normalMatrix * normal);
					vPosition = position;
					gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
				}
			`,
			fragmentShader: `
				uniform vec3 color;
				uniform float intensity;
				uniform float time;
				
				varying vec3 vNormal;
				varying vec3 vPosition;
				
				void main() {
					float fresnel = pow(1.0 - abs(dot(vNormal, vec3(0.0, 0.0, 1.0))), 2.0);
					float pulse = sin(time * 2.0) * 0.3 + 0.7;
					float alpha = fresnel * intensity * pulse * 0.5;
					
					gl_FragColor = vec4(color, alpha);
				}
			`,
			transparent: true,
			blending: THREE.AdditiveBlending,
			depthWrite: false,
			side: THREE.BackSide,
		});

		this.glowMesh = new THREE.Mesh(glowGeometry, glowMaterial);
		this.glowMesh.frustumCulled = false;
		this.scene.add(this.glowMesh);
	}

	generatePathPoints(count) {
		const points = [];
		const spreadX = aurawpThreeConfig.platforms.spreadX;
		const spreadZ = aurawpThreeConfig.platforms.spreadZ;
		const maxHeight = aurawpThreeConfig.platforms.maxHeight;

		for (let i = 0; i < count; i++) {
			const t = i / (count - 1);
			const x = Math.sin(t * Math.PI * 2) * (spreadX / 2) * (0.5 + Math.random() * 0.5);
			const z = Math.cos(t * Math.PI * 2) * (spreadZ / 2) * (0.5 + Math.random() * 0.5);
			const y = Math.sin(t * Math.PI * 4) * (maxHeight / 2) + Math.random() * 5;

			points.push(new THREE.Vector3(x, y, z));
		}

		// Close the loop for continuous path
		if (points.length > 0) {
			points[points.length - 1].copy(points[0]);
		}

		return points;
	}

	update(deltaTime) {
		if (!this.pathMesh || aurawpThreeConfig.general.reducedMotion) return;

		this.flowOffset += this.config.flowSpeed * deltaTime * 60;
		this.pulseTime += deltaTime * this.config.pulseSpeed;

		if (this.pathMesh.material.uniforms) {
			this.pathMesh.material.uniforms.flowOffset.value = this.flowOffset;
			this.pathMesh.material.uniforms.time.value = this.pulseTime;
		}

		if (this.glowMesh && this.glowMesh.material.uniforms) {
			this.glowMesh.material.uniforms.time.value = this.pulseTime;
		}
	}

	dispose() {
		if (this.pathMesh) {
			this.pathMesh.geometry.dispose();
			this.pathMesh.material.dispose();
			this.scene.remove(this.pathMesh);
			this.pathMesh = null;
		}

		if (this.glowMesh) {
			this.glowMesh.geometry.dispose();
			this.glowMesh.material.dispose();
			this.scene.remove(this.glowMesh);
			this.glowMesh = null;
		}
	}
}

export default PathMarkers;
