// script.js - Eventify Smart Event Platform (Section 1 Interactions)



document.addEventListener('DOMContentLoaded', function() {
    // ---------- MOBILE MENU TOGGLE ----------
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');

    if (mobileBtn && mainNav) {
        mobileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mainNav.classList.toggle('active');
            // Change icon (optional)
            const icon = mobileBtn.querySelector('i');
            if (mainNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking outside (optional)
        document.addEventListener('click', function(event) {
            if (!mainNav.contains(event.target) && !mobileBtn.contains(event.target) && mainNav.classList.contains('active')) {
                mainNav.classList.remove('active');
                const icon = mobileBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }


// Replace the old marketplace click handler with this:
if (marketplaceLink) {
    marketplaceLink.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('marketplace').scrollIntoView({ behavior: 'smooth' });
    });
}
    window.addEventListener('scroll', () => {
    const eventsSection = document.getElementById('featured-events');
    const eventsLink = document.getElementById('navEvents');
    const homeLink = document.getElementById('navHome');
    if (eventsSection && eventsLink) {
        const rect = eventsSection.getBoundingClientRect();
        if (rect.top <= 100 && rect.bottom >= 100) {
            eventsLink.classList.add('active');
            homeLink.classList.remove('active');
        } else if (window.scrollY < 100) {
            homeLink.classList.add('active');
            eventsLink.classList.remove('active');
        }
    }
});
    // ---------- AUTH BUTTONS (Demo alerts) ----------

    const registerBtn = document.getElementById('registerBtn');
    const adminBtn = document.getElementById('adminBtn');

   
    if (adminBtn) {
        adminBtn.addEventListener('click', () => {
            alert('👑 Admin Panel — Manage users, approve events & view analytics.\n(Demo mode: full panel in later section)');
        });
    }

    // ---------- HERO BUTTONS ----------
    const createEventBtn = document.querySelector('.hero-buttons .btn-primary');
    const exploreEventsBtn = document.querySelector('.hero-buttons .btn-outline-dark');


    // ---------- EVENT CARD "GET TICKETS" BUTTONS ----------
    const ticketButtons = document.querySelectorAll('.event-footer .btn-sm');
   

    // ---------- CATEGORY CARDS (demo navigation) ----------
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('click', () => {
            const categoryName = card.querySelector('h3')?.innerText || 'category';
            alert(`🏷️ Showing events in "${categoryName}" category.\n(Filter functionality ready in upcoming iteration.)`);
        });
        card.style.cursor = 'pointer';
    });

    // ---------- SMOOTH SCROLL FOR INTERNAL ANCHORS (if any) ----------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#' && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Optional: console log that script is ready
    console.log('Eventify Platform — Section 1 UI ready (static demo)');
});

// Check if user is logged in (from login.html)
const storedUser = localStorage.getItem('eventify_user');
if (storedUser) {
    const user = JSON.parse(storedUser);
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.innerHTML = `<i class="fas fa-user-circle"></i> ${user.name} (${user.role})`;
        loginBtn.href = "#";
        loginBtn.classList.add("logged-in");
        // Optional logout on click
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if(confirm('Logout?')) {
                localStorage.removeItem('eventify_user');
                location.reload();
            }
        });
    }
}


// Check if user is logged in (from register or login)
const loggedUser = localStorage.getItem('eventify_user');
if (loggedUser) {
    const user = JSON.parse(loggedUser);
    const registerBtn = document.getElementById('registerBtn');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginBtn) {
        loginBtn.innerHTML = `<i class="fas fa-user-circle"></i> ${user.name}`;
        loginBtn.href = "#";
        loginBtn.classList.add("logged-in");
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if(confirm('Logout?')) {
                localStorage.removeItem('eventify_user');
                location.reload();
            }
        });
    }
    if (registerBtn) {
        registerBtn.style.display = 'none'; // hide register button after login
    }
}


// Create Event button logic
const createEventBtn = document.getElementById('createEventBtn');
if (createEventBtn) {
    createEventBtn.addEventListener('click', () => {
        const loggedUser = localStorage.getItem('eventify_user');
        if (!loggedUser) {
            alert('⚠️ Please login or register to create an event.');
            window.location.href = 'login.html';
        } else {
            window.location.href = 'create-event.html';
        }
    });
}





// Load and display events (static + user-created)
function loadAllEvents() {
    const eventsGrid = document.getElementById('eventsGrid');
    if (!eventsGrid) return;

    // Clear only if we want to rebuild, but keep static HTML as backup?
    // Better to append user events after static ones or merge.
    
    // Get user-created events from localStorage
    const userEvents = JSON.parse(localStorage.getItem('eventify_events') || '[]');
    
    if (userEvents.length > 0) {
        // Add a heading for user events
        const userEventsHeading = document.createElement('div');
        userEventsHeading.className = 'section-header';
        userEventsHeading.style.marginTop = '3rem';
        userEventsHeading.innerHTML = `
            <div>
                <span class="section-tag">🎉 Your Creations</span>
                <h2>Events You've Created</h2>
            </div>
        `;
        
        // Insert heading after the existing events grid
        eventsGrid.parentNode.insertBefore(userEventsHeading, eventsGrid.nextSibling);
        
        // Create a new grid for user events
        const userGrid = document.createElement('div');
        userGrid.className = 'events-grid';
        userGrid.id = 'userEventsGrid';
        
        // Append after heading
        userEventsHeading.insertAdjacentElement('afterend', userGrid);
        
        // Populate user events
        userEvents.slice(-3).reverse().forEach(event => {
            const card = createEventCard(event);
            userGrid.appendChild(card);
        });
    }
}

function createEventCard(event) {
    const div = document.createElement('div');
    div.className = 'event-card';
    div.innerHTML = `
        <div class="event-img">
            <img src="${event.imageUrl}" alt="${event.title}" onerror="this.src='https://placehold.co/400x240/2C3E50/white?text=Event+Image'">
            <span class="event-badge">${event.category}</span>
        </div>
        <div class="event-info">
            <div class="event-category"><i class="fas fa-tag"></i> ${event.category}</div>
            <h3>${escapeHtml(event.title)}</h3>
            <div class="event-meta">
                <span><i class="fas fa-calendar-day"></i> ${new Date(event.date).toDateString()}</span>
                <span><i class="fas fa-map-marker-alt"></i> ${escapeHtml(event.location)}</span>
            </div>
            <div class="event-footer">
                <div class="price">Starting at <strong>$${event.price}</strong></div>
                <button class="btn-sm btn-primary" data-event-id="${event.id}">Get Tickets →</button>
            </div>
        </div>
    `;
    return div;
}

// Helper to prevent XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Call on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAllEvents();
    
    // Also handle ticket buttons for dynamic events (event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-sm') && e.target.closest('.event-card')) {
            const eventId = e.target.getAttribute('data-event-id');
            
        }
    });
});



document.querySelectorAll('.event-card .btn-sm').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        if (href) {
            window.location.href = href;
        } else {
            alert('Booking page not ready yet.');
        }
    });
});


// Contact form submission
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('contactName').value.trim();
        const email = document.getElementById('contactEmail').value.trim();
        const subject = document.getElementById('contactSubject').value.trim();
        const message = document.getElementById('contactMessage').value.trim();

        if (!name || !email || !subject || !message) {
            alert('Please fill all fields.');
            return;
        }
        if (!email.includes('@')) {
            alert('Enter a valid email address.');
            return;
        }

        // Store contact message in localStorage (optional)
        const contacts = JSON.parse(localStorage.getItem('eventify_contacts') || '[]');
        contacts.push({
            id: Date.now(),
            name: name,
            email: email,
            subject: subject,
            message: message,
            date: new Date().toISOString()
        });
        localStorage.setItem('eventify_contacts', JSON.stringify(contacts));

        alert('✅ Thank you! Your message has been sent. We\'ll get back to you soon.');
        contactForm.reset();
    });
}

// Footer newsletter form
const newsletterForm = document.getElementById('footerNewsletterForm');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = newsletterForm.querySelector('input[type="email"]').value.trim();
        if (email && email.includes('@')) {
            alert(`✅ Subscribed! You'll receive updates at ${email}`);
            newsletterForm.reset();
        } else {
            alert('Please enter a valid email address.');
        }
    });
}



document.querySelectorAll(".user-name").forEach(el => {
    el.addEventListener("click", () => {
        let menu = el.nextElementSibling;
        if(menu.style.display === "block"){
            menu.style.display = "none";
        } else {
            menu.style.display = "block";
        }
    });
});

// click outside close
document.addEventListener("click", function(e){
    document.querySelectorAll(".dropdown-menu").forEach(menu => {
        if(!menu.parentElement.contains(e.target)){
            menu.style.display = "none";
        }
    });
});
