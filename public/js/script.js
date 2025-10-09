// File: public/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired. script.js is running.");

    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-quad'
    });
    console.log("AOS initialized.");
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (navbar) { // Ensure navbar element exists
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-lg');
                navbar.style.padding = '12px 0';
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.style.padding = '18px 0';
            }
        }
    });
    console.log("Navbar scroll effect listener added.");
    
    // Home page hero slider
    if (document.querySelector('.hero-slider')) {
        console.log("Hero slider detected. Initializing.");
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const interval = 5000; // 5 seconds
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        setInterval(nextSlide, interval);
        showSlide(currentSlide); // Show initial slide
    } else {
        console.log("No hero slider detected on this page.");
    }
    
    // Testimonials carousel
    if (document.querySelector('.testimonials-container')) {
        console.log("Testimonials container detected. Populating.");
        const testimonials = [
            {
                name: "Rina Sari",
                role: "Pelanggan Setia",
                text: "Teh Boston memang juara! Rasanya selalu segar dan bikin nagih. Varian rasa lemon grass favorit saya.",
                rating: 5
            },
            {
                name: "Budi Santoso",
                role: "Mitra Franchise",
                text: "Gabung jadi mitra Teh Boston adalah keputusan terbaik. Omzet bisa 3-4 juta per hari dengan modal terjangkau.",
                rating: 5
            },
            {
                name: "Dewi Anggraini",
                role: "Pelanggan",
                text: "Saya suka semua varian yakult series-nya. Fresh banget dan harganya sangat terjangkau untuk kualitas premium.",
                rating: 4
            },
            {
                name: "Ahmad Fauzi",
                role: "Pemilik Booth",
                text: "Dukungan dari tim Teh Boston sangat membantu. Dari awal pendaftaran sampai booth beroperasi semua lancar.",
                rating: 5
            }
        ];
        
        const container = document.querySelector('.testimonials-container');
        
        // Clear existing content to prevent duplicates if script runs multiple times
        if (container) {
             container.innerHTML = ''; 
        }

        testimonials.forEach(testimonial => {
            let stars = '';
            for (let i = 0; i < testimonial.rating; i++) {
                stars += '<i class="fas fa-star text-warning"></i>';
            }
            
            const testimonialHTML = `
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="${testimonial.rating * 100}">
                        <div class="testimonial-rating">${stars}</div>
                        <p class="testimonial-text">"${testimonial.text}"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">${testimonial.name.charAt(0)}</div>
                            <div>
                                <h6 class="mb-0">${testimonial.name}</h6>
                                <small>${testimonial.role}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            if (container) { // Ensure container exists before appending
                container.innerHTML += testimonialHTML;
            }
        });
        console.log("Testimonials populated.");
    } else {
        console.log("No testimonials container detected on this page.");
    }
    
    // Branch map logic (only on pages with a #map element)
    const mapElement = document.getElementById('map');
    if (mapElement) {
        console.log("Map element found. Initializing Leaflet map.");

        let map; // Declare map variable in a scope accessible for invalidateSize calls
        let markers = []; // To store Leaflet markers
        let latLngs = []; // To store Leaflet LatLngs for fitBounds

        try {
            // Initialize map
            map = L.map('map').setView([-6.568248, 107.762785], 13); // Changed zoom to 13 for better initial view
            console.log("Leaflet map initialized successfully.");

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            console.log("Tile layer added to map.");

            // Lokasi cabang-cabang Teh Boston (data)
            const branchesData = [
                {name: "Pasirkareumbi", address: "Jl. Letnan Jenderal S. Parman, Subang", lat: -6.574552, lng: 107.766049},
                {name: "Cinangsi", address: "Jl. Sukamulya Cibogo, Subang", lat: -6.559202, lng: 107.795116},
                {name: "Pejuang 45", address: "Jl. Pejuang 45, Subang", lat: -6.560413, lng: 107.764078},
                {name: "Pamanukan", address: "Jl. Raya Rancasari, Subang", lat: -6.297561, lng: 107.820725},
                {name: "Pagaden", address: "Jl. Jend. Ahmad Yani, Subang", lat: -6.454906, lng: 107.810753},
                {name: "Cigadung", address: "Jl. MT. Haryono, Subang", lat: -6.564782, lng: 107.754463},
                {name: "Depan PTPN", address: "Jl. Otto Iskandar Dinata, Subang", lat: -6.568010, lng: 107.762960},
                {name: "Dangdeur", address: "Jl. Subang - Cidahu, Subang", lat: -6.545368, lng: 107.738344},
                {name: "Depan REGIO", address: "Jl. Otto Iskandar Dinata, Subang", lat: -6.560453585478372, lng: 107.76984101811108},
                {name: "Gunung Sembung", address: "Jl. Pamanukan - Subang - Bandung, Subang", lat: -6.510544, lng: 107.800784},
                {name: "Perumnas Dolog", address: "Jl. Raya Perumnas, Subang", lat: -6.556739, lng: 107.767299},
                {name: "Tubun", address: "Jl. Marsinu, Subang", lat: -6.548934, lng: 107.761504},
                {name: "Kalijati", address: "Jl. Raya Kalijati, Subang", lat: -6.519279583424668,  lng: 107.67403769930847},
                {name: "Panji", address: "Jl. Panji, Subang", lat: -6.566585, lng: 107.750255},
            ];

            // Custom icon for branches
            const customIcon = L.icon({
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                shadowSize: [41, 41]
            });
            console.log("Custom marker icon defined.");

            // Add markers to map and collect LatLngs for fitBounds
            branchesData.forEach(branch => {
                const marker = L.marker([branch.lat, branch.lng], {icon: customIcon}).addTo(map)
                    .bindPopup(`<b>${branch.name}</b><br>${branch.address}`);
                markers.push(marker);
                latLngs.push([branch.lat, branch.lng]);
            });
            console.log("Markers added to map and lat/lngs collected.");

            // Initial fitBounds to show all markers
            if (latLngs.length > 0) {
                const bounds = L.latLngBounds(latLngs);
                map.fitBounds(bounds, { padding: [50, 50] });
                console.log("Map fitted to bounds to show all markers.");
            }

            // Simulate user location (Subang PTPN)
            const userLocation = L.latLng(-6.568248, 107.762785); 
            const userIcon = L.icon({
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-blue.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                shadowSize: [41, 41]
            });
            // L.marker(userLocation, {icon: userIcon}).addTo(map).bindPopup('Lokasi Anda Saat Ini (Simulasi)');
            // console.log("Simulated user location marker added.");

            // Function to calculate distance (Haversine formula)
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radius of Earth in km
                const dLat = deg2rad(lat2 - lat1);
                const dLon = deg2rad(lon2 - lon1);
                
                const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
                    
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                const distance = R * c; // Distance in km
                
                return distance;
            }
            
            function deg2rad(deg) {
                return deg * (Math.PI/180);
            }
            console.log("Distance calculation function defined.");

            // Generate branch cards dynamically and add interactivity
            const branchListContainer = document.querySelector('.branches-list'); // Corrected selector
            if (branchListContainer) {
                // Clear existing content to prevent duplicates if script runs multiple times
                branchListContainer.innerHTML = ''; 
                console.log("Cleared existing branch cards in .branches-list.");

                branchesData.forEach((branch, index) => {
                    const card = document.createElement('div');
                    card.className = 'branch-card';
                    card.setAttribute('data-aos', 'fade-up');
                    card.setAttribute('data-aos-delay', (index + 1) * 100); 

                    // Set first card as active by default
                    if (index === 0) {
                        card.classList.add('active');
                    }
                    
                    // Calculate and display distance for each card
                    const distance = calculateDistance(userLocation.lat, userLocation.lng, branch.lat, branch.lng);
                    
                    card.innerHTML = `
                        <div class="card-body">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> ${branch.name}</h3>
                            <p class="card-text text-muted">${branch.address}</p>
                            <span class="distance"></span>
                        </div>
                    `;
                    
                    card.addEventListener('click', () => {
                        document.querySelectorAll('.branch-card').forEach(c => c.classList.remove('active'));
                        card.classList.add('active');
                        
                        map.setView([branch.lat, branch.lng], 15); // Zoom in on click
                        
                        // Open popup for the corresponding marker
                        markers.forEach(marker => {
                            if (marker.getLatLng().lat === branch.lat && marker.getLatLng().lng === branch.lng) {
                                marker.openPopup();
                            }
                        });
                        console.log(`Clicked branch card: ${branch.name}. Map set view to [${branch.lat}, ${branch.lng}].`);
                    });
                    
                    branchListContainer.appendChild(card);
                });
                console.log("Dynamically generated and appended branch cards.");
            } else {
                console.error("Error: .branches-list container not found for dynamic card generation.");
            }

            // --- Critical for Map Rendering: InvalidateSize logic ---
            // This function will be called on various events to re-render the map
            const resizeMap = () => {
                console.log("resizeMap() called. Checking map state...");
                // Log computed dimensions of map container before invalidateSize
                const mapContainer = document.querySelector('.map-container');
                if (mapContainer) {
                    console.log("Map container computed height (before invalidateSize):", window.getComputedStyle(mapContainer).height);
                    console.log("Map container computed width (before invalidateSize):", window.getComputedStyle(mapContainer).width);
                } else {
                    console.warn(".map-container not found in resizeMap.");
                }

                setTimeout(() => {
                    if (map) {
                        map.invalidateSize({pan: false, animate: false}); // Try with pan:false and animate:false for robustness
                        console.log("Map invalidateSize() executed.");

                        // Fit bounds again after invalidating size to ensure all markers are visible
                        if (latLngs.length > 0) {
                            const bounds = L.latLngBounds(latLngs);
                            map.fitBounds(bounds, { padding: [50, 50] });
                            console.log("Map fitBounds() executed after invalidateSize.");
                        }
                    } else {
                        console.error("Error: 'map' object is not defined when resizeMap tries to invalidate size.");
                    }
                    // Log computed dimensions of map container after invalidateSize
                    if (mapContainer) {
                        console.log("Map container computed height (after invalidateSize):", window.getComputedStyle(mapContainer).height);
                        console.log("Map container computed width (after invalidateSize):", window.getComputedStyle(mapContainer).width);
                    }
                }, 500); // Increased delay to 500ms for robust testing, you can reduce this later
            };

            // Initial call for immediate rendering
            console.log("Calling resizeMap() immediately after map setup.");
            resizeMap();

            // 1. Add a global ResizeObserver for the map container (more robust for dynamic resizes)
            if (typeof ResizeObserver !== 'undefined') {
                const resizeObserver = new ResizeObserver(entries => {
                    for (let entry of entries) {
                        if (entry.target === mapElement && map) {
                            console.log("ResizeObserver triggered for map element. Resizing map.");
                            resizeMap(); // Call our consolidated resize function
                        }
                    }
                });
                resizeObserver.observe(mapElement);
                console.log("ResizeObserver attached to map element.");
            } else {
                // 2. Fallback for older browsers: re-invalidate on window resize
                window.addEventListener('resize', () => {
                    console.log("Window resize event triggered (fallback). Resizing map.");
                    resizeMap();
                });
                window.addEventListener('orientationchange', () => {
                    console.log("Orientation change event triggered. Resizing map.");
                    resizeMap();
                });
                console.log("Window resize/orientationchange listeners attached (fallback).");
            }

            // 3. InvalidateSize if map is in a dynamic container (e.g., Bootstrap Tab, Accordion, Modal)
            //    Uncomment and adapt these sections if your map is initially hidden and then shown.
            //    Make sure the selectors match your actual HTML structure.
            //    If your map is ALWAYS visible on page load, these might not be necessary.

            // Example for Bootstrap Tabs:
            // const branchMapTabTrigger = document.querySelector('a[data-bs-toggle="tab"][href="#branchMapTab"]'); // Adjust selector as needed
            // if (branchMapTabTrigger) {
            //     branchMapTabTrigger.addEventListener('shown.bs.tab', function (event) {
            //         console.log("Bootstrap tab 'shown' event triggered. Resizing map.");
            //         resizeMap();
            //     });
            // }

            // Example for Bootstrap Collapses/Accordions:
            // const branchMapCollapseElement = document.getElementById('mapCollapsePanel'); // Adjust ID to your collapse element
            // if (branchMapCollapseElement) {
            //     branchMapCollapseElement.addEventListener('shown.bs.collapse', function (event) {
            //         console.log("Bootstrap collapse 'shown' event triggered. Resizing map.");
            //         resizeMap();
            //     });
            // }

            // Example for Bootstrap Modals:
            // const branchMapModalElement = document.getElementById('mapModal'); // Adjust ID to your modal element
            // if (branchMapModalElement) {
            //     branchMapModalElement.addEventListener('shown.bs.modal', function (event) {
            //         console.log("Bootstrap modal 'shown' event triggered. Resizing map.");
            //         resizeMap();
            //     });
            // }

        } catch (error) {
            console.error("Error during Leaflet map initialization:", error);
            console.error("Make sure Leaflet CSS and JS are loaded correctly BEFORE script.js.");
            console.error("Also check if the #map element exists in your HTML.");
        }
    } else {
        console.log("Map element (#map) not found on this page. Skipping map initialization.");
    }
});