<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/header.php";
require __DIR__ . "/../function/function.php";
blockAccess();
$TOTAL_SECTIONS = 5;
$user_id = $_SESSION['id'];

$start_section = 1;
try {
    $stmt = $connection->prepare("SELECT MAX(section_number) AS max_completed FROM survey_progress WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['max_completed'] !== null) {
        $max_completed = (int)$result['max_completed'];
        $start_section = ($max_completed >= $TOTAL_SECTIONS)
            ? $TOTAL_SECTIONS + 1
            : $max_completed + 1;
    }
} catch (PDOException $e) {
    error_log("Progress lookup failed for user $user_id: " . $e->getMessage());
    $start_section = 1;
}
?>

<div id="survey-content-wrapper">
    <section class="survey-section" id="section-1">
        <div class="survey-wrapper">
            <form class="surveyForm" id="surveyForm-1" data-section="1">
                <div class="survey-header">
                    <h1>Student <span class="text-danger">Experience Feedback</span> </h1>
                    <p class="subtitle">Please confirm your details to begin the survey.</p>
                </div>

                <div class="form-row">
                    <div class="question-group">
                        <label for="student_name">Full Name</label>
                        <input type="text" id="student_name" name="student_name"
                            value="<?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? '') ?>"
                            readonly required>
                    </div>
                    <div class="question-group">
                        <label for="student_email">Email</label>
                        <input type="email" id="student_email" name="student_email"
                            value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>"
                            readonly required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="question-group" style="width: 100%;">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="CS">Computer Science</option>
                            <option value="EE">Electrical Engineering</option>
                            <option value="BBA">Business Administration</option>
                        </select>
                    </div>
                    <div class="question-group"> <label for="attendance">Attendance Type</label> <select id="attendance" name="attendance" required>
                            <option value="">Select Type</option>
                            <option>Full-Time</option>
                            <option>Part-Time</option>
                            <option>Exchange</option>
                        </select> </div>
                </div>

                <div class="form-row">
                    <div class="question-group"> <label for="semester">Current Semester</label> <select id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option>1st</option>
                            <option>2nd</option>
                            <option>3rd</option>
                            <option>4th</option>
                            <option>5th</option>
                            <option>6th</option>
                            <option>7th</option>
                            <option>8th</option>
                        </select> </div>
                    <div class="question-group"> <label for="degree">Degree Program</label> <select id="degree" name="degree" required>
                            <option value="">Select Program</option>
                            <option>BS</option>
                            <option>MS</option>
                            <option>PhD</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="question-group"> <label for="age_group">Age Group</label> <select id="age_group" name="age_group" required>
                            <option value="">Select Age Range</option>
                            <option value="18-22">18–22</option>
                            <option value="23-26">23–26</option>
                            <option value=">26">Above 26</option>
                        </select> </div>

                </div>
                <div class="navigation-buttons"> <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> </div>
            </form>
        </div>
    </section>
    <section class="survey-section" id="section-2">
        <div class="survey-container">
            <div class="survey-header">
                <h1>Student <span class="text-danger">Experience Feedback</span></h1>
                <p class="subtitle">Share Your Experience About Your Teacher.</p>
            </div>
            <form class="surveyForm" id="surveyForm-2" data-section="2">
                <div class="form-row">
                    <div class="form-group"> <label>How would you rate the teaching quality?</label> <select name="q_teaching_quality" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                    <div class="form-group"> <label>How effective were the course materials?</label> <select name="q_course_materials" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>How engaging were the lectures?</label> <select name="q_lectures_engaging" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                    <div class="form-group"> <label>How helpful was the instructor?</label> <select name="q_instructor_helpful" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>How organized was the course content?</label> <select name="q_course_organized" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                    <div class="form-group"> <label>How clear were the grading criteria?</label> <select name="q_grading_clear" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>How was communication from faculty?</label> <select name="q_faculty_comm" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                    <div class="form-group"> <label>How was the classroom environment?</label> <select name="q_classroom_env" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>How supportive was the administration?</label> <select name="q_admin_support" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                    <div class="form-group"> <label>Overall Your academic experience?</label> <select name="q_overall_academic" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Very Poor</option>
                        </select> </div>
                </div>
                <div class="navigation-buttons"> <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> </div>
            </form>
        </div>
    </section>
    <section class="survey-section" id="section-3">
        <div class="survey-container">
            <div class="survey-header">
                <h1>Student <span class="text-danger">Experience Feedback</span></h1>
                <p class="subtitle">Share Your Experience About Campus Facilities.</p>
            </div>
            <form class="surveyForm" id="surveyForm-3" data-section="3">
                <div class="form-row">
                    <div class="form-group"> <label>Classrooms (Cleanliness, equipment, ventilation):</label> <select name="r_classrooms" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                    <div class="form-group"> <label>Library (Resources, study environment, service):</label> <select name="r_library" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>Wi-Fi & Internet Connectivity:</label> <select name="r_wifi" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                    <div class="form-group"> <label>Cafeteria/Canteen (Quality, variety, hygiene):</label> <select name="r_cafeteria" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>Transportation Services (Availability and cost):</label> <select name="r_transport" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                    <div class="form-group"> <label>Security Measures on Campus:</label> <select name="r_security" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>Hostel Facilities (Accommodation, food, maintenance):</label> <select name="r_hostel" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                </div>
                <div class="navigation-buttons"> <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> </div>
            </form>
        </div>
    </section>
    <section class="survey-section" id="section-4">
        <div class="survey-container">
            <div class="survey-header">
                <h1>Student <span class="text-danger">Experience FeedBack</span></h1>
                <p class="subtitle">Share Your Experience About Overall Environment.</p>
            </div>
            <form class="surveyForm" id="surveyForm-4" data-section="4">
                <div class="form-row">
                    <div class="form-group"> <label>Overall feeling of safety and well-being on campus:</label> <select name="e_safety" required>
                            <option value="">Select</option>
                            <option>Very Safe</option>
                            <option>Safe</option>
                            <option>Neutral</option>
                            <option>Unsafe</option>
                            <option>Very Unsafe</option>
                        </select> </div>
                    <div class="form-group"> <label>Effectiveness of anti-bullying/harassment policies:</label> <select name="e_anti_bullying" required>
                            <option value="">Select</option>
                            <option>Very Effective</option>
                            <option>Effective</option>
                            <option>Needs Improvement</option>
                            <option>Poor</option>
                            <option>Non-existent/Unaware</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>Availability and quality of student societies/clubs:</label> <select name="e_societies" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                    <div class="form-group"> <label>Frequency and relevance of events and seminars:</label> <select name="e_events" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Lacking</option>
                            <option>Not Applicable</option>
                        </select> </div>
                </div>
                <div class="form-row">
                    <div class="form-group"> <label>Quality of Internship & Job Placement cell:</label> <select name="e_placement" required>
                            <option value="">Select</option>
                            <option>Excellent</option>
                            <option>Good</option>
                            <option>Average</option>
                            <option>Poor</option>
                            <option>Not Applicable</option>
                        </select> </div>
                    <div class="form-group"> <label>Availability and quality of mental health support services:</label> <select name="e_mental_health" required>
                            <option value="">Select</option>
                            <option>Available and Good</option>
                            <option>Available but Poor Quality</option>
                            <option>Not Available/Unaware</option>
                        </select> </div>
                </div>
                <div class="navigation-buttons"> <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> </div>
            </form>
        </div>
    </section>
    <section class="survey-section" id="section-5">
        <div class=" survey-container">
            <div class="survey-header">
                <h1>Student <span class="text-danger">Experience FeedBack</span></h1>
                <p class="subtitle">Your Suggestions will Allow Us to Improve Your Experience.</p>
            </div>
            <form class="surveyForm" id="surveyForm-5" data-section="5">
                <div class="question-group full-width-feedback"> <label for="final_feedback">Provide Your FeedBack:</label> <textarea id="final_feedback" name="final_feedback" placeholder="Type your suggestions here" required></textarea> </div>
                <div class="navigation-buttons"> <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Submit Survey</button> </div>
            </form>
        </div>
    </section>
</div>


<div id="success-message">
    <div class="success-content">
        <h1>Successfully Submitted!</h1>
        <p>Thank you for completing the Student Experience Feedback survey. <br> Redirecting to homepage</p>
    </div>
</div>
<div id="toast-container"></div>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const TOTAL_SECTIONS = <?= $TOTAL_SECTIONS; ?>;
    let currentSectionIndex = <?= $start_section; ?>;

    const surveyContentWrapper = document.getElementById('survey-content-wrapper');
    const successMessage = document.getElementById('success-message');
    const forms = document.querySelectorAll('.surveyForm');

    function showSection(index) {
        document.querySelectorAll('.survey-section').forEach(section => {
            section.style.display = 'none';
        });

        if (index > TOTAL_SECTIONS) {
            surveyContentWrapper.style.display = 'none';
            successMessage.style.display = 'flex';
            setTimeout(() => {
                window.location.href = "/index.php";
            }, 2000);
            return;
        }

        const targetSection = document.getElementById(`section-${index}`);
        if (targetSection) {
            targetSection.style.display = 'flex';
            successMessage.style.display = 'none';
            surveyContentWrapper.style.display = 'flex';
        }
    }

    showSection(currentSectionIndex);

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formElement = this;
            const sectionNumber = parseInt(formElement.getAttribute('data-section'));
            const submitButton = formElement.querySelector('.btn-primary');

            if (!formElement.checkValidity()) {
                showToast('Please fill all required fields', 'error');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = 'Processing';

            const formData = new FormData(formElement);
            formData.append('section_number', sectionNumber);

            fetch('/pages/survey_handler.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            showToast('Session expired. Please log in again.', 'error');
                            setTimeout(() => window.location.reload(), 1500);
                            throw new Error('Unauthorized');
                        }
                        throw new Error('Server error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Saved successfully', 'success');
                        currentSectionIndex = data.next_section;
                        showSection(currentSectionIndex);
                    } else {
                        showToast(data.message || 'Submission failed', 'error');
                    }
                })
                .catch(error => {
                    console.error(' Error:', error);
                    showToast('A critical error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML =
                        sectionNumber === TOTAL_SECTIONS ?
                            '<i class="fas fa-check-circle"></i> Submit Survey' :
                            'Next <i class="fas fa-arrow-right"></i>';
                });
        });
    });
});

</script>
<?php require __DIR__ . "/../includes/footer.php"; ?>