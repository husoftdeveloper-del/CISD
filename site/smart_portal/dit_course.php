<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DIT Program</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#eef4ff,#f8fbff);
    padding:50px 25px;
}

/* ===== Title ===== */
.title{
    text-align:center;
    margin-bottom:45px;
}

.title h2{
    font-size:32px;
    color:#2c3e50;
    margin-bottom:8px;
}

.title p{
    color:#6c7a89;
    font-size:15px;
}

/* ===== Cards Wrapper ===== */
.dit-box{
    display:flex;
    justify-content:center;
    gap:35px;
    flex-wrap:wrap;
}

/* ===== Card ===== */
.dit-card{
    width:320px;
    background:#fff;
    border-radius:20px;
    padding:35px 25px;
    text-align:center;
    box-shadow:0 15px 35px rgba(0,0,0,0.12);
    transition:0.4s;
    position:relative;
    overflow:hidden;
}

/* gradient line */
.dit-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background:linear-gradient(to right,#1abc9c,#3498db);
}

.dit-card:hover{
    transform:translateY(-10px);
    box-shadow:0 25px 45px rgba(0,0,0,0.18);
}

/* ===== Icon ===== */
.dit-card i{
    font-size:52px;
    color:#1abc9c;
    margin-bottom:18px;
}

/* ===== Text ===== */
.dit-card h3{
    font-size:22px;
    color:#2c3e50;
    margin-bottom:10px;
}

.dit-card p{
    font-size:14px;
    color:#7f8c8d;
    line-height:1.6;
}

/* ===== Button ===== */
.dit-card a{
    display:inline-block;
    margin-top:22px;
    padding:12px 28px;
    border-radius:30px;
    background:linear-gradient(to right,#1abc9c,#16a085);
    color:#fff;
    font-weight:600;
    text-decoration:none;
    transition:0.35s;
    box-shadow:0 8px 20px rgba(0,0,0,0.18);
}

.dit-card a:hover{
    transform:translateY(-3px);
    box-shadow:0 14px 30px rgba(0,0,0,0.25);
}

/* ===== Responsive ===== */
@media(max-width:768px){
    .title h2{font-size:26px;}
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="title">
    <h2>🎓 Diploma in Information Technology (DIT)</h2>
    <p>Select semester to view subjects, marks & fee details</p>
</div>

<div class="dit-box">

    <div class="dit-card">
        <i class="fas fa-book-reader"></i>
        <h3>1st Semester</h3>
        <p>
            IT Fundamentals, MS Office, Internet & Computer Basics
        </p>
        <a href="dit_sem1.php">View Details</a>
    </div>

    <div class="dit-card">
        <i class="fas fa-laptop-code"></i>
        <h3>2nd Semester</h3>
        <p>
            Web Development, Programming Concepts & Database Systems
        </p>
        <a href="dit_sem2.php">View Details</a>
    </div>

</div>

</body>
</html>
