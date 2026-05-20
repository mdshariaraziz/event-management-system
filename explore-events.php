<?php
session_start();
include("config.php");

$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Events | Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        /* Header */
        .site-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1E2F5E, #5D3FD3);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .logo i {
            background: none;
            color: #5D3FD3;
        }

        .back-home {
            color: #5D3FD3;
            text-decoration: none;
            font-weight: 500;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .hero-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .hero-banner p {
            opacity: 0.9;
        }

        /* Search & Filter */
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 24px;
            margin-top: -2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }

        .search-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .search-box {
            flex: 2;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-box input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 1.5px solid #e2e8f0;
            border-radius: 60px;
            font-size: 1rem;
            font-family: inherit;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group select {
            width: 100%;
            padding: 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.95rem;
            background: white;
            font-family: inherit;
        }

        .reset-btn {
            background: #f1f5f9;
            border: none;
            padding: 0 20px;
            border-radius: 60px;
            cursor: pointer;
            font-weight: 500;
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .event-card {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.08);
            transition: 0.25s;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.15);
        }

        .event-img {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .event-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #5D3FD3;
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .event-info {
            padding: 1.5rem;
        }

        .event-category {
            font-size: 0.8rem;
            color: #5D3FD3;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .event-info h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.85rem;
            color: #4a5568;
            margin-bottom: 1rem;
        }

        .event-meta i {
            width: 22px;
            color: #5D3FD3;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .price {
            font-weight: 600;
        }

        .price strong {
            font-size: 1.2rem;
            color: #0f172a;
        }

        .btn-ticket {
            background: #5D3FD3;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-ticket:hover {
            background: #4a2fc2;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        footer {
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            .search-row {
                flex-direction: column;
            }
            .reset-btn {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="container header-container">
        <a href="index.php" class="logo">
            <i class="fas fa-calendar-alt"></i>
            <span>Eventify</span>
        </a>
        <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
    </div>
</header>

<div class="hero-banner">
    <div class="container">
        <h1><i class="fas fa-calendar-alt"></i> Explore Events</h1>
        <p>Discover amazing events happening near you</p>
    </div>
</div>

<div class="container">
    <!-- Search & Filter Section -->
    <div class="filter-section">
        <div class="search-row">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by event title, location...">
            </div>
            <div class="filter-group">
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Concert">Concert</option>
                    <option value="Sports">Sports</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Corporate">Corporate</option>
                </select>
            </div>
            <div class="filter-group">
                <input type="date" id="dateFilter" placeholder="Filter by date">
            </div>
            <button class="reset-btn" id="resetBtn"><i class="fas fa-undo-alt"></i> Reset</button>
        </div>
    </div>

    <!-- Events Grid -->
           
             <div id="eventsGrid" class="events-grid"></div>



</div>

<footer>
    <p>&copy; 2025 Eventify — Your smart event management platform</p>
</footer>

<script>
    // Sample static events (fallback)
    const staticEvents = [
        {
            id: 1001,
            title: "Grand Wedding Expo 2025",
            category: "Wedding",
            date: "2025-05-15",
            location: "Downtown Grand Hall, NYC",
            price: 49,
            seats: 200,
            description: "Largest wedding exhibition",
            imageUrl: "images/pic-4.jpeg",
            organizer: "eventify@demo.com",
            createdAt: "2025-01-01"
        },
        {
            id: 1002,
            title: "Summer Beats Music Festival",
            category: "Concert",
            date: "2025-06-28",
            location: "Riverside Park, LA",
            price: 79,
            seats: 500,
            description: "Live music & food",
            imageUrl: "images/pic-6.webp",
            organizer: "eventify@demo.com",
            createdAt: "2025-01-02"
        },
        {
            id: 1003,
            title: "Global Tech Summit 2025",
            category: "Seminar",
            date: "2025-07-10",
            location: "City Convention Center, SF",
            price: 149,
            seats: 300,
            description: "Tech leaders & innovation",
            imageUrl: "images/pic-8.jpg",
            organizer: "eventify@demo.com",
            createdAt: "2025-01-03"
        },
        {
            id: 1004,
            title: "Charity Sports Marathon",
            category: "Sports",
            date: "2025-08-20",
            location: "Central Park, NYC",
            price: 25,
            seats: 1000,
            description: "Run for a cause",
            imageUrl: "images/pic-9.jpg",
            organizer: "eventify@demo.com",
            createdAt: "2025-01-04"
        }
    ];

    // Load events from localStorage (user-created) + static
 function getAllEvents() {
    return staticEvents.sort((a, b) => new Date(a.date) - new Date(b.date));
}

    let currentEvents = [];

    function renderEvents(events) {
        const grid = document.getElementById('eventsGrid');
        if (!grid) return;

        if (events.length === 0) {
            grid.innerHTML = `<div class="no-results"><i class="fas fa-calendar-times"></i> <h3>No events found</h3><p>Try adjusting your search or filters.</p></div>`;
            return;
        }

        grid.innerHTML = events.map(event => `
            <div class="event-card" data-id="${event.id}">
                <div class="event-img">
                    <img src="${event.imageUrl || 'https://placehold.co/600x400/2C3E50/white?text=Event'}" alt="${event.title}" onerror="this.src='https://placehold.co/600x400/2C3E50/white?text=Event+Image'">
                    <span class="event-badge">${event.category}</span>
                </div>
                <div class="event-info">
                    <div class="event-category"><i class="fas fa-tag"></i> ${event.category}</div>
                    <h3>${escapeHtml(event.title)}</h3>
                    <div class="event-meta">
                        <span><i class="fas fa-calendar-day"></i> ${formatDate(event.date)}</span>
                        <span><i class="fas fa-map-marker-alt"></i> ${escapeHtml(event.location)}</span>
                    </div>
                    <div class="event-footer">
                        <div class="price">From <strong>$${event.price}</strong></div>
                        <a href="book-ticket.php?id=${event.id}" class="btn-ticket">Get Tickets →</a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function filterEvents() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const date = document.getElementById('dateFilter').value;

        let filtered = [...currentEvents];

        if (searchTerm) {
            filtered = filtered.filter(event => 
                event.title.toLowerCase().includes(searchTerm) || 
                event.location.toLowerCase().includes(searchTerm)
            );
        }
        if (category) {
            filtered = filtered.filter(event => event.category === category);
        }
        if (date) {
            filtered = filtered.filter(event => event.date === date);
        }

        renderEvents(filtered);
    }

    function formatDate(dateStr) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateStr).toLocaleDateString(undefined, options);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Reset filters
    document.getElementById('resetBtn').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('dateFilter').value = '';
        renderEvents(currentEvents);
    });

    // Live filter on input change
    document.getElementById('searchInput').addEventListener('input', filterEvents);
    document.getElementById('categoryFilter').addEventListener('change', filterEvents);
    document.getElementById('dateFilter').addEventListener('change', filterEvents);

    // Handle ticket button clicks (event delegation)
    
    // Initialize page
    currentEvents = getAllEvents();
    renderEvents(currentEvents);
</script>
</body>
</html>