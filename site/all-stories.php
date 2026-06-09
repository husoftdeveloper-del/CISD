<?php
$PAGE = 'all-stories';
$PAGE_TITLE = 'All Student Success Stories - CISD Institute';
$PAGE_DESC = 'Read all success stories from our graduates.';
include 'includes/header.php';
?>
<section class="section testimonials">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Student Stories</span>
      <h2>All Success Stories</h2>
    </div>
    <div class="grid cards-3">
      <?php

        $stmt = $pdo->query("SELECT id, image, name, father_name, course, quote, created_at FROM success_stories ORDER BY created_at DESC");
        $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stories as $s):
      ?>
        <div class="t-card reveal">
          <img src="<?php echo e($s['image'] ? 'uploads/stories/'.$s['image'] : 'uploads/stories/placeholder.png'); ?>" alt="<?php echo e($s['name']); ?>" class="t-profile-img" />
          <div class="t-stars">✦✦✦✦✦</div>
          <p class="t-quote">“<?php echo e($s['quote']); ?>”</p>
          <div class="t-author">
            <div><strong><?php echo e($s['name']); ?></strong><small><?php echo e($s['course']); ?></small></div>
            <button class="view-more-btn" onclick="viewMore(this)" data-image="<?php echo e($s['image'] ? 'uploads/stories/'.$s['image'] : 'uploads/stories/placeholder.png'); ?>" data-name="<?php echo e($s['name']); ?>" data-father="<?php echo e($s['father_name']); ?>" data-course="<?php echo e($s['course']); ?>" data-location="<?php echo e($s['location'] ?? 'N/A'); ?>" data-contact="<?php echo e($s['contact'] ?? 'N/A'); ?>" data-email="<?php echo e($s['email'] ?? 'N/A'); ?>" data-education="<?php echo e($s['education'] ?? 'N/A'); ?>" data-quote="<?php echo e($s['quote']); ?>">View More</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Modal -->
<div id="storyModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:1000;">
  <div class="modal-content" style="background:#fff; padding:20px; max-width:600px; border-radius:8px; position:relative;">
    <span class="close" onclick="closeModal()" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:24px;">&times;</span>
    <img id="modalImage" src="" alt="Student" style="max-width:100%; height:auto; margin-bottom:15px;" />
    <h3 id="modalName"></h3>
    <p><strong>Father:</strong> <span id="modalFather"></span></p>
    <p><strong>Course:</strong> <span id="modalCourse"></span></p>
    <p><strong>Location:</strong> <span id="modalLocation"></span></p>
    <p><strong>Contact:</strong> <span id="modalContact"></span></p>
    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
    <p><strong>Education:</strong> <span id="modalEducation"></span></p>
    <p><strong>Story:</strong> <span id="modalQuote"></span></p>
  </div>
</div>
<script>
function viewMore(btn) {
  document.getElementById('modalImage').src = btn.getAttribute('data-image');
  document.getElementById('modalName').textContent = btn.getAttribute('data-name');
  document.getElementById('modalFather').textContent = btn.getAttribute('data-father') || 'N/A';
  document.getElementById('modalCourse').textContent = btn.getAttribute('data-course') || 'N/A';
  document.getElementById('modalLocation').textContent = btn.getAttribute('data-location') || 'N/A';
  document.getElementById('modalContact').textContent = btn.getAttribute('data-contact') || 'N/A';
  document.getElementById('modalEmail').textContent = btn.getAttribute('data-email') || 'N/A';
  document.getElementById('modalEducation').textContent = btn.getAttribute('data-education') || 'N/A';
  document.getElementById('modalQuote').textContent = btn.getAttribute('data-quote');
  document.getElementById('storyModal').style.display = 'flex';
}

}
function closeModal() { document.getElementById('storyModal').style.display = 'none'; }
</script>

<?php include 'includes/footer.php'; ?>
