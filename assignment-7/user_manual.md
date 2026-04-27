# GlamAura Wigs — User Manual
## Assignment 7: Integration, Testing, and Evaluation

---

## Quick Start

| Role | Start Here | Admin Credentials |
|------|-----------|-------------------|
| Customer | Open `Home.html` | N/A |
| Admin | Open `login.html` | admin@glamaura.com / admin123 |
| Developer | See Setup section below | N/A |

---

## PART A — Customer Guide

### 1. Browsing & Shopping
1. Open **Home.html** — you'll see the homepage with hero banner and featured wigs
2. Click **Shop** in the navbar to see all 24 wigs
3. Use **Search** to find wigs by name (e.g. "Body Wave", "Blonde")
4. Use **Filter** dropdown to browse by texture (Straight, Curly, Body Wave, Deep Wave, Kinky Curly, Accessories)
5. Use **Sort** to order by price (low→high or high→low) or name (A→Z)
6. Click **"Add to Cart"** on any wig — a pink notification confirms it was added

### 2. Managing Your Cart
1. Click the **🛒 cart icon** in the navbar to open `cart.html`
2. Change **hair length** (12" to 20") — prices update automatically based on multiplier:
   - 12" = base price | 14" = 1.2× | 16" = 1.4× | 18" = 1.7× | 20" = 2.0×
3. Use **+/-** buttons to adjust quantities
4. Click **Remove** to delete a single item
5. Click **Clear Cart** to remove all items
6. Click **"Proceed to Checkout"** when ready

### 3. Placing an Order (Checkout)
1. Fill in: Full Name, Email, Phone, Delivery Address, City
2. Select **Delivery Method**:
   - Standard (3–5 days) — GH₵ 100
   - Express (1–2 days) — GH₵ 200
   - Pickup (Accra only) — GH₵ 50
3. Select **Payment Method**:
   - 📱 MTN MoMo — enter mobile number and select network
   - 💳 Card — enter card number, expiry, CVV
   - 🏦 Bank Transfer — transfer to displayed account details
4. Review the **Order Summary** (right panel) — confirms subtotal, VAT (12.5%), delivery, and total
5. Click **"Place Order"** — wait 1–2 seconds for processing
6. You'll be redirected to the **confirmation page** with your Order ID (e.g. GA-1285)

### 4. Tracking Your Order
1. Note your **Order ID** from the confirmation page
2. Click **Track Order** in the navbar (or go to `track.html`)
3. Enter your Order ID and click **"Track Order"**
4. The 4-step tracker shows current status: Confirmed → Processing → Out for Delivery → Delivered

### 5. Creating an Account
1. Click **Login** in the navbar → open `login.html`
2. Click the **Sign Up** tab
3. Enter: Full Name, Email, Phone Number, Password (min 6 characters), Confirm Password
4. Click **"Create Account"**
5. You'll be redirected to your Customer Dashboard automatically

### 6. Logging In
1. Click **Login** in the navbar
2. Enter your registered email and password
3. Click **"Login"**
4. You'll be redirected to your Customer Dashboard

### 7. Customer Dashboard
- **Dashboard tab** — Overview KPIs: total orders, total spent, cart items
- **My Orders tab** — Full order history table with order IDs, items, total, and status
- **My Profile tab** — Edit name, phone, city, and delivery address; click "Save Changes"
- **Logout** — Click Logout in the sidebar to end your session

---

## PART B — Admin Guide

### Admin Login
- Go to `login.html`
- Email: `admin@glamaura.com`
- Password: `admin123`
- You'll be redirected to the Admin Dashboard

### Back-End System (Full Admin Dashboard)
Open `assignment-6/backend_system.html` for the complete back-end system.

**Left sidebar navigation:**

| Section | Module | What You Can Do |
|---------|--------|-----------------|
| Overview | Dashboard | View KPI summary, revenue chart, recent activity |
| CRM | Customer Database | Browse all customers with segmentation |
| CRM | Communications | View all customer interaction records |
| CRM | Feedback Management | View and resolve reviews/complaints |
| Finance | Financial Summary | Revenue/expense/profit KPIs and bar chart |
| Finance | Invoices & Payments | View invoices; click "+ Generate Invoice" |
| Ordering | Order Management | View all orders with statuses |
| Ordering | Inventory Management | View stock levels; red bars = low stock alert |
| Ordering | Delivery Management | Active deliveries and rider assignments |
| Supply Chain | Supply Chain Flow | End-to-end supply chain visual diagram |
| Supply Chain | Supplier Management | All suppliers with reliability scores |
| Supply Chain | Distribution Tracking | Inbound shipments and ETAs |

---

## PART C — Developer Setup (XAMPP + MySQL)

### Requirements
- XAMPP (Apache + MySQL) — download from apachefriends.org
- Web browser (Chrome or Firefox recommended)

### Step-by-Step Setup
1. **Install XAMPP** and start both **Apache** and **MySQL** services
2. **Copy project** — paste the `GlamAuraWigs` folder into `C:/xampp/htdocs/`
3. **Create database** — open phpMyAdmin (`http://localhost/phpmyadmin`), create database named `glamaura`
4. **Run SQL schema** — open the SQL tab in phpMyAdmin, paste contents of `sql.txt`, click Go
5. **Verify db.php** — open `db.php` and confirm: host=`localhost`, user=`root`, password=`""`, db=`glamaura`
6. **Access the site** — open `http://localhost/GlamAuraWigs/Home.html`

### File Reference

| File | Purpose |
|------|---------|
| Home.html | Homepage |
| Shop.html | Product catalog |
| Collection.html | Collections browser |
| cart.html | Shopping cart |
| checkout.html | Checkout page |
| confirmation.html | Order confirmation |
| customer_dashboard.html | Customer account dashboard |
| track.html | Order tracking |
| login.html | Login / Sign Up |
| About.html | About GlamAura |
| Contact.html | Contact & support |
| admin_dashboard.html | Admin dashboard (HTML version) |
| assignment-6/backend_system.html | Full back-end system dashboard |
| GlamAura_Complete.html | All-in-one integrated version |
| db.php | MySQL connection config |
| login.php | Server-side login processing |
| signup.php | Server-side user registration |
| logout.php | Session destruction |
| fetch_products.php | Returns products as JSON from DB |
| place_order.php | Inserts order into DB |
| checkout.php | Processes checkout form |
| get_order.php | Retrieves order by ID |
| sql.txt | MySQL schema (run in phpMyAdmin) |
| shared.css | Shared design system stylesheet |
| navbar.html/css/js | Shared navigation component |
| images/ | All 27 product images |

---

*GlamAura Wigs — Bachelor of ICT | E-Business Course | Assignment 7*
