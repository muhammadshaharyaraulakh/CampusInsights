<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/header.php";
require __DIR__ . "/../function/function.php";
blockAccess();
$TOTAL_SECTIONS = 5;
$user_id = $_SESSION['id'];


$TOTAL_SECTIONS = 5;
$user_id = $_SESSION['id'];

// 1. Check Status First
// Sirf 'survey_progress' column select karein (Optimization)
$statusStmt = $connection->prepare("SELECT survey_progress FROM user WHERE id = :id LIMIT 1");
$statusStmt->execute([':id' => $user_id]);

// Force FETCH_ASSOC taake array mile
$userStatus = $statusStmt->fetch(PDO::FETCH_ASSOC); 

// Check if status is completed
if ($userStatus && $userStatus['survey_progress'] === 'completed') {
    // Note: 'Location:' ke baad space mat dein
    header("Location: /index.php");
    exit;
}

// .

// 2. Calculate Start Section
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
    error_log("Progress lookup failed: " . $e->getMessage());
    $start_section = 1;
}
?>
?>

<div id="survey-content-wrapper">
<section class="survey-section" id="section-1">
    <div class="survey-container">
        <div class="survey-header">
            <h1>Faculty <span class="text-danger">Evaluation</span></h1>
            <p class="subtitle">Please provide feedback specifically regarding all the course instructors.</p>
        </div>
        <form class="surveyForm" id="surveyForm-1" data-section="1">
            
            <div class="form-row">
                <div class="form-group">
                    <label>How would you rate the teachers subject knowledge?</label>
                    <select name="q_teacher_knowledge" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>How clear were the teachers explanations?</label>
                    <select name="q_teacher_clarity" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>How punctual were the teachers for class?</label>
                    <select name="q_teacher_punctuality" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>How well-prepared were the teachers for lectures?</label>
                    <select name="q_teacher_preparedness" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>How effective were teachers keeping the class engaged?</label>
                    <select name="q_teacher_engagement" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>How approachable were the teachers for questions?</label>
                    <select name="q_teacher_approachability" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>How fair were the teachers in grading?</label>
                    <select name="q_teacher_fairness" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>How well did the teachers use practical examples?</label>
                    <select name="q_teacher_examples" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>How appropriate were the pace of the lectures?</label>
                    <select name="q_teacher_pace" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Overall professionalism of the teachers?</label>
                    <select name="q_teacher_professionalism" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="navigation-buttons"> 
                <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> 
            </div>
        </form>
    </div>
</section>
<section class="survey-section" id="section-2">
    <div class="survey-container">
        <div class="survey-header">
            <h1>Facility <span class="text-danger">Feedback</span></h1>
            <p class="subtitle">Please rate the Classrooms and Computer Labs facilities.</p>
        </div>
        <form class="surveyForm" id="surveyForm-2" data-section="2">
            
            <div class="form-row">
                <div class="form-group">
                    <label>How would you rate the cleanliness of classrooms?</label>
                    <select name="q_class_cleanliness" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Condition of classroom furniture (Desks/Chairs)?</label>
                    <select name="q_class_furniture" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quality of Projectors/Multimedia in classrooms?</label>
                    <select name="q_class_multimedia" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lighting and ventilation in classrooms?</label>
                    <select name="q_class_ventilation" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Performance speed of Computer Lab PCs?</label>
                    <select name="q_lab_hardware" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Availability of required software/tools in Labs?</label>
                    <select name="q_lab_software" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Condition of keyboards, mice, and screens?</label>
                    <select name="q_lab_peripherals" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Internet connectivity speed within the Labs?</label>
                    <select name="q_lab_internet" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Availability of Lab Instructors/Technical Support?</label>
                    <select name="q_lab_support" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Availability of Labs for extra practice time?</label>
                    <select name="q_lab_access" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="navigation-buttons"> 
                <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> 
            </div>
        </form>
    </div>
</section>
<section class="survey-section" id="section-3">
    <div class="survey-container">
        <div class="survey-header">
            <h1>Campus <span class="text-danger">Environment</span></h1>
            <p class="subtitle">Share your experience regarding safety, culture, and general atmosphere.</p>
        </div>
        <form class="surveyForm" id="surveyForm-3" data-section="3">
            
            <div class="form-row">
                <div class="form-group"> 
                    <label>How would you rate the overall safety on campus?</label> 
                    <select name="env_safety" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
                <div class="form-group"> 
                    <label>Behavior and cooperation of security staff?</label> 
                    <select name="env_security_staff" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"> 
                    <label>Effectiveness of anti-harassment/bullying policies?</label> 
                    <select name="env_harassment_policy" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
                <div class="form-group"> 
                    <label>Ease of reporting complaints or grievances?</label> 
                    <select name="env_complaint_system" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"> 
                    <label>Level of respect shown by administration to students?</label> 
                    <select name="env_admin_respect" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
                <div class="form-group"> 
                    <label>Gender sensitivity and respect across campus?</label> 
                    <select name="env_gender_respect" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"> 
                    <label>Cleanliness of common areas (grounds, corridors)?</label> 
                    <select name="env_cleanliness" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
                <div class="form-group"> 
                    <label>Quality of campus greenery and sitting areas?</label> 
                    <select name="env_greenery" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"> 
                    <label>Enforcement of student discipline rules?</label> 
                    <select name="env_discipline" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
                <div class="form-group"> 
                    <label>Overall peacefulness of the study environment?</label> 
                    <select name="env_peacefulness" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select> 
                </div>
            </div>

            <div class="navigation-buttons"> 
                <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> 
            </div>
        </form>
    </div>
</section>
<section class="survey-section" id="section-4">
    <div class="survey-container">
        <div class="survey-header">
            <h1>Transport & <span class="text-danger">Canteen</span></h1>
            <p class="subtitle">Please evaluate the university transport services and cafeteria facilities.</p>
        </div>
        <form class="surveyForm" id="surveyForm-4" data-section="4">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Availability and coverage of transport routes?</label>
                    <select name="trans_routes" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Physical condition and cleanliness of buses/vans?</label>
                    <select name="trans_condition" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Punctuality of transport arrival/departure?</label>
                    <select name="trans_punctuality" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Behavior and cooperation of transport staff?</label>
                    <select name="trans_staff" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Affordability of transport charges/fares?</label>
                    <select name="trans_cost" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Hygiene and cleanliness of the canteen area?</label>
                    <select name="cant_hygiene" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Freshness and taste of the food served?</label>
                    <select name="cant_quality" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Affordability/Pricing of food items?</label>
                    <select name="cant_price" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Variety of food options available in the menu?</label>
                    <select name="cant_variety" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Speed of service and staff behavior in canteen?</label>
                    <select name="cant_service" required>
                        <option value="">Select</option>
                        <option value="5">Excellent</option>
                        <option value="4">Good</option>
                        <option value="3">Average</option>
                        <option value="2">Poor</option>
                        <option value="1">Very Poor</option>
                    </select>
                </div>
            </div>

            <div class="navigation-buttons"> 
    <button type="submit" class="btn btn-primary">Next <i class="fas fa-arrow-right"></i></button> 
</div>
        </form>
    </div>
</section>
<section class="survey-section" id="section-5">
    <div class="survey-container">
        <div class="survey-header">
            <h1>Confidential <span class="text-danger">Complaints & Feedback</span></h1>
            <p class="subtitle">Feel free to register a specific complaint about any teacher, staff member, or department.</p>
        </div>
        <form class="surveyForm" id="surveyForm-5" data-section="5">
            
            <div class="form-row">
                <div class="form-group question-group full-width-feedback">
                    <label>Name of the Person or Department you have a complaint about (Optional):</label>
                    <input type="text" name="complaint_target" placeholder=" Mr. XYZ, Admin Office, Security Guard" class="form-control">
                </div>
            </div>

            <div class="question-group full-width-feedback"> 
                <label for="final_feedback">Please describe your complaint or suggestion in detail:</label> 
                <textarea id="final_feedback" name="final_feedback" placeholder="Type your complaint or suggestion here freely" required></textarea> 
            </div>
            
            <div class="navigation-buttons"> 
                <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Submit Survey</button> 
            </div>
        </form>
    </div>
</section>
</div>


<div id="success-message">
    <div class="success-content">
        <h1>Successfully Submitted!</h1>
        <p>Thank you for completing the Student Experience Feedback survey. <br> Redirecting to Homepage</p>
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