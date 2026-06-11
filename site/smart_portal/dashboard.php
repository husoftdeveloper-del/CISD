<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

/* -------- DASHBOARD QUERIES -------- */

// TOTAL STUDENTS
$students = $conn->query(
    "SELECT COUNT(*) AS total FROM admissions"
)->fetch_assoc()['total'] ?? 0;

// TOTAL PAID AMOUNT (FROM FEE RECEIPTS)
$paid = $conn->query(
    "SELECT SUM(received_amount) AS total_paid FROM fee_receipts_v2"
)->fetch_assoc()['total_paid'] ?? 0;

// ✅ TOTAL REMAINING FEE (from fee_receipts_v2)

// ✅ TOTAL REMAINING FEE (fee_receipts_v2)
$remainingResult = $conn->query("
    SELECT SUM(f.remaining_amount) AS total_remaining
    FROM fee_receipts_v2 f
    INNER JOIN (
        SELECT admission_id, MAX(id) AS max_id
        FROM fee_receipts_v2
        GROUP BY admission_id
    ) x ON f.id = x.max_id
    WHERE f.remaining_amount > 0
");

$totalRemaining = $remainingResult->fetch_assoc()['total_remaining'] ?? 0;


// TOTAL EXPENDITURE
$expenditure = $conn->query(
    "SELECT SUM(amount) AS total_expense FROM expenditures"
)->fetch_assoc()['total_expense'] ?? 0;

// ✅ TOTAL CEO CASH
$ceoCash = $conn->query(
    "SELECT SUM(amount) AS total_ceo_cash FROM ceo_cash"
)->fetch_assoc()['total_ceo_cash'] ?? 0;

// ✅ FINAL CASH IN HAND (CORRECT FORMULA)
$cashInHand = $paid + $ceoCash - $expenditure;

// ✅ CHECK IF CASH IS NEGATIVE
$isCashNegative = ($cashInHand < 0);



// TOTAL COURSES
$courses = $conn->query(
    "SELECT COUNT(DISTINCT course) AS total FROM admissions"
)->fetch_assoc()['total'] ?? 0;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">
    <title>CISD ACADEMY Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            display: flex;
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {

            width: 240px;
            background: linear-gradient(180deg, #2c3e50, #34495e);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 30px;
        }

       /* ===== LOGO WRAPPER ===== */
/* ===== LOGO WRAPPER ===== */
.logo-wrap{
    width: 150px;
    height: 150px;
    margin: 0 auto 25px;
    border-radius: 50%;
    padding: 8px;
    background: linear-gradient(135deg,#1abc9c,#3498db,#9b59b6);
    box-shadow: 0 0 30px rgba(26,188,156,0.7);
    animation: logoGlow 6s infinite alternate;
}

/* ===== LOGO WRAPPER ===== */
.logo-wrap{
    width: 150px;
    height: 150px;
    margin: 0 auto 25px;
    border-radius: 50%;
    padding: 8px;
    background: linear-gradient(135deg,#1abc9c,#3498db,#9b59b6);
    box-shadow: 0 0 30px rgba(26,188,156,0.7);
    animation: logoGlow 6s infinite alternate;
}

/* Logo container */
.logo-wrap{
    width:130px;
    height:130px;
    margin:20px auto 15px;
    border-radius:50%;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 0 20px rgba(26,188,156,0.6);
    overflow:hidden;   /* IMPORTANT */
}

/* Logo image */
.logo-wrap img{
    width:85%;
    height:85%;
    object-fit:contain; /* NO STRETCH */
    border-radius:50%;
}

/* Logo container */
.logo-wrap{
    width:130px;
    height:130px;
    margin:20px auto 15px;
    border-radius:50%;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 0 20px rgba(26,188,156,0.6);
    overflow:hidden;   /* IMPORTANT */
}

/* Logo image */
.logo-wrap img{
    width:85%;
    height:85%;
    object-fit:contain; /* NO STRETCH */
    border-radius:50%;
}
/* ===== Logo Outer Wrapper ===== */
.logo-wrap{
    width:140px;
    height:140px;
    margin:25px auto 20px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
}

/* ===== Animated Gradient Ring ===== */
.logo-wrap::before{
    content:"";
    position:absolute;
    inset:-6px;
    border-radius:50%;
    background:linear-gradient(
        60deg,
        #1abc9c,
        #3498db,
        #9b59b6,
        #f39c12,
        #1abc9c
    );
    background-size:400% 400%;
    animation:ringRotate 6s linear infinite;
    filter:blur(1px);
}

/* ===== Inner White Circle ===== */
.logo-wrap::after{
    content:"";
    position:absolute;
    inset:6px;
    border-radius:50%;
    background:#fff;
    z-index:1;
}

/* ===== Logo Image ===== */
.logo-wrap img{
    width:75%;
    height:75%;
    object-fit:contain;
    border-radius:50%;
    position:relative;
    z-index:2;
}

/* ===== Animation ===== */
@keyframes ringRotate{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}


        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .academy-title {
            position: relative;
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.4s ease-in-out;
            animation: glowFade 4s infinite alternate;
        }

        .academy-title::after {
            content: '';
            display: block;
            margin: 6px auto 0;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #1abc9c, #3498db, #9b59b6);
            border-radius: 4px;
            animation: underlineMove 3s infinite linear;
        }

        @keyframes glowFade {
            0% {
                text-shadow: 0 0 5px #1abc9c, 0 0 10px #3498db;
            }

            100% {
                text-shadow: 0 0 10px #9b59b6, 0 0 20px #1abc9c;
            }
        }

        @keyframes underlineMove {
            0% {
                transform: translateX(0%);
            }

            50% {
                transform: translateX(10%);
            }

            100% {
                transform: translateX(0%);
            }
        }

        .academy-title {
            position: relative;
            font-size: 26px;
            color: #ffffff;
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
            text-shadow: 0 0 8px #00f7ff, 0 0 15px #00c3ff;
            animation: glowText 2s ease-in-out infinite alternate;
        }

        /* Animated underline */
        .academy-title::after {
            content: '';
            display: block;
            margin: 8px auto 0;
            width: 70%;
            height: 3px;
            background: linear-gradient(to right, #00c3ff, #1abc9c, #9b59b6);
            border-radius: 5px;
            animation: underlinePulse 3s infinite ease-in-out;
        }

        /* Glowing text animation */
        @keyframes glowText {
            0% {
                text-shadow: 0 0 5px #00f7ff, 0 0 10px #00c3ff;
            }

            100% {
                text-shadow: 0 0 15px #1abc9c, 0 0 25px #9b59b6;
            }
        }

        /* Underline breathing effect */
        @keyframes underlinePulse {

            0%,
            100% {
                transform: scaleX(1);
            }

            50% {
                transform: scaleX(1.2);
            }
        }


        .sidebar a {
            display: block;
            padding: 16px 28px;
            color: #ecf0f1;
            font-size: 17px;
            /* 👈 Increase font size */
            font-weight: 600;
            /* 👈 Make it bolder */
            transition: background 0.3s, transform 0.2s;
        }

        .sidebar a:hover {
            background: #1abc9c;
            transform: translateX(5px);
            /* 👈 Small move on hover */
        }


        .sidebar a:hover {
            background: #1abc9c;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(-45deg, #3498db, #2ecc71, #1abc9c, #9b59b6);
            background-size: 400% 400%;
            animation: gradMove 10s ease infinite;
            color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
        }

        .header p {
            margin-top: 8px;
            font-size: 1rem;
        }

        /* Course Cards */
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(50% - 20px);
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform .3s, box-shadow .3s;
        }

        /* ===== Animated Gradient Outline for .card ===== */
        .card {
            position: relative;
            /* required for pseudo-elements */
            z-index: 1;
            overflow: hidden;
        }

        /* The animated gradient border */
        .card::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(60deg,
                    rgba(26, 188, 156, 0.6),
                    rgba(52, 152, 219, 0.6),
                    rgba(155, 89, 182, 0.6),
                    rgba(231, 76, 60, 0.6));
            background-size: 400% 400%;
            border-radius: 14px;
            /* match your .card border-radius + 2px */
            z-index: -1;
            animation: borderShift 6s ease infinite;
        }

        /* Inner white “mask” so your card’s content stays crisp */
        .card::after {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            background: #fff;
            border-radius: 12px;
            /* match your .card border-radius */
            z-index: -1;
        }

        /* Animate the gradient’s position */
        @keyframes borderShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }


        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .card h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p a {
            color: #1abc9c;
            font-weight: 600;
        }

        /* Summary Cards */
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 40px 0;
        }

        /* ===== Animated Gradient Outline for .summary-card ===== */
        .summary-card {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Outer animated gradient border */
        .summary-card::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(60deg,
                    rgba(26, 188, 156, 0.6),
                    rgba(52, 152, 219, 0.6),
                    rgba(155, 89, 182, 0.6),
                    rgba(231, 76, 60, 0.6));
            background-size: 400% 400%;
            border-radius: 16px;
            /* slightly larger than card */
            z-index: -1;
            animation: borderShiftSummary 6s ease infinite;
        }

        /* Inner white background mask */
        .summary-card::after {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            background: #ffffff;
            border-radius: 14px;
            z-index: -1;
        }

        /* Reuse or redefine the keyframes */
        @keyframes borderShiftSummary {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }


        .summary-card {
            background: #fff;
            padding: 20px 25px;
            width: 200px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform .3s, box-shadow .3s;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .summary-title {
            font-size: .9rem;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #2c3e50;
        }

        /* Footer */
        .footer {
            width: 100%;
            text-align: center;
            padding: 15px 0;
            background: linear-gradient(270deg, #1abc9c, #2ecc71, #3498db, #9b59b6);
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            animation: gradMove 10s ease infinite;
        }

        /* Animations */
        @keyframes gradMove {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        /* Responsive */
        @media(max-width:768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .cards,
            .summary-cards {
                flex-direction: column;
                align-items: center;
            }

            .card,
            .summary-card {
                flex: 1 1 100%;
                max-width: 320px;
            }


            .sidebar {
                display: none;
            }
        }

        .sidebar .logo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 30px;
            object-fit: cover;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 25px rgba(26, 188, 156, 0.8),
                0 0 35px rgba(52, 152, 219, 0.5);
        }

        /* Animated border using pseudo-element */
        .sidebar .logo::before {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 50%;
            background: linear-gradient(60deg, #1abc9c, #3498db, #9b59b6, #f39c12);
            background-size: 400% 400%;
            animation: animateLogoBorder 6s ease infinite;
            z-index: -1;
            filter: blur(5px);
        }

        /* Animated border movement */
        @keyframes animateLogoBorder {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body.dark {
            background: #1e1e2f !important;
            color: #f1f1f1;
        }

        body.dark .card,
        body.dark .summary-card,
        body.dark .main-content,
        body.dark .header,
        body.dark .footer {
            background-color: #2e2e3e !important;
            color: #fff !important;
        }

        body.dark .sidebar {
            background: #181818 !important;
        }

        body.dark .sidebar a {
            color: #eee;
        }

        body.dark .sidebar a:hover {
            background: #333;
        }
        /* Dropdown container */
.dropdown {
    width: 100%;
}

/* Main dropdown button */
.dropdown-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 28px;
    color: #ecf0f1;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.dropdown-btn:hover {
    background: #1abc9c;
}

/* Arrow */
.dropdown-btn .arrow {
    transition: transform 0.3s ease;
}

/* Dropdown content */
.dropdown-content {
    display: none;
    background: #34495e;
}

.dropdown-content a {
    padding: 14px 45px;
    font-size: 15px;
    display: block;
    color: #ecf0f1;
}

.dropdown-content a:hover {
    background: #16a085;
}

/* Active state */
.dropdown.active .dropdown-content {
    display: block;
}

.dropdown.active .arrow {
    transform: rotate(180deg);
}
.nav-item {
    list-style: none;
}

.nav-link {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    color: #fff;
    cursor: pointer;
}

.dropdown-menu {
    display: none;
    background: #1f2937;
    padding-left: 15px;
}

.dropdown-menu li a {
    display: block;
    padding: 10px;
    color: #cbd5e1;
    text-decoration: none;
}

.dropdown-menu li a:hover {
    background: #374151;
    border-radius: 6px;
}
.sidebar{
    overflow-y: auto;
}

.sidebar::after{
    content:"";
    display:block;
    margin:20px auto;
    width:70%;
    height:1px;
    background:linear-gradient(to right,transparent,#1abc9c,transparent);
}
.sidebar::-webkit-scrollbar{
    width:6px;
}
.sidebar::-webkit-scrollbar-thumb{
    background:#1abc9c;
    border-radius:10px;
}
/* ===== SIDEBAR ===== */
.sidebar{
    width:260px;
    height:100vh;
    background:linear-gradient(180deg,#0f2027,#203a43,#2c5364);
    padding-top:20px;
    position:fixed;
    overflow-y:auto;
}

/* Logo */
.logo-wrap{
    text-align:center;
    margin-bottom:10px;
}
.logo-wrap img{
    width:90px;
    border-radius:12px;
}

/* Title */
.academy-title{
    color:#fff;
    text-align:center;
    font-size:18px;
    margin-bottom:25px;
    letter-spacing:1px;
}

/* ===== LINKS ===== */
.menu-link{
    display:flex;
    align-items:center;
    gap:14px;
    padding:14px 18px;
    margin:8px 14px;
    border-radius:14px;
    color:#ecf0f1;
    font-size:15px;
    font-weight:500;
    text-decoration:none;
    cursor:pointer;
    transition:0.35s;
    background:rgba(255,255,255,0.05);
}

.menu-link i{
    font-size:17px;
    color:#1abc9c;
}

.menu-link:hover{
    background:linear-gradient(135deg,#1abc9c,#3498db);
    transform:translateX(6px);
    box-shadow:0 10px 30px rgba(26,188,156,0.45);
}

.menu-link:hover i,
.menu-link:hover span{
    color:#fff;
}

.menu-link.active{
    background:linear-gradient(135deg,#16a085,#2980b9);
}

/* Logout */
.menu-link.logout{
    background:rgba(231,76,60,0.15);
}
.menu-link.logout:hover{
    background:linear-gradient(135deg,#e74c3c,#c0392b);
}

/* ===== DROPDOWN ===== */
.menu-dropdown .dropdown-btn{
    justify-content:space-between;
}

.arrow{
    margin-left:auto;
    transition:0.3s;
}

.menu-dropdown.active .arrow{
    transform:rotate(180deg);
}

.dropdown-content{
    display:none;
    margin:5px 30px 10px;
}

.dropdown-content a{
    display:block;
    padding:10px 14px;
    border-radius:10px;
    color:#ecf0f1;
    text-decoration:none;
    font-size:14px;
    margin-bottom:6px;
    background:rgba(255,255,255,0.05);
    transition:0.3s;
}

.dropdown-content a:hover{
    background:linear-gradient(135deg,#3498db,#1abc9c);
    transform:translateX(6px);
}

.submenu{
  display:none;
  padding-left:18px;
}
.submenu li a{
  display:block;
  padding:6px 0;
  color:#2c3e50;
  text-decoration:none;
  font-weight:500;
}
.submenu li a:hover{
  color:#1abc9c;
}
.tagline{
    margin-top:10px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:14px;
    font-size:18px;
    font-weight:700;
    letter-spacing:.6px;
    color:#ffffff;
}

.tagline span{
    opacity:0;
    animation:fadeWords 6s infinite;
}

.tagline span:nth-child(1){animation-delay:0s}
.tagline span:nth-child(2){animation-delay:.4s}
.tagline span:nth-child(3){animation-delay:.8s}
.tagline span:nth-child(4){animation-delay:1.2s}
.tagline span:nth-child(5){animation-delay:1.6s}

.tagline .dot{
    color:#ffeaa7;
    font-size:22px;
}

@keyframes fadeWords{
    0%{opacity:0; transform:translateY(10px)}
    15%{opacity:1; transform:translateY(0)}
    75%{opacity:1}
    100%{opacity:0}
}
.submenu {
    display: none;
    padding-left: 15px;
}

.menu-item:hover .submenu {
    display: block;
}

.submenu a {
    display: block;
    padding: 8px 12px;
    color: #eee;
    font-size: 14px;
}


/* Slide animation */
@keyframes slideDown{
    from{opacity:0; transform:translateY(-10px)}
    to{opacity:1; transform:translateY(0)}
}

/* Active open */
.menu-dropdown.active .ceo-content{
    display:block;
}
.menu-dropdown.active .arrow{
    transform:rotate(180deg);
}




    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


  <div class="sidebar">

    <div class="logo-wrap" >
        <img src="logo.png" alt="CISD Logo">
    </div>

    <h2 class="academy-title">CISD CHD</h2>

    <!-- MAIN LINKS -->
    <a href="dashboard.php" class="menu-link active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>

    <a href="admission.php" class="menu-link">
        <i class="fas fa-user-plus"></i>
        <span>New Admission</span>
    </a>

    <a href="admission_list.php" class="menu-link">
        <i class="fas fa-list"></i>
        <span>Admission List</span>
    </a>

    <!-- FEE RECEIPT DROPDOWN -->
    <div class="menu-dropdown">
        <div class="menu-link dropdown-btn">
            <i class="fas fa-money-bill-wave"></i>
            <span>Fee Receipt</span>
            <i class="fas fa-chevron-down arrow"></i>
        </div>

        <div class="dropdown-content">
            <a href="fee_receipt.php">➕ Add Fee Receipt</a>
            <a href="fee_receipt_list.php">📄 Receipt List</a>
        </div>
    </div>

    <!-- EXPENDITURE DROPDOWN -->
    <div class="menu-dropdown">
        <div class="menu-link dropdown-btn">
            <i class="fas fa-wallet"></i>
            <span>Expenditure</span>
            <i class="fas fa-chevron-down arrow"></i>
        </div>

        <div class="dropdown-content">
            <a href="expenditure.php">➕ Add Expenditure</a>
            <a href="expenditure_list.php">📋 Expenditure List</a>
        </div>
    </div>

    <a href="monthly_income.php" class="menu-link">
        <i class="fas fa-chart-line"></i>
        <span>Monthly Income</span>
    </a>
   <!-- TEACHER SALARY DROPDOWN -->
<!-- TEACHERS DROPDOWN (ALL IN ONE) -->
<div class="menu-dropdown">
    <div class="menu-link dropdown-btn">
        <i class="fas fa-user-tie"></i>
        <span>Teachers</span>
        <i class="fas fa-chevron-down arrow"></i>
    </div>

    <div class="dropdown-content">
        <!-- Teacher Master -->
        <a href="add_teacher.php">➕ Add Teacher</a>
        <a href="teacher_list.php">📋 Teacher List</a>

        <hr style="border:0;height:1px;background:rgba(255,255,255,0.15);margin:8px 0;">

        <!-- Teacher Salary -->
        <a href="add_teacher_salary.php">💰 Add Salary</a>
        <a href="teacher_salary_monthly.php">📅 Monthly Salary Sheet</a>
        <a href="teacher_salary_list.php">📄 Salary Records</a>
    </div>
</div>
<div class="menu-dropdown ceo-dropdown">
    <div class="menu-link dropdown-btn">
        <i class="fas fa-briefcase"></i>
        <span>CEO Cash</span>
        <i class="fas fa-chevron-down arrow"></i>
    </div>

    <div class="dropdown-content ceo-content">
        <a href="ceo_cash_add.php">
            <i class="fas fa-plus-circle"></i> Add CEO Cash
        </a>
        <a href="ceo_cash_list.php">
            <i class="fas fa-list-alt"></i> Cash History
        </a>
    </div>
</div>




    <a href="../admin/applications.php" class="menu-link">
        <i class="fas fa-file-alt"></i>
        <span>Online Applications</span>
    </a>

    <a href="../admin/dashboard.php" class="menu-link">
        <i class="fas fa-globe"></i>
        <span>Website Admin</span>
    </a>

    <a href="settings.php" class="menu-link">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>

    <a href="logout.php" class="menu-link logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>

</div>


    <div class="main-content">
        <div class="header">
            <h1>CISD  CHARSADDA</h1>
       <div class="tagline">
    <span>Track Admissions</span>
    <span class="dot">•</span>
    <span>Manage Fees</span>
    <span class="dot">•</span>
    <span>Run Your Academy Smarter</span>
</div>

        </div>
        

        <div class="cards">
            <div class="card">
                <h3><i class="fas fa-file-alt"></i> MS Office</h3>
                <p><a href="ms_office_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3><i class="fas fa-keyboard"></i> Typing Course</h3>
                <p><a href="typing_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3><i class="fas fa-language"></i> English Language</h3>
                <p><a href="english_language_course.php">View Details</a></p>
            </div>

            <div class="card">
                <h3><i class="fas fa-pen-nib"></i> Short Hand</h3>
                <p><a href="short_hand_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3><i class="fas fa-graduation-cap"></i> DIT</h3>
                <p><a href="dit_course.php">View Details</a></p>
            </div>

            <!-- ✅ New Courses -->
            <div class="card">
                <h3>💻 WEB DEVELOPMENT</h3>
                <p><a href="web_development_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>📱 APP DEVELOPMENT</h3>
                <p><a href="app_development_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>🤖 AI &amp; PYTHON</h3>
                <p><a href="ai_python_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>🎨 GRAPHIC DESIGNING</h3>
                <p><a href="graphic_designing_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>▶️ YOUTUBE AUTOMATION</h3>
                <p><a href="youtube_automation_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>📣 DIGITAL MARKETING</h3>
                <p><a href="digital_marketing_course.html">View Details</a></p>
            </div>

            <div class="card">
                <h3>🧠 BASIC COMPUTER SKILLS</h3>
                <p><a href="basic_computer_skills_course.html">View Details</a></p>
            </div>

        </div>


        <div class="summary-cards">
            <div class="summary-card" onclick="window.location.href='students_list.php'" style="cursor:pointer;">
                <div class="summary-title">Total Students</div>
                <div class="summary-value"><?= $students ?></div>
            </div>

            <div class="summary-card" onclick="window.location.href='fee_receipt_list.php'" style="cursor: pointer;">
                <div class="summary-title">Total Paid</div>
                <div class="summary-value">Rs. <?= number_format($paid) ?></div>
            </div>
            <div class="summary-card"
     onclick="window.location.href='remaining_fee_list.php'"
     style="cursor:pointer;">

    <div class="summary-title">Remaining Fee</div>

    <div class="summary-value" style="color:#e74c3c;">
        Rs. <?= number_format($totalRemaining) ?>

    </div>
</div>


         <div class="summary-card" 
     onclick="window.location.href='expenditure_list.php'" 
     style="cursor:pointer;">

    <div class="summary-title">Total Expenditure</div>

    
    <div class="summary-value">
        Rs. <?= number_format($expenditure) ?>
        
    </div>

</div>
<div class="summary-card"
     onclick="window.location.href='cash_in_hand.php'"
     style="cursor:pointer;">
<div class="summary-title">Cash In Hand</div>
<div class="summary-value"
     style="color:<?= $isCashNegative ? '#e74c3c' : '#27ae60' ?>;">
    Rs. <?= number_format($cashInHand) ?>
</div>


</div>


            <!-- <a href="remaining_fee_list.php" class="summary-card-link">
                <div class="summary-card">
                    <div class="summary-title">Remaining Fee</div>
                    <div class="summary-value">Rs. <?= number_format($remaining) ?></div>
                </div>
            </a> -->


            <!-- <div class="summary-card" onclick="window.location.href='fee_receipt_list.php'" style="cursor: pointer;">
                <div class="summary-title">Courses</div>
                <div class="summary-value"><?= $courses ?></div>
            </div> -->

        </div>

<div class="footer">
    © 2026 CISD CHD | All Rights Reserved <br>
    <span style="font-size:14px; opacity:0.9;">
        Developed by <strong>Usman Ali </strong>
    </span>
</div>

    </div>
    <script>
        const timeoutMinutes = <?php echo file_exists("session_timeout.txt") ? intval(file_get_contents("session_timeout.txt")) : 15; ?>;
        const timeoutSeconds = timeoutMinutes * 60;

        const warningBefore = 60; // seconds before logout to show warning
        const countdownStart = timeoutSeconds - warningBefore;

        let timeElapsed = 0;
        let countdownShown = false;

        const interval = setInterval(() => {
            timeElapsed++;

            if (timeElapsed >= countdownStart && !countdownShown) {
                countdownShown = true;
                showCountdown(warningBefore);
            }

            if (timeElapsed >= timeoutSeconds) {
                clearInterval(interval);
                window.location.href = 'logout.php';
            }
        }, 1000);

        function showCountdown(seconds) {
            const countdownDiv = document.createElement('div');
            countdownDiv.id = "session-countdown";
            countdownDiv.style.position = "fixed";
            countdownDiv.style.bottom = "30px";
            countdownDiv.style.right = "30px";
            countdownDiv.style.background = "linear-gradient(to right, #e74c3c, #c0392b)";
            countdownDiv.style.color = "#fff";
            countdownDiv.style.padding = "15px 25px";
            countdownDiv.style.borderRadius = "10px";
            countdownDiv.style.fontSize = "20px";
            countdownDiv.style.boxShadow = "0 8px 16px rgba(0,0,0,0.3)";
            countdownDiv.style.zIndex = "9999";
            countdownDiv.style.animation = "pulse 1s infinite";

            document.body.appendChild(countdownDiv);

            const countdownInterval = setInterval(() => {
                countdownDiv.innerHTML = `⏳ Session will expire in <b>${seconds}</b> seconds...`;

                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    countdownDiv.innerHTML = "🔒 Logging out...";
                }

                seconds--;
            }, 1000);
        }

        // Pulse animation
        const style = document.createElement('style');
        style.innerHTML = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }`;
        document.head.appendChild(style);
    </script>


</body>
<script>
    document.querySelectorAll(".dropdown-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            btn.parentElement.classList.toggle("active");
        });
    });
</script>

</html>
<script>
    const darkMode = localStorage.getItem("dark-mode");
    if (darkMode === "enabled") {
        document.body.classList.add("dark");
    }

</script>
<script>
document.querySelectorAll(".dropdown-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        this.nextElementSibling.classList.toggle("show");
    });
});
</script>

<style>
.dropdown-menu.show{
    display:block;
}
</style>
<script>
document.querySelectorAll(".menu-dropdown .dropdown-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        const parent = btn.parentElement;
        parent.classList.toggle("active");

        const menu = parent.querySelector(".dropdown-content");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });
});
</script>

