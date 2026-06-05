<?php
// teacher.php - display individual teacher details
require 'includes/header.php';

// Define teacher data (same order as in about.php)
$teachers = [
    [
        'image' => 'shahabsir.JPEG',
        'name' => 'Shahab Khan',
        'role' => 'Founder & Lead Instructor',
        'field' => 'Software Engineer',
        'location' => 'Sardheri, Charsadda',
        'contact' => '+92-370-504-0330',
        'email' => 'shahab@cisd-institute.com',
        'education' => 'M.Sc. Computer Science'
    ],
    [
        'image' => 'qarisaib.JPEG',
        'name' => 'Usman Ali',
        'role' => 'Senior Trainer',
        'field' => 'UI/UX & Graphic Design',
        'location' => 'Sardheri, Charsadda',
        'contact' => '+92-370-504-0331',
        'email' => 'usman@cisd-institute.com',
        'education' => 'B.Des. Graphic Design'
    ],
    [
        'image' => 'Hafiz.PNG',
        'name' => 'Hafiz Ullah',
        'role' => 'Trainer',
        'field' => 'Video Editing & Content',
        'location' => 'Sardheri,Charsadda',
        'contact' => '+92-370-504-0332',
        'email' => 'hafiz@cisd-institute.com',
        'education' => 'Diploma in Video Production'
    ],
    [
        'image' => 'Daniyal.JPEG',
        'name' => 'Danyal',
        'role' => 'Trainer',
        'field' => 'Digital Marketing',
        'location' => 'Mardan , Pakistan',
        'contact' => '+92-370-504-0333',
        'email' => 'danyal@cisd-institute.com',
        'education' => 'BBA Marketing'
    ]
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
if ($id < 0 || $id >= count($teachers)) {
    echo '<p>Teacher not found.</p>';
    require 'includes/footer.php';
    exit;
}
$teacher = $teachers[$id];
?>
<section class="section">
  <div class="container">
    <div class="card" style="max-width:600px;margin:auto;">
      <img src="images/<?= e($teacher['image']) ?>" alt="<?= e($teacher['name']) ?>" style="width:100%;object-fit:cover;" />
      <div class="card-body">
        <h3><?= e($teacher['name']) ?></h3>
        <p style="color:var(--gold);font-weight:600;margin:0;"><?= e($teacher['role']) ?></p>
        <p><?= e($teacher['field']) ?></p>
        <ul style="list-style:none;padding:0;margin-top:10px;">
          <li><strong>Location:</strong> <?= e($teacher['location']) ?></li>
          <li><strong>Contact:</strong> <?= e($teacher['contact']) ?></li>
          <li><strong>Email:</strong> <a href="mailto:<?= e($teacher['email']) ?>"><?= e($teacher['email']) ?></a></li>
          <li><strong>Education:</strong> <?= e($teacher['education']) ?></li>
        </ul>
        <a href="about.php" class="view-more-btn" style="margin-top:15px;display:inline-block;">Back to Team</a>
      </div>
    </div>
  </div>
</section>
<?php
require 'includes/footer.php';
?>
