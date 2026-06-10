<?php require_once __DIR__ . '/../config.php'; if(!isset($PAGE)) $PAGE=''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= e($PAGE_TITLE ?? $INSTITUTE['name']) ?></title>
<meta name="description" content="<?= e($PAGE_DESC ?? $INSTITUTE['tagline']) ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header class="navbar">
  <div class="container nav-inner">
    <a href="index.php" class="brand">
      <img src="images/CISD.png" alt="CISD INSTITUTE Logo" class="brand-logo" />

    </a>
    <nav class="nav-links" id="navLinks">
      <a href="index.php" class="<?= $PAGE==='home'?'active':'' ?>">Home</a>
      <a href="about.php" class="<?= $PAGE==='about'?'active':'' ?>">About</a>
      <a href="courses.php" class="<?= $PAGE==='courses'?'active':'' ?>">Courses</a>
      <a href="gallery.php" class="<?= $PAGE==='gallery'?'active':'' ?>">Gallery</a>
      <a href="contact.php" class="<?= $PAGE==='contact'?'active':'' ?>">Contact</a>
      <a href="admin/dashboard.php" class="<?= $PAGE==='admin-dashboard'?'active':'' ?>">Admin</a>
      <a href="admissions.php" class="btn btn-gold nav-cta">Apply Now</a>
    </nav>
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
<main>
