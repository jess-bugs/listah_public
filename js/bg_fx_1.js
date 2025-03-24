$('document').ready(function() {
    
    
    
    let scene, camera, renderer, particles, particleSystem;
    const particleCount = 500;
    
    function init() {
        // Scene, Camera, Renderer
        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
        camera.position.z = 200;
        
        renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);
        
        // Particle Geometry
        particles = new THREE.BufferGeometry();
        let positions = new Float32Array(particleCount * 3);
        let colors = new Float32Array(particleCount * 3);
        
        for (let i = 0; i < particleCount; i++) {
            let x = (Math.random() - 0.5) * 800;
            let y = (Math.random() - 0.5) * 800;
            let z = (Math.random() - 0.5) * 800;
            
            positions[i * 3] = x;
            positions[i * 3 + 1] = y;
            positions[i * 3 + 2] = z;
            
            let color = Math.random();
            colors[i * 3] = color;
            colors[i * 3 + 1] = color;
            colors[i * 3 + 2] = color;
        }
        
        particles.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particles.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        
        // Particle Material
        let particleMaterial = new THREE.PointsMaterial({
            size: 2,
            vertexColors: true,
            transparent: true,
            opacity: 0.8,
            blending: THREE.AdditiveBlending
        });
        
        particleSystem = new THREE.Points(particles, particleMaterial);
        scene.add(particleSystem);
        
        animate();
        window.addEventListener("resize", onWindowResize);
    }
    
    function animate() {
        requestAnimationFrame(animate);
        
        // Move particles randomly
        let positions = particles.attributes.position.array;
        for (let i = 0; i < positions.length; i += 3) {
            positions[i + 1] += Math.sin(Date.now() * 0.0001 + i) * 0.1; // Vertical floating effect
        }
        particles.attributes.position.needsUpdate = true;
        
        renderer.render(scene, camera);
    }
    
    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    }
    
    init();
    
    
})