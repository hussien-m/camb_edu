document.addEventListener('DOMContentLoaded', function() {
    // Timer - use ISO format for JavaScript Date
    const startTime = new Date(document.querySelector('[data-start-time]')?.getAttribute('data-start-time') || new Date());
    const durationMinutes = parseInt(document.querySelector('[data-duration]')?.getAttribute('data-duration') || 0);
    const endTime = startTime.getTime() + (durationMinutes * 60 * 1000);
    const timerElement = document.getElementById('timeRemaining');

    // Debug info
    console.log('Start Time:', startTime);
    console.log('Duration:', durationMinutes, 'minutes');
    console.log('End Time:', new Date(endTime));
    console.log('Current Time:', new Date());
    console.log('Time remaining (ms):', endTime - new Date().getTime());

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            console.log('Time expired!');
            timerElement.innerHTML = "TIME'S UP!";
            timerElement.parentElement.classList.add('text-danger');
            clearInterval(timerInterval);
            const submitForm = document.getElementById('submitExamForm');
            if (submitForm) {
                submitForm.submit();
            }
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.innerHTML = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (minutes < 5) {
            timerElement.parentElement.classList.add('text-warning');
        }
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Question navigation
    let currentQuestion = 0;
    const questions = document.querySelectorAll('.question-card');
    const totalQuestions = questions.length;

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = i === index ? 'block' : 'none';
        });
        currentQuestion = index;

        // Update navigation buttons
        document.querySelectorAll('.prev-btn').forEach(btn => {
            btn.disabled = index === 0;
        });
    }

    // Navigation buttons
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentQuestion < totalQuestions - 1) {
                showQuestion(currentQuestion + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentQuestion > 0) {
                showQuestion(currentQuestion - 1);
            }
        });
    });

    // Question navigator buttons
    document.querySelectorAll('.question-nav-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const questionIndex = parseInt(this.getAttribute('data-question'));
            showQuestion(questionIndex);
        });
    });

    // Save answer via AJAX
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionId = this.getAttribute('data-question-id');
            const optionId = this.value;
            const attemptId = document.querySelector('[data-attempt-id]')?.getAttribute('data-attempt-id');

            fetch(`/student/exams/${attemptId}/save-answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question_id: questionId,
                    option_id: optionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update answer count
                    const answeredCount = document.getElementById('answeredCount');
                    const unansweredCount = document.getElementById('unansweredCount');

                    if (answeredCount && unansweredCount) {
                        answeredCount.textContent = data.answered_count;
                        unansweredCount.textContent = data.unanswered_count;
                    }

                    // Update navigator button
                    const navBtn = document.querySelector(`[data-question="${Array.from(questions).findIndex(q => q.querySelector(`input[value="${optionId}"]`))}"]`);
                    if (navBtn) {
                        navBtn.classList.add('btn-success', 'text-white');
                    }
                }
            })
            .catch(error => console.error('Error saving answer:', error));
        });
    });

    // Submit exam
    const submitBtn = document.getElementById('submitExamBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to submit the exam? This action cannot be undone.')) {
                document.getElementById('submitExamForm').submit();
            }
        });
    }
});
