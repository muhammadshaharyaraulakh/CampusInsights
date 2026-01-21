 <?php
  require __DIR__ . "/../config/config.php";
  require __DIR__ . "/../includes/header.php";
  require __DIR__ . "/../function/function.php";
  RoleBasedAccess();
  ?>

 <section class="about">
   <div class="container about-container fade-in">
     <div class="about-content">
       <h1>About <span class="text-danger">Us</span></h1>
       <p>
         Welcome to <a href="/index.php">CampusInsights</a> a platform built to gather and analyze insights about the educational environment at universities.
         Our goal is to create a better understanding of how students and educators experience their academic surroundings and help institutions make data-driven improvements.
       </p>
       <p>
         We believe in the power of feedback and research. By collecting surveys, we aim to highlight key areas that shape student satisfaction, learning quality, and campus life.
       </p>
       <p>
         Whether you're a student, a teacher, or a researcher, EduSurvey gives you a voice. Together, we can make education more effective, inclusive, and inspiring.
       </p>

       <a href="/pages/contact.php" class="btn btn-secondary">Contact Us</a>
     </div>

     <div class="about-image">
       <img src="/assests/Images/about.png" alt="University Environment">
     </div>
   </div>
 </section>
 <?php require __DIR__ . "/../includes/footer.php"; ?>