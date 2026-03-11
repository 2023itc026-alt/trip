<?php 
session_start();
require_once 'db_config.php'; // Required to fix the $conn error

if (!isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$destination = isset($_GET['place']) ? htmlspecialchars($_GET['place']) : "Unknown City";

// 1. Fetch dynamic places from Database
$places_query = $conn->query("SELECT name, cat, description as 'desc', image as 'img' FROM explore_places");
$combined_places = [];
while($row = $places_query->fetch_assoc()) {
    // Add "images/" prefix to dynamic images
    $row['img'] = "images/" . $row['img']; 
    $combined_places[] = $row;
}

// 2. Manually add your original static images to the same array
$static_items = [
    ['name' => "Burj Khalifa", 'cat' => "Hotel", 'desc' => "7-star luxury stay", 'img' => "bur.jpg"],
    ['name' => "Palm Jumeirah", 'cat' => "island", 'desc' => "ultra-luxury vacations", 'img' => "palm.webp"],
    ['name' => "Dubai Mall", 'cat' => "Mall", 'desc' => "Massive shopping center", 'img' => "mall.webp"],
    ['name' => "Museum of the Future", 'cat' => "Museum", 'desc' => "Tech enthusiasts paradise", 'img' => "museum.jpg"],
    ['name' => "Desert Safari", 'cat' => "safari", 'desc' => "Essential 4–7 hour experience", 'img' => "safari.jfif"]
];

// 3. Merge them together
$final_list = array_merge($combined_places, $static_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plan Trip to <?php echo $destination; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Change height to min-height so it can grow */
.planner-layout { 
    display: grid; 
    grid-template-columns: 350px 1fr 350px; 
    gap: 20px; 
    padding: 30px; 
    color: white; 
    min-height: 100vh; /* Changed from fixed 90vh */
    align-items: start;
}

/* Remove fixed overflow so the whole page scrolls together */
.column { 
    background: var(--glass); 
    border-radius: 15px; 
    padding: 20px; 
    border: 1px solid rgba(255,255,255,0.1); 
    height: auto; /* Let it expand based on content */
}
        
        /* Search & Categories */
        .search-box { width: 100%; padding: 12px; border-radius: 25px; border: none; margin-bottom: 15px; background: rgba(255,255,255,0.1); color: white; }
        .cat-btn { background: none; border: 1px solid var(--primary); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; cursor: pointer; margin: 2px; }
        .cat-btn.active { background: var(--primary); }

        /* Place Cards */
        .place-card { background: rgba(0,0,0,0.3); padding: 15px; border-radius: 10px; margin-bottom: 15px; transition: 0.3s; }
        .place-card:hover { border: 1px solid var(--primary); }
        .place-card img { width: 100%; height: 120px; object-fit: cover; border-radius: 5px; }
        .add-btn { background: var(--primary); border: none; color: white; padding: 5px 15px; border-radius: 5px; cursor: pointer; float: right; margin-top: 5px; }

        /* Itinerary List */
        .itinerary-item { background: #2a2a40; padding: 10px; border-radius: 5px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--primary); }
    </style>
</head>
<body>
    <div class="planner-layout">
        
        <div class="column">
            <h3>Explore <?php echo $destination; ?></h3>
            <input type="text" id="searchInput" class="search-box" placeholder="Search places..." onkeyup="filterPlaces()">
            
           <div class="category-filters" style="margin-bottom: 20px;">
    <button class="cat-btn active" onclick="filterCat('all', this)">All</button>
    <button class="cat-btn" onclick="filterCat('Hotel', this)">Hotels</button>
    <button class="cat-btn" onclick="filterCat('Restaurant', this)">Food</button>
    <button class="cat-btn" onclick="filterCat('Museum', this)">Culture</button>
    <button class="cat-btn" onclick="filterCat('Park', this)">Nature</button>
</div>

            <div id="suggestionsBox">
                </div>
        </div>

        <div class="column" style="text-align: center;">
            <h2>Trip to <?php echo $destination; ?></h2>
            <p>Customize your dream vacation</p>
            <hr style="opacity: 0.2; margin: 20px 0;">
            <div id="itineraryBox">
                <p style="opacity: 0.5;">No items added to your list yet.</p>
            </div>
        </div>

        <div class="column">
    <h3>Final Plan</h3>
    <form action="process.php" method="POST">
        <input type="hidden" name="destination" value="<?php echo $destination; ?>">
        
        <label>Arrival Date:</label>
        <input type="date" name="travel_date" class="search-box" required>
        
        <textarea name="itinerary" id="hiddenData" style="display:none;"></textarea>
        
        <button type="submit" name="save_trip" class="book-btn" style="width: 100%; margin-top: 20px;">Save Trip to Profile</button>
    </form>
</div>
    </div>

    <script>
    // Merging Database places and Static places into one JS array
    const dbPlaces = <?php echo json_encode($combined_places); ?>;
    const staticPlaces = <?php echo json_encode($static_items); ?>;
    
    // Combine them into a single array
    const places = [...dbPlaces, ...staticPlaces];

    let myPlan = [];

    function displayPlaces(data) {
        const box = document.getElementById('suggestionsBox');
        box.innerHTML = data.map(p => {
            // Logic to handle different image paths for DB vs Static
            const imgSrc = p.img ? p.img : `images/${p.image}`;
            const description = p.desc ? p.desc : p.description;
            
            return `
                <div class="place-card">
                    <img src="${imgSrc}" alt="${p.name}">
                    <h4>${p.name}</h4>
                    <small>${p.cat} | ${description}</small>
                    <button class="add-btn" onclick="addToPlan('${p.name}')">+</button>
                </div>
            `;
        }).join('');
    }

    // Initial Load
    displayPlaces(places);

        function filterPlaces() {
            const val = document.getElementById('searchInput').value.toLowerCase();
            const filtered = places.filter(p => p.name.toLowerCase().includes(val));
            displayPlaces(filtered);
        }

       function filterCat(cat, btn) {
    // 1. Remove 'active' class from all buttons
    document.querySelectorAll('.cat-btn').forEach(button => {
        button.classList.remove('active');
    });

    // 2. Add 'active' class to the clicked button
    btn.classList.add('active');

    // 3. Filter the data
    const filtered = (cat === 'all') 
        ? places 
        : places.filter(p => p.cat === cat);
    
    displayPlaces(filtered);
}
        function addToPlan(name) {
            myPlan.push(name);
            updateUI();
        }

        function updateUI() {
    const box = document.getElementById('itineraryBox');
    box.innerHTML = myPlan.map(item => `<div class="itinerary-item"><span>${item}</span></div>`).join('');
    
    // This line sends the list to the PHP form
    document.getElementById('hiddenData').value = myPlan.join(', ');
}

        // Initial Load
        displayPlaces(places);
    </script>
</body>
</html>