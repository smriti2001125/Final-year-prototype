# Lost & Found System - Setup Instructions

**Final Year Project by Januka Khadka | P2837476**
**Niels Brock - Bachelor's in Computer Science**

---

## ⚡ Quick Setup (5 steps)

### Step 1 — Copy to XAMPP
Copy the entire `lost_and_found` folder to:
```
C:\xampp\htdocs\lost_and_found\
```

### Step 2 — Start XAMPP
Open XAMPP Control Panel and start:
- ✅ Apache
- ✅ MySQL

### Step 3 — Create Database
1. Open browser → go to: http://localhost/phpmyadmin
2. Click **"New"** on the left sidebar
3. Name it: `lost_found_db` → Click **Create**
4. Click the **SQL** tab
5. Open `database_setup.sql` from this folder, copy all contents, paste & click **Go**

### Step 4 — Open the Website
Go to: **http://localhost/lost_and_found/**

### Step 5 — Login as Admin
- **Email:** admin@lostandfound.com
- **Password:** password

---

## 📁 Project Structure
```
lost_and_found/
├── index.php              ← Home page
├── report_lost.php        ← Report a lost item
├── report_found.php       ← Report a found item
├── search.php             ← Search & filter items
├── login.php              ← User login
├── register.php           ← User registration
├── logout.php             ← Logout
├── database_setup.sql     ← Run this in phpMyAdmin
├── admin/
│   ├── dashboard.php      ← Admin overview
│   ├── manage_items.php   ← View/edit/delete items
│   └── manage_users.php   ← View/delete users
├── includes/
│   ├── db.php             ← Database connection
│   ├── header.php         ← Navbar + HTML head
│   └── footer.php         ← Footer + scripts
├── css/
│   └── style.css          ← All styling
├── js/
│   └── script.js          ← JavaScript
└── uploads/               ← Item images stored here
```

---

## 🔑 Demo Accounts
All demo accounts use password: **password**

| Name  | Email                       | Role  |
|-------|-----------------------------|-------|
| Admin | admin@lostandfound.com      | Admin |
| Alice | alice@example.com           | User  |
| Bob   | bob@example.com             | User  |

---

## 🤖 How the Auto-Matching Works
When a new lost or found item is submitted, the system compares it against all open items of the opposite type using a **rule-based scoring system**:

| Rule                          | Points |
|-------------------------------|--------|
| Category match                | +40    |
| Exact location match          | +30    |
| Partial location match        | +15    |
| Date within 7 days            | +20    |
| Date within 30 days           | +10    |
| Keyword overlap (per word)    | +5 each (max 20) |

- Score **≥ 40** → Match recorded in database
- Score **≥ 70** → Both items automatically marked as "matched"

---

## 🛠 Technologies Used
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 8+
- **Database:** MySQL (via XAMPP)
- **Dev Tools:** VS Code, XAMPP, phpMyAdmin
