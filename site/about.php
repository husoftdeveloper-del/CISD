<?php $PAGE='about'; $PAGE_TITLE='About — CISD INSTITUTE'; $PAGE_DESC='Learn about our mission, vision and the team behind CISD INSTITUTE.'; include 'includes/header.php'; ?>

<section class="page-header">
  <div class="container">
    <span class="eyebrow">About Us</span>
    <h1>Empowering the next generation of digital talent</h1>
    <p>Born from a passion to bridge the gap between classroom theory and industry skills.</p>
  </div>
</section>

<section class="section">
  <div class="container about-grid">
    <div class="reveal">
      <span class="eyebrow">Our Story</span>
      <h2>Built by professionals, for ambitious learners</h2>
      <p style="color:var(--muted);margin-top:14px">NovaSkills was founded with one mission: to make world-class IT and digital skills education accessible, practical and outcome-driven. Our graduates work as freelancers, full-time employees and founders — across borders.</p>
      <p style="color:var(--muted);margin-top:10px">We combine modern curriculum, hands-on projects, and 1:1 mentorship to ensure every student walks out with a portfolio, not just a certificate.</p>
    </div>
    <div class="reveal">
      <img src="images/hero-classroom.jpg" alt="Classroom" style="border-radius:20px;box-shadow:var(--shadow)" />
    </div>
  </div>
</section>

<section class="section" style="background:#fff">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Our Values</span><h2>Mission, Vision & Promise</h2></div>
    <div class="mission-grid">
      <div class="feature reveal"><div class="icon">M</div><h3>Mission</h3><p>To deliver practical, industry-aligned training that opens real career and freelance opportunities.</p></div>
      <div class="feature reveal"><div class="icon">V</div><h3>Vision</h3><p>To be the most trusted digital skills institute producing employable, confident professionals.</p></div>
      <div class="feature reveal"><div class="icon">P</div><h3>Promise</h3><p>Real projects, real mentors, real outcomes — or your fee refunded within 7 days.</p></div>
      <div class="feature reveal"><div class="icon">C</div><h3>Community</h3><p>Lifetime access to our alumni community for jobs, gigs and collaborations.</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Meet the Team</span><h2>Mentors who care about your success</h2></div>
    <div class="grid cards-4">
      <?php
      $team=[
    ['shahabsir.JPEG','Shahab Khan','Founder & Lead Instructor','Software Engineer'],
    ['qarisaib.JPEG','Usman Ali','Senior Trainer','UI/UX & Graphic Design'],

    ['Hafiz.PNG','Hafiz Ullah ','Trainer','Video Editing & Content'],
        ['Daniyal.JPEG','Danyal','Trainer','Digital Marketing'],
];
      foreach($team as $i => $t): ?>
      <div class="card reveal">
        <img src="images/<?= e($t[0]) ?>" alt="<?= e($t[1]) ?>" loading="lazy" />
        <div class="card-body">
          <h3><?= e($t[1]) ?></h3>
          <p style="color:var(--gold);font-weight:600;margin:0"><?= e($t[2]) ?></p>
          <p><?= e($t[3]) ?></p>
        <a href="teacher.php?id=<?= $i ?>" class="view-more-btn">View More</a>
          </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
