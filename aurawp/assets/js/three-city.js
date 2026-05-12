// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Main Three.js City Background Module
 * Mirror's Edge style 3D city background with floating platforms, parallax, and path markers
 */

import * as THREE from 'three';
import { aurawpThreeConfig } from './three-city-config.js';
import { FloatingPlatforms } from './features/floating-platforms.js';
import { ParallaxSystem } from './features/parallax-system.js';
import { PathMarkers } from './features/path-markers.js';
import { ScrollSync } from './utils/scroll-sync.js';

export class ThreeCityBackground {
constructor(containerId = 'aurawp-three-bg') {
this.containerId = containerId;
this.container = null;
this.scene = null;
this.camera = null;
this.renderer = null;
this.clock = new THREE.Clock();

// Features
this.platforms = null;
this.parallax = null;
this.pathMarkers = null;
this.scrollSync = null;

// State
this.isInitialized = false;
this.animationFrameId = null;
this.isVisible = true;

// Bind methods
this.animate = this.animate.bind(this);
this.handleResize = this.handleResize.bind(this);
this.handleVisibilityChange = this.handleVisibilityChange.bind(this);

// Check if should disable on weak devices
this.shouldDisable = this.checkWeakDevice();
}

checkWeakDevice() {
// Disable on very weak mobile devices
if (!aurawpThreeConfig.general.isMobile) return false;

// Check for low memory devices
if (navigator.deviceMemory && navigator.deviceMemory < 2) {
return true;
}

return false;
}

init() {
if (this.isInitialized || this.shouldDisable) {
console.log('[ThreeCity] Initialization skipped:', this.shouldDisable ? 'weak device' : 'already initialized');
return false;
}

try {
this.container = document.getElementById(this.containerId);

if (!this.container) {
console.warn('[ThreeCity] Container not found:', this.containerId);
return false;
}

// Apply user config from WordPress Customizer
this.applyUserConfig();

// Setup scene
this.setupScene();
this.setupCamera();
this.setupRenderer();
this.setupLights();

// Initialize features
this.initFeatures();

// Setup event listeners
this.setupEventListeners();

// Start animation loop
this.animate();

this.isInitialized = true;
console.log('[ThreeCity] Initialized successfully');

return true;
} catch (error) {
console.error('[ThreeCity] Initialization failed:', error);
this.dispose();
return false;
}
}

applyUserConfig() {
// Apply WordPress Customizer settings if available
if (window.aurawpThreeUserConfig) {
const userConfig = window.aurawpThreeUserConfig;

if (userConfig.platforms) {
Object.assign(aurawpThreeConfig.platforms, userConfig.platforms);
}
if (userConfig.parallax) {
Object.assign(aurawpThreeConfig.parallax, userConfig.parallax);
}
if (userConfig.pathMarkers) {
Object.assign(aurawpThreeConfig.pathMarkers, userConfig.pathMarkers);
}
if (userConfig.general) {
Object.assign(aurawpThreeConfig.general, userConfig.general);
}
}
}

setupScene() {
this.scene = new THREE.Scene();
}

setupCamera() {
const aspect = this.container.clientWidth / this.container.clientHeight;
this.camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000);
}

setupRenderer() {
this.renderer = new THREE.WebGLRenderer({
alpha: true,
antialias: !aurawpThreeConfig.general.isMobile,
powerPreference: 'high-performance',
preserveDrawingBuffer: false,
});

this.renderer.setPixelRatio(aurawpThreeConfig.general.pixelRatio);
this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
this.renderer.setClearColor(0x1a1a2e, 1);
this.renderer.outputColorSpace = THREE.SRGBColorSpace;
this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
this.renderer.toneMappingExposure = 1.0;

// Performance optimizations
if (aurawpThreeConfig.performance.disableShadows) {
this.renderer.shadowMap.enabled = false;
}

this.container.appendChild(this.renderer.domElement);

// Set accessibility attributes
this.renderer.domElement.setAttribute('aria-hidden', 'true');
this.renderer.domElement.setAttribute('role', 'presentation');
}

setupLights() {
// Ambient light
const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
this.scene.add(ambientLight);

// Directional light (sun/moon)
const directionalLight = new THREE.DirectionalLight(0xffaa00, 0.8);
directionalLight.position.set(10, 20, 10);

if (!aurawpThreeConfig.performance.disableShadows) {
directionalLight.castShadow = true;
directionalLight.shadow.mapSize.width = 1024;
directionalLight.shadow.mapSize.height = 1024;
}

this.scene.add(directionalLight);

// Point lights for Mirror's Edge style orange highlights
const pointLight1 = new THREE.PointLight(0xff6b00, 1, 50);
pointLight1.position.set(-10, 10, -10);
this.scene.add(pointLight1);

const pointLight2 = new THREE.PointLight(0x00aaff, 0.5, 50);
pointLight2.position.set(10, -10, 10);
this.scene.add(pointLight2);
}

initFeatures() {
// Initialize parallax system first (sets up fog and camera)
this.parallax = new ParallaxSystem(this.scene, this.camera);

// Initialize floating platforms
this.platforms = new FloatingPlatforms(this.scene);

// Initialize path markers
this.pathMarkers = new PathMarkers(this.scene);

// Setup scroll sync
this.scrollSync = new ScrollSync(this.handleScroll.bind(this));
}

setupEventListeners() {
window.addEventListener('resize', this.handleResize);
document.addEventListener('visibilitychange', this.handleVisibilityChange);
}

handleScroll(scrollData) {
if (this.parallax) {
this.parallax.updateScroll(scrollData.delta);
}
}

handleResize() {
if (!this.camera || !this.renderer) return;

const width = this.container.clientWidth;
const height = this.container.clientHeight;

this.camera.aspect = width / height;
this.camera.updateProjectionMatrix();
this.renderer.setSize(width, height);
}

handleVisibilityChange() {
this.isVisible = document.visibilityState === 'visible';

if (!this.isVisible && this.animationFrameId) {
cancelAnimationFrame(this.animationFrameId);
this.animationFrameId = null;
} else if (this.isVisible && !this.animationFrameId) {
this.animate();
}
}

animate() {
if (!this.isVisible || !this.isInitialized) return;

this.animationFrameId = requestAnimationFrame(this.animate);

const deltaTime = Math.min(this.clock.getDelta(), 0.1); // Cap delta time

// Update features
if (this.platforms) {
this.platforms.update(deltaTime);
}
if (this.parallax) {
this.parallax.update(deltaTime);
}
if (this.pathMarkers) {
this.pathMarkers.update(deltaTime);
}

// Render
this.renderer.render(this.scene, this.camera);
}

dispose() {
console.log('[ThreeCity] Disposing...');

// Stop animation
if (this.animationFrameId) {
cancelAnimationFrame(this.animationFrameId);
this.animationFrameId = null;
}

// Dispose features
if (this.platforms) {
this.platforms.dispose();
this.platforms = null;
}
if (this.parallax) {
this.parallax.dispose();
this.parallax = null;
}
if (this.pathMarkers) {
this.pathMarkers.dispose();
this.pathMarkers = null;
}
if (this.scrollSync) {
this.scrollSync.destroy();
this.scrollSync = null;
}

// Remove event listeners
if (this.container) {
window.removeEventListener('resize', this.handleResize);
document.removeEventListener('visibilitychange', this.handleVisibilityChange);
}

// Dispose renderer
if (this.renderer) {
this.renderer.dispose();
if (this.container && this.renderer.domElement.parentNode === this.container) {
this.container.removeChild(this.renderer.domElement);
}
this.renderer = null;
}

// Dispose scene
if (this.scene) {
this.scene.clear();
this.scene = null;
}

this.camera = null;
this.isInitialized = false;
}
}

// Auto-initialize on DOM ready
let threeCityInstance = null;

function initOnReady() {
if (!threeCityInstance) {
threeCityInstance = new ThreeCityBackground('aurawp-three-bg');
threeCityInstance.init();
}
}

// Expose dispose function globally for cleanup
window.aurawpThreeCity = {
dispose: () => {
if (threeCityInstance) {
threeCityInstance.dispose();
threeCityInstance = null;
}
},
getInstance: () => threeCityInstance,
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', initOnReady);
} else {
initOnReady();
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
if (threeCityInstance) {
threeCityInstance.dispose();
}
});

export default ThreeCityBackground;
