<?php 
session_start();
if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicles & Guides | Odyssey Travels</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .vg-container { padding: 50px; max-width: 1200px; margin: auto; color: white; height: 100vh; overflow-y: auto; }
        
        /* Tab Styling */
        .tabs { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; }
        .tab-btn { 
            background: rgba(255,255,255,0.1); border: 2px solid transparent; color: white; 
            padding: 10px 30px; border-radius: 30px; cursor: pointer; font-weight: 600; transition: 0.3s;
        }
        .tab-btn.active { background: var(--primary); border-color: var(--primary); }

        .content-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        
        .vg-card { 
            background: var(--glass); border-radius: 15px; overflow: hidden; 
            border: 1px solid rgba(255,255,255,0.1); transition: 0.3s; 
        }
        .vg-card:hover { transform: translateY(-5px); border-color: var(--primary); }
        .vg-card img { width: 100%; height: 200px; object-fit: cover; }
        .vg-info { padding: 20px; }
        
        .badge { background: var(--primary); font-size: 12px; padding: 3px 10px; border-radius: 5px; margin-bottom: 10px; display: inline-block; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="vg-container">
        <a href="index.php" style="color: var(--primary); text-decoration: none;">← Back to Home</a>
        <h1 style="text-align: center; margin: 20px 0;">Our Fleet & Experts</h1>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('vehicles')">Our Vehicles</button>
            <button class="tab-btn" onclick="switchTab('guides')">Certified Guides</button>
        </div>

        <div id="vehicles-section" class="content-grid">
            <div class="vg-card">
                <img src="porsche.jpg" alt="Porsche 911">
                <div class="vg-info">
                    <span class="badge">ultra luxury</span>
                    <h3>Porsche 911</h3>
                    <p>The Porsche 911 is fundamentally a 2+2 seater sports car, featuring two front bucket seats and two very small rear seats suitable for children, pets, or extra storage. </p>
                </div>
            </div>
            <div class="vg-card">
                <img src="mahindra.webp" alt="Mahindra xuv 7xo">
                <div class="vg-info">
                    <span class="badge">premium</span>
                    <h3>Mahindra xuv 7xo</h3>
                    <p>The 2026 Mahindra XUV 7XO is a 7-seater (and 6-seater) SUV, succeeding the XUV700.engine, with 6-speed manual or automatic options. Key updates include a triple 12.3-inch screen layout, 16-speaker Harman Kardon audio, and ADAS Level-2. </p>
                </div>
            </div>
            <div class="vg-card">
                <img src="thar.avif" alt="thar">
                <div class="vg-info">
                    <span class="badge">premium</span>
                    <h3>Thar</h3>
                    <p>Thar is primarily a 4-seater lifestyle SUV. Fully air-conditioned with luggage space.</p>
                </div>
            </div>
            <div class="vg-card">
                <img src="bolero.jpg" alt="Bolero">
                <div class="vg-info">
                    <span class="badge">premium</span>
                    <h3>Bolero</h3>
                    <p> Bolero-seater SUV. Fully air-conditioned with luggage space.</p>
                </div>
            </div>
           <div class="vg-card">
                <img src="bmw.webp" alt="Bmw">
                <div class="vg-info">
                    <span class="badge">luxuary</span>
                    <h3>Bmw</h3>
                    <p> BMW is a  two-seater performance car.amazing riding experience</p>
                </div>
            </div>
      <div class="vg-card">
                <img src="range.avif" alt="range rover">
                <div class="vg-info">
                    <span class="badge">luxuary</span>
                    <h3>Range rover</h3>
                    <p> The Range Rover is available as a luxury 5-seater or a 7-seater, with the 7-seat configuration primarily offered on the Long Wheelbase (LWB) models to accommodate more passengers with heated, powered seating. </p>
                </div>
            </div>
      <div class="vg-card">
                <img src="kia.jpg" alt="Kia carnival">
                <div class="vg-info">
                    <span class="badge">Premium</span>
                    <h3>Kia carnival</h3>
                    <p> The Kia Carnival is a premium MPV, commonly configured as a 7-seater (2+2+3 layout) with luxurious captain seats in the second row, though 8 and 9-seater options exist </p>
                </div>
            </div>

      <div class="vg-card">
                <img src="mercedes.webp" alt="Mercedes van">
                <div class="vg-info">
                    <span class="badge">luxuary</span>
                    <h3>Mercedes van</h3>
                    <p> Mercedes-Benz Sprinter Passenger Van is a premium, high-roof vehicle designed for transporting 12 to 15 passengers comfortably </p>
                </div>
            </div>

      <div class="vg-card">
                <img src="audi.jpg" alt="Audi q3">
                <div class="vg-info">
                    <span class="badge">Premium</span>
                    <h3>Audi q3</h3>
                    <p>The Audi Q3 is a premium 5-seater compact SUV designed for comfort and versatility, featuring high-quality leather upholstery and a flexible seating layout </p>
                </div>
            </div>
        </div>

        <div id="guides-section" class="content-grid hidden">
            <div class="vg-card">
                <img src="tourist1.jpg" alt="Guide 1">
                <div class="vg-info">
                    <span class="badge">5+ Years Exp</span>
                    <h3>James</h3>
                    <p>Expert in History and Architecture. Speaks English, Spanish,German and French.</p>
                </div>
            </div>
            <div class="vg-card">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=500" alt="Guide 2">
                <div class="vg-info">
                    <span class="badge">Nature Expert</span>
                    <h3>Sarah arjun</h3>
                    <p>Specializes in trekking,wildlife photography and forest specialist. Lead guide for Swiss Alps.</p>
                </div>
            </div>
            <div class="vg-card">
                <img src="tourist2.webp" alt="Guide 3">
                <div class="vg-info">
                    <span class="badge">5+ years,Nature Expert</span>
                    <h3>jeeva</h3>
                    <p>Specializes in trekking,local areas and party areas. Lead guide for Goa.</p>
                </div>
            </div>
          <div class="vg-card">
                <img src="guide3.webp" alt="Guide 4">
                <div class="vg-info">
                    <span class="badge">10+ years,Nature Expert</span>
                    <h3>hakkim khan</h3>
                    <p>Specializes in trekking,hidden area,local area. Lead guide for manali.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(type) {
            const vSec = document.getElementById('vehicles-section');
            const gSec = document.getElementById('guides-section');
            const btns = document.querySelectorAll('.tab-btn');

            btns.forEach(b => b.classList.remove('active'));
            
            if(type === 'vehicles') {
                vSec.classList.remove('hidden');
                gSec.classList.add('hidden');
                event.target.classList.add('active');
            } else {
                vSec.classList.add('hidden');
                gSec.classList.remove('hidden');
                event.target.classList.add('active');
            }
        }
    </script>
</body>
</html>