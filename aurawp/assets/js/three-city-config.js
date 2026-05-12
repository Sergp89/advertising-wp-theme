// @wordpress-version: 6.5+
// @three-version: 0.160.0

/**
 * Three.js City Background Configuration
 * Mirror's Edge style floating platforms, parallax, and path markers
 */

export const aurawpThreeConfig = {
	// General settings
	general: {
		debug: false,
		autoResize: true,
		pixelRatio: Math.min(window.devicePixelRatio || 1, 2),
		reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
		isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
	},

	// Floating platforms configuration
	platforms: {
		enabled: true,
		count: 50,
		minSize: 2,
		maxSize: 8,
		minHeight: -10,
		maxHeight: 30,
		spreadX: 60,
		spreadZ: 60,
		color: '#ffffff',
		opacity: 0.9,
		hoverSpeed: 0.5,
		hoverAmplitude: 0.5,
		rotationSpeed: 0.002,
		bridgesEnabled: true,
		bridgeColor: '#ff6b00',
		bridgeOpacity: 0.6,
	},

	// Parallax system configuration
	parallax: {
		enabled: true,
		layers: 3,
		baseDepth: 10,
		depthMultiplier: 0.3,
		fogNear: 20,
		fogFar: 100,
		fogColor: '#1a1a2e',
		cameraBaseY: 5,
		cameraRangeY: 10,
		scrollSensitivity: 0.002,
	},

	// Path markers configuration
	pathMarkers: {
		enabled: true,
		pointCount: 30,
		curveTension: 0.5,
		tubeRadius: 0.3,
		tubeSegments: 64,
		color: '#ff6b00',
		glowIntensity: 1.5,
		flowSpeed: 0.001,
		opacity: 0.8,
		pulseEnabled: true,
		pulseSpeed: 2,
		pulseAmplitude: 0.3,
	},

	// Performance settings
	performance: {
		maxPlatformsMobile: 20,
		maxPlatformsTablet: 35,
		disableShadows: true,
		limitFPS: 60,
	},
};

export default aurawpThreeConfig;
