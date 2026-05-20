<?php

session_start();
require_once 'config.php';  // Your database connection file
$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $isLoggedIn ? $_SESSION['user_id'] : 0;
$user_name = $isLoggedIn ? ($_SESSION['fullname'] ?? '') : '';
$user_email = $isLoggedIn ? ($_SESSION['email'] ?? '') : '';
// Static events array (matches original design)
$events = [
    1001 => [
        'id' => 1001,
        'title' => "Grand Wedding Expo 2025",
        'category' => "Wedding",
        'date' => "2025-05-15",
        'location' => "Downtown Grand Hall, NYC",
        'price' => 49,
        'seats' => 200,
        'imageUrl' => "images/pic-2.jpg"
    ],
    1002 => [
        'id' => 1002,
        'title' => "Summer Beats Music Festival",
        'category' => "Concert",
        'date' => "2025-06-28",
        'location' => "Riverside Park, LA",
        'price' => 79,
        'seats' => 500,
        'imageUrl' => "images/pic-6.webp"
    ],
    1003 => [
        'id' => 1003,
        'title' => "Global Tech Summit 2025",
        'category' => "Seminar",
        'date' => "2025-07-10",
        'location' => "City Convention Center, SF",
        'price' => 149,
        'seats' => 300,
        'imageUrl' => "images/pic-8.jpg"
    ],
    1004 => [
        'id' => 1004,
        'title' => "Charity Sports Marathon",
        'category' => "Sports",
        'date' => "2025-08-20",
        'location' => "Central Park, NYC",
        'price' => 25,
        'seats' => 1000,
        'imageUrl' => "images/pic-9.jpg"
    ]
];

// Get event from URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = isset($events[$event_id]) ? $events[$event_id] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => ''];
  
    // Get POST data
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    // Validate
    if (!$event_id || !isset($events[$event_id])) {
        $response['message'] = 'Invalid event.';
        echo json_encode($response);
        exit;
    }
    
    $event = $events[$event_id];
    
    if (empty($fullname) || empty($email)) {
        $response['message'] = 'Name and email are required.';
        echo json_encode($response);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit;
    }
    
    if ($quantity < 1 || $quantity > $event['seats']) {
        $response['message'] = 'Invalid quantity or not enough seats available.';
        echo json_encode($response);
        exit;
    }
    
    // Calculate total
    $total = $event['price'] * $quantity;
    $ticket_id = 'TIX-' . time() . '-' . rand(1000, 9999);
   $user_id = $isLoggedIn ? $_SESSION['user_id'] : 0;
    
    // Insert into database using mysqli (from config.php)
    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (user_id, event_id, fullname, email, quantity, total, ticket_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iissids", $user_id, $event_id, $fullname, $email, $quantity, $total, $ticket_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
        $response['ticket'] = [
            'ticket_id' => $ticket_id,
            'event_title' => $event['title'],
            'event_date' => $event['date'],
            'event_location' => $event['location'],
            'attendee' => $fullname,
            'email' => $email,
            'quantity' => $quantity,
            'total' => $total,
            'price_per_ticket' => $event['price']
        ];
    } else {
        $response['message'] = 'Database error: ' . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    echo json_encode($response);
    exit;
}

// --- Page Display Logic ---
$isLoggedIn = isset($_SESSION['user_id']);
$user_name = $isLoggedIn ? ($_SESSION['fullname'] ?? '') : '';
$user_email = $isLoggedIn ? ($_SESSION['email'] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket | Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            padding: 40px 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 32px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .event-details {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .event-image {
            flex: 1;
            min-width: 200px;
        }
        .event-image img {
            width: 100%;
            border-radius: 24px;
            object-fit: cover;
        }
        .event-info {
            flex: 2;
        }
        .event-info h2 {
            margin-bottom: 1rem;
        }
        .info-row {
            margin: 0.5rem 0;
            color: #334155;
        }
        .info-row i {
            width: 28px;
            color: #5D3FD3;
        }
        .price-badge {
            font-size: 1.5rem;
            font-weight: 800;
            color: #5D3FD3;
            margin-top: 1rem;
        }
        .booking-form {
            border-top: 1px solid #e2e8f0;
            padding-top: 1.5rem;
            margin-top: 1rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.4rem;
        }
        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 60px;
            font-family: inherit;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .quantity-selector button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .quantity-selector span {
            font-size: 1.2rem;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
        }
        .total-price {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 1rem 0;
        }
        .btn-pay {
            background: #5D3FD3;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 60px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }
        .ticket-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 24px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        .qr-code {
            text-align: center;
            margin: 1rem 0;
        }
        .qr-code img {
            background: white;
            padding: 10px;
            border-radius: 16px;
        }
        .download-btn {
            background: white;
            color: #5D3FD3;
            border: none;
            padding: 10px 20px;
            border-radius: 60px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
        }
        .hidden {
            display: none;
        }
        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 20px;
            margin-bottom: 1rem;
        }
        .alert a {
            color: #5D3FD3;
            font-weight: 600;
        }
        @media (max-width: 640px) {
            .card {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card" id="bookingSection">
        <h1><i class="fas fa-ticket-alt"></i> Book Your Ticket</h1>
        <div id="eventDisplay">
            <?php if ($event): ?>
            <div class="event-details">
                <div class="event-image">
                    <img src="<?php echo htmlspecialchars($event['imageUrl']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" onerror="this.src='https://placehold.co/600x400/2C3E50/white?text=Event'">
                </div>
                <div class="event-info">
                    <h2><?php echo htmlspecialchars($event['title']); ?></h2>
                    <div class="info-row"><i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($event['date'])); ?></div>
                    <div class="info-row"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></div>
                    <div class="info-row"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($event['category']); ?></div>
                    <div class="price-badge">$<?php echo number_format($event['price'], 2); ?> per ticket</div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert">Event not found. Please go back to <a href="index.php">homepage</a>.</div>
            <?php endif; ?>
        </div>
        
        <?php if (!$event): ?>
            <!-- No event, stop rendering booking form -->
        
        <?php else: ?>
        <div id="bookingForm">
            <div class="booking-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="fullName" placeholder="Enter your name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" placeholder="your@email.com" value="<?php echo htmlspecialchars($user_email); ?>" required>
                </div>
                <div class="form-group">
                    <label>Number of Tickets</label>
                    <div class="quantity-selector">
                        <button id="decBtn">-</button>
                        <span id="quantity">1</span>
                        <button id="incBtn">+</button>
                    </div>
                </div>
                <div class="total-price">Total: $<span id="totalPrice">0.00</span></div>
                <button class="btn-pay" id="payBtn">Proceed to Payment <i class="fas fa-credit-card"></i></button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div id="ticketSection" class="card hidden">
        <h2><i class="fas fa-check-circle"></i> Payment Successful!</h2>
        <div id="ticketContent"></div>
        <button id="downloadPdfBtn" class="download-btn"><i class="fas fa-download"></i> Download PDF Ticket</button>
        <a href="index.php" class="btn-pay" style="display: inline-block; text-align: center; margin-top: 1rem; text-decoration: none;">← Back to Home</a>
    </div>
</div>

<script>
    const eventData = <?php echo json_encode($event); ?>;
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    let currentQuantity = 1;
    
    const quantitySpan = document.getElementById('quantity');
    const totalSpan = document.getElementById('totalPrice');
    const decBtn = document.getElementById('decBtn');
    const incBtn = document.getElementById('incBtn');
    const payBtn = document.getElementById('payBtn');
    const bookingSection = document.getElementById('bookingSection');
    const ticketSection = document.getElementById('ticketSection');
    
    function updateTotalDisplay() {
        if (eventData) {
            const total = eventData.price * currentQuantity;
            totalSpan.innerText = total.toFixed(2);
        }
    }
    
    if (decBtn && incBtn) {
        decBtn.addEventListener('click', () => {
            if (currentQuantity > 1) {
                currentQuantity--;
                quantitySpan.innerText = currentQuantity;
                updateTotalDisplay();
            }
        });
        incBtn.addEventListener('click', () => {
            if (eventData && currentQuantity < eventData.seats) {
                currentQuantity++;
                quantitySpan.innerText = currentQuantity;
                updateTotalDisplay();
            } else if (eventData && currentQuantity >= eventData.seats) {
                alert(`Only ${eventData.seats} seats available.`);
            }
        });
    }
    
    if (eventData) updateTotalDisplay();
    
    if (payBtn) {
        payBtn.addEventListener('click', async () => {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!fullName || !email) {
                alert('Please enter your name and email.');
                return;
            }
            if (!email.includes('@') || !email.includes('.')) {
                alert('Enter a valid email address.');
                return;
            }
            
            payBtn.disabled = true;
            payBtn.innerHTML = 'Processing... <i class="fas fa-spinner fa-pulse"></i>';
            
            try {
                const formData = new URLSearchParams();
                formData.append('event_id', eventData.id);
                formData.append('fullname', fullName);
                formData.append('email', email);
                formData.append('quantity', currentQuantity);
                
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData.toString()
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const ticket = result.ticket;
                    const qrData = JSON.stringify({
                        ticketId: ticket.ticket_id,
                        event: ticket.event_title,
                        attendee: ticket.attendee,
                        date: ticket.event_date,
                        quantity: ticket.quantity
                    });
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrData)}`;
                    
                    const ticketHTML = `
                        <div class="ticket-card" id="ticketToPrint">
                            <h3><i class="fas fa-ticket-alt"></i> Eventify Ticket</h3>
                            <p><strong>Event:</strong> ${escapeHtml(ticket.event_title)}</p>
                            <p><strong>Attendee:</strong> ${escapeHtml(ticket.attendee)}</p>
                            <p><strong>Email:</strong> ${escapeHtml(ticket.email)}</p>
                            <p><strong>Quantity:</strong> ${ticket.quantity}</p>
                            <p><strong>Total Paid:</strong> $${ticket.total.toFixed(2)}</p>
                            <p><strong>Date:</strong> ${new Date(ticket.event_date).toDateString()}</p>
                            <p><strong>Location:</strong> ${escapeHtml(ticket.event_location)}</p>
                            <p><strong>Ticket ID:</strong> ${escapeHtml(ticket.ticket_id)}</p>
                            <div class="qr-code"><img src="${qrUrl}" alt="QR Code"></div>
                            <p><small>Scan QR at venue entrance</small></p>
                        </div>
                    `;
                    document.getElementById('ticketContent').innerHTML = ticketHTML;
                    
                    // Optional: store in localStorage
                    const bookings = JSON.parse(localStorage.getItem('eventify_bookings') || '[]');
                    bookings.push({
                        ticketId: ticket.ticket_id,
                        eventId: eventData.id,
                        eventTitle: ticket.event_title,
                        attendee: ticket.attendee,
                        email: ticket.email,
                        quantity: ticket.quantity,
                        total: ticket.total,
                        purchaseDate: new Date().toLocaleString(),
                        qrData: qrData
                    });
                    localStorage.setItem('eventify_bookings', JSON.stringify(bookings));
                    
                    bookingSection.classList.add('hidden');
                    ticketSection.classList.remove('hidden');
                } else {
                    alert('Booking failed: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred. Please try again.');
            } finally {
                payBtn.disabled = false;
                payBtn.innerHTML = 'Proceed to Payment <i class="fas fa-credit-card"></i>';
            }
        });
    }
    
    document.getElementById('downloadPdfBtn')?.addEventListener('click', () => {
        const element = document.getElementById('ticketToPrint');
        if (element) {
            html2pdf().set({
                margin: [0.5, 0.5, 0.5, 0.5],
                filename: `ticket_${eventData ? eventData.title.replace(/\s/g, '_') : 'event'}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            }).from(element).save();
        }
    });
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
</script>
</body>
</html>
<?php mysqli_close($conn); ?>