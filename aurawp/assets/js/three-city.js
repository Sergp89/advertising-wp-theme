/**
 * AuraWP Three.js City Scene
 * 
 * Creates a minimalist 3D cityscape inspired by Mirror's Edge
 * with scroll-controlled camera movement
 * 
 * @package AuraWP
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * AuraThreeJS - Main Three.js scene manager
     */
    window.AuraThreeJS = {
        // Scene components
        scene: null,
        camera: null,
        renderer: null,
        buildings: [],
        fog: null,
        
        // Settings from WordPress Customizer
        settings: {
            cameraSpeed: 0.5,
            fogDensity: 0.02,
            lodLevel: 1,
            reducedMotion: false
        },
        
        // Animation state
        isAnimating: false,
        scrollProgress: 0,
        targetScrollProgress: 0,
        
        /**
         * Initialize the Three.js scene
         */
        init: function() {
            // Check for reduced motion preference
            this.settings.reducedMotion = aurawpSettings?.reducedMotion || 
                window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            // Don't initialize if reduced motion is enabled
            if (this.settings.reducedMotion) {
                console.log('Reduced motion enabled - skipping 3D scene');
                return;
            }
            
            // Load settings from WordPress
            this.settings.cameraSpeed = aurawpSettings?.cameraSpeed || 0.5;
            this.settings.fogDensity = aurawpSettings?.fogDensity || 0.02;
            this.settings.lodLevel = aurawpSettings?.lodLevel || 1;
            
            try {
                this.createScene();
                this.createCamera();
                this.createRenderer();
                this.createCity();
                this.addLights();
                this.bindEvents();
                this.animate();
                
                this.isAnimating = true;
                console.log('Three.js scene initialized successfully');
            } catch (error) {
                console.error('Failed to initialize Three.js scene:', error);
                this.showFallback();
            }
        },
        
        /**
         * Create the Three.js scene
         */
        createScene: function() {
            this.scene = new THREE.Scene();
            
            // Add fog based on settings
            const fogColor = this.getFogColor();
            this.fog = new THREE.FogExp2(fogColor, this.settings.fogDensity);
            this.scene.fog = this.fog;
        },
        
        /**
         * Get fog color based on current theme
         * 
         * @return {THREE.Color} Fog color
         */
        getFogColor: function() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            return new THREE.Color(isDark ? 0x09090b : 0xfafafa);
        },
        
        /**
         * Create and position the camera
         */
        createCamera: function() {
            const canvas = document.getElementById('three-canvas');
            const width = canvas.clientWidth;
            const height = canvas.clientHeight;
            
            this.camera = new THREE.PerspectiveCamera(
                75, // Field of view
                width / height, // Aspect ratio
                0.1, // Near clipping plane
                1000 // Far clipping plane
            );
            
            // Initial camera position - starting point of the flight
            this.camera.position.set(0, 5, 20);
            this.camera.rotation.x = -0.1;
        },
        
        /**
         * Create the WebGL renderer
         */
        createRenderer: function() {
            const canvas = document.getElementById('three-canvas');
            
            this.renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                antialias: true,
                alpha: true,
                powerPreference: 'high-performance'
            });
            
            const width = canvas.clientWidth;
            const height = canvas.clientHeight;
            
            this.renderer.setSize(width, height);
            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            
            // Handle window resize
            window.addEventListener('resize', this.onResize.bind(this));
        },
        
        /**
         * Create the procedural city
         */
        createCity: function() {
            const citySize = 200;
            const buildingCount = 150 * this.settings.lodLevel;
            
            // Create ground plane
            this.createGround();
            
            // Generate buildings
            for (let i = 0; i < buildingCount; i++) {
                this.createBuilding(citySize);
            }
        },
        
        /**
         * Create ground plane
         */
        createGround: function() {
            const geometry = new THREE.PlaneGeometry(300, 300, 50, 50);
            
            // Create gradient material
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const material = new THREE.MeshStandardMaterial({
                color: isDark ? 0x18181b : 0xe4e4e7,
                roughness: 0.8,
                metalness: 0.2,
                wireframe: false
            });
            
            const ground = new THREE.Mesh(geometry, material);
            ground.rotation.x = -Math.PI / 2;
            ground.position.y = -2;
            ground.receiveShadow = true;
            
            this.scene.add(ground);
        },
        
        /**
         * Create a single building
         * 
         * @param {number} citySize - Size of the city area
         */
        createBuilding: function(citySize) {
            // Random building dimensions
            const width = Math.random() * 3 + 1;
            const depth = Math.random() * 3 + 1;
            const height = Math.random() * 15 + 3;
            
            const geometry = new THREE.BoxGeometry(width, height, depth);
            
            // Glass-like material with Mirror's Edge aesthetic
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const primaryColor = isDark ? 0x6366f1 : 0x4f46e5;
            const secondaryColor = isDark ? 0xec4899 : 0xdb2777;
            
            // Randomly choose between primary and accent colors
            const useAccent = Math.random() > 0.85;
            const color = useAccent ? secondaryColor : primaryColor;
            
            const material = new THREE.MeshStandardMaterial({
                color: color,
                roughness: 0.1,
                metalness: 0.8,
                transparent: true,
                opacity: 0.8,
                emissive: color,
                emissiveIntensity: 0.2
            });
            
            const building = new THREE.Mesh(geometry, material);
            
            // Position building randomly within city bounds
            const x = (Math.random() - 0.5) * citySize;
            const z = (Math.random() - 0.5) * citySize;
            
            building.position.set(x, height / 2 - 2, z);
            building.castShadow = true;
            building.receiveShadow = true;
            
            // Store reference for animation
            this.buildings.push(building);
            
            this.scene.add(building);
        },
        
        /**
         * Add lighting to the scene
         */
        addLights: function() {
            // Ambient light
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
            this.scene.add(ambientLight);
            
            // Directional light (sun/moon)
            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(50, 50, 50);
            directionalLight.castShadow = true;
            directionalLight.shadow.mapSize.width = 2048;
            directionalLight.shadow.mapSize.height = 2048;
            directionalLight.shadow.camera.near = 0.5;
            directionalLight.shadow.camera.far = 500;
            directionalLight.shadow.camera.left = -100;
            directionalLight.shadow.camera.right = 100;
            directionalLight.shadow.camera.top = 100;
            directionalLight.shadow.camera.bottom = -100;
            this.scene.add(directionalLight);
            
            // Point lights for atmosphere
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            if (isDark) {
                // Add colored point lights in dark mode
                const pointLight1 = new THREE.PointLight(0x6366f1, 0.5, 50);
                pointLight1.position.set(-20, 10, -20);
                this.scene.add(pointLight1);
                
                const pointLight2 = new THREE.PointLight(0xec4899, 0.5, 50);
                pointLight2.position.set(20, 10, 20);
                this.scene.add(pointLight2);
            }
        },
        
        /**
         * Bind event listeners
         */
        bindEvents: function() {
            // Scroll handler for camera movement
            window.addEventListener('scroll', this.onScroll.bind(this), { passive: true });
            
            // Listen for theme changes
            window.addEventListener('aurawp-theme-change', this.onThemeChange.bind(this));
            
            // Listen for reduced motion changes
            window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', (e) => {
                this.settings.reducedMotion = e.matches;
                if (e.matches) {
                    this.isAnimating = false;
                }
            });
        },
        
        /**
         * Handle scroll events
         */
        onScroll: function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            
            // Calculate scroll progress (0 to 1)
            this.targetScrollProgress = scrollTop / docHeight;
        },
        
        /**
         * Handle window resize
         */
        onResize: function() {
            const canvas = document.getElementById('three-canvas');
            const width = canvas.clientWidth;
            const height = canvas.clientHeight;
            
            this.camera.aspect = width / height;
            this.camera.updateProjectionMatrix();
            
            this.renderer.setSize(width, height);
            this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        },
        
        /**
         * Handle theme change
         * 
         * @param {CustomEvent} event - Theme change event
         */
        onThemeChange: function(event) {
            const isDark = event.detail.theme === 'dark';
            
            // Update fog color
            this.scene.fog.color.set(isDark ? 0x09090b : 0xfafafa);
            
            // Update ground color
            const ground = this.scene.children.find(child => child.geometry?.type === 'PlaneGeometry');
            if (ground && ground.material) {
                ground.material.color.set(isDark ? 0x18181b : 0xe4e4e7);
            }
            
            // Update building materials
            this.buildings.forEach((building) => {
                if (building.material) {
                    const isAccent = Math.random() > 0.85;
                    const color = isAccent 
                        ? (isDark ? 0xec4899 : 0xdb2777)
                        : (isDark ? 0x6366f1 : 0x4f46e5);
                    
                    building.material.color.set(color);
                    building.material.emissive.set(color);
                }
            });
        },
        
        /**
         * Animation loop
         */
        animate: function() {
            if (!this.isAnimating) return;
            
            requestAnimationFrame(this.animate.bind(this));
            
            // Smooth scroll interpolation
            this.scrollProgress += (this.targetScrollProgress - this.scrollProgress) * 0.05;
            
            // Move camera based on scroll
            this.updateCameraPosition();
            
            // Animate buildings slightly
            this.animateBuildings();
            
            // Render scene
            this.renderer.render(this.scene, this.camera);
        },
        
        /**
         * Update camera position based on scroll
         */
        updateCameraPosition: function() {
            const pathLength = 100;
            const speed = this.settings.cameraSpeed;
            
            // Camera follows a path through the city
            const progress = this.scrollProgress * pathLength;
            
            // Smooth camera movement
            this.camera.position.z = 20 - progress * speed;
            this.camera.position.y = 5 + Math.sin(progress * 0.1) * 3;
            this.camera.position.x = Math.cos(progress * 0.05) * 10;
            
            // Look ahead in the direction of travel
            this.camera.lookAt(
                this.camera.position.x,
                this.camera.position.y - 2,
                this.camera.position.z - 10
            );
            
            // Reset camera when reaching end (infinite loop effect)
            if (this.camera.position.z < -pathLength) {
                this.camera.position.z = 20;
            }
        },
        
        /**
         * Animate buildings with subtle movement
         */
        animateBuildings: function() {
            const time = Date.now() * 0.001;
            
            this.buildings.forEach((building, index) => {
                // Subtle pulsing effect on some buildings
                if (index % 10 === 0 && building.material) {
                    const scale = 1 + Math.sin(time + index) * 0.02;
                    building.scale.set(scale, scale, scale);
                }
            });
        },
        
        /**
         * Show fallback background if WebGL fails
         */
        showFallback: function() {
            const canvas = document.getElementById('three-canvas');
            if (canvas) {
                canvas.style.display = 'none';
            }
            
            const fallback = document.querySelector('.fallback-bg');
            if (fallback) {
                fallback.style.display = 'block';
            }
        },
        
        /**
         * Destroy the scene and clean up
         */
        destroy: function() {
            this.isAnimating = false;
            
            if (this.renderer) {
                this.renderer.dispose();
            }
            
            if (this.scene) {
                this.scene.traverse((object) => {
                    if (object.geometry) {
                        object.geometry.dispose();
                    }
                    if (object.material) {
                        if (Array.isArray(object.material)) {
                            object.material.forEach(material => material.dispose());
                        } else {
                            object.material.dispose();
                        }
                    }
                });
            }
        }
    };

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            // Delay initialization slightly for better performance
            setTimeout(() => {
                window.AuraThreeJS.init();
            }, 100);
        });
    } else {
        setTimeout(() => {
            window.AuraThreeJS.init();
        }, 100);
    }

})();
