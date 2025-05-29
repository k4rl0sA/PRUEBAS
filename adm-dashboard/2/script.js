// Global variables for charts
let ageChart, specialtyChart, disabilityChart, elderlyChart;
let dashboardData = null;

document.addEventListener('DOMContentLoaded', function() {
    fetch('lib.php')
        .then(res => res.json())
        .then(data => {
            dashboardData = data;
            initializeCharts(data);
            updateMetrics(data);
            setupEventListeners();
            startRealTimeUpdates();
        })
        .catch(err => {
            alert('Error cargando datos del backend');
            console.error(err);
        });
});

// Inicializar todos los gráficos con datos del backend
function initializeCharts(data) {
    initializeAgeChart(data);
    initializeSpecialtyChart(data);
    initializeDisabilityChart(data);
    initializeElderlyChart(data);
}


// Sample data
/* const healthData = {
    patients: 3553128,
    families: 1570758,
    pregnantWomen: 25933,
    monthlyConsultations: 89456,
    ageDistribution: {
        labels: ['0-5 años', '6-17 años', '18-29 años', '30-44 años', '45-59 años', '60+ años'],
        data: [341866, 568501, 710626, 852754, 420998, 659383]
    },
    genderDistribution: {
        labels: ['Hombres', 'Mujeres'],
        data: [1776564, 1776564]
    },
    specialties: {
        labels: ['Medicina General', 'Pediatría', 'Ginecología', 'Odontología', 'Psicología', 'Enfermería'],
        data: [28456, 15234, 12890, 18765, 8901, 5210]
    },
    disability: {
        labels: ['Visual', 'Auditiva', 'Motora', 'Cognitiva', 'Múltiple'],
        data: [45123, 31087, 52198, 18765, 8236]
    },
    elderly: {
        labels: ['60-69 años', '70-79 años', '80+ años'],
        data: [329691, 197615, 132077]
    }
};
 */


// Age distribution chart
function initializeAgeChart(data) {
    const ctx = document.getElementById('ageChart').getContext('2d');
    ageChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.ageDistribution.labels,
            datasets: [{
                data: data.ageDistribution.values,
                 backgroundColor: [
                    '#FF6B9D', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

// Specialty consultations chart
function initializeSpecialtyChart(data) {
    const ctx = document.getElementById('specialtyChart').getContext('2d');
    specialtyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.specialties.labels,
            datasets: [{
                label: 'Consultas',
                data: data.specialties.values,
                backgroundColor: [
                    '#0066CC',
                    '#00D4FF',
                    '#FF6B9D',
                    '#10B981',
                    '#A855F7',
                    '#F59E0B'
                ],
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Disability chart
function initializeDisabilityChart(data) {
    const ctx = document.getElementById('disabilityChart').getContext('2d');
    disabilityChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.disability.labels,
            datasets: [{
                data: data.disability.values,
                backgroundColor: [
                    '#0066CC',
                    '#00D4FF',
                    '#FF6B9D',
                    '#10B981',
                    '#A855F7'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

// Elderly chart
function initializeElderlyChart(data) {
    const ctx = document.getElementById('elderlyChart').getContext('2d');
    elderlyChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.elderly.labels,
            datasets: [{
                data: data.elderly.values,
                backgroundColor: [
                    '#10B981',
                    '#059669',
                    '#047857'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

// Update metrics with animation
function updateMetrics(data) {
    animateCounter('totalPatients', data.totalPatients);
    animateCounter('totalFamilies', data.totalFamilies);
    animateCounter('pregnantWomen', data.pregnantWomen);
    animateCounter('monthlyConsultations', data.monthlyConsultations);
}

// Animate counter function
function animateCounter(elementId, targetValue) {
    const element = document.getElementById(elementId);
    const startValue = 0;
    const duration = 2000;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const currentValue = Math.floor(startValue + (targetValue - startValue) * progress);
        element.textContent = formatNumber(currentValue);

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    requestAnimationFrame(updateCounter);
}

// Format number with dots as thousands separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Setup event listeners
function setupEventListeners() {
    // Chart toggle buttons
    const chartButtons = document.querySelectorAll('.chart-btn');
    chartButtons.forEach(button => {
        button.addEventListener('click', function() {
            chartButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const chartType = this.dataset.chart;
            toggleChart(chartType);
        });
    });

    // Filter change listeners
    document.getElementById('departmentFilter').addEventListener('change', handleFilterChange);
    document.getElementById('municipalityFilter').addEventListener('change', handleFilterChange);
    document.getElementById('dateFilter').addEventListener('change', handleFilterChange);
}

// Toggle between age and gender charts
function toggleChart(chartType) {
    if (chartType === 'gender') {
        ageChart.data.labels = dashboardData.genderDistribution.labels;
        ageChart.data.datasets[0].data = dashboardData.genderDistribution.data;
        ageChart.data.datasets[0].backgroundColor = ['#0066CC', '#FF6B9D'];
    } else {
        ageChart.data.labels = dashboardData.ageDistribution.labels;
        ageChart.data.datasets[0].data = dashboardData.ageDistribution.data;
        ageChart.data.datasets[0].backgroundColor = [
            '#FF6B9D', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD'
        ];
    }
    ageChart.update();
}

// Handle filter changes
function handleFilterChange() {
    const department = document.getElementById('departmentFilter').value;
    const municipality = document.getElementById('municipalityFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    console.log('Filters changed:', { department, municipality, date });
    
    // Add loading state
    document.body.classList.add('loading');
    
    // Simulate API call
    setTimeout(() => {
        // Update data based on filters
        updateDataBasedOnFilters(department, municipality, date);
        document.body.classList.remove('loading');
    }, 1000);
}

// Update data based on filters
function updateDataBasedOnFilters(department, municipality, date) {
    // Simulate different data based on filters
    const multiplier = Math.random() * 0.3 + 0.85; // Random variation between 0.85 and 1.15
    
    dashboardData.patients = Math.floor(3553128 * multiplier);
    dashboardData.families = Math.floor(1570758 * multiplier);
    dashboardData.pregnantWomen = Math.floor(25933 * multiplier);
    dashboardData.monthlyConsultations = Math.floor(89456 * multiplier);
    
    // Update age distribution
    dashboardData.ageDistribution.data = dashboardData.ageDistribution.data.map(value => 
        Math.floor(value * multiplier)
    );
    
    // Update specialty data
    dashboardData.specialties.data = dashboardData.specialties.data.map(value => 
        Math.floor(value * multiplier)
    );
    
    // Update charts and metrics
    updateMetrics();
    updateCharts();
}

// Update all charts
function updateCharts() {
    ageChart.data.datasets[0].data = dashboardData.ageDistribution.data;
    ageChart.update();
    
    specialtyChart.data.datasets[0].data = dashboardData.specialties.data;
    specialtyChart.update();
}

// Refresh data function
function refreshData() {
    const refreshBtn = document.querySelector('.refresh-btn');
    refreshBtn.style.transform = 'rotate(360deg)';
    refreshBtn.style.transition = 'transform 0.5s ease';
    
    setTimeout(() => {
        refreshBtn.style.transform = 'rotate(0deg)';
    }, 500);
    
    // Simulate data refresh
    updateDataBasedOnFilters('', '', '');
    
    // Add new activity
    addNewActivity();
}

// Add new activity to the list
function addNewActivity() {
    const activities = [
        {
            title: 'Nueva campaña de prevención iniciada',
            time: 'Hace unos segundos',
            type: 'info'
        },
        {
            title: 'Actualización de datos completada',
            time: 'Hace unos segundos',
            type: 'success'
        },
        {
            title: 'Recordatorio: Revisión mensual pendiente',
            time: 'Hace unos segundos',
            type: 'warning'
        }
    ];
    
    const randomActivity = activities[Math.floor(Math.random() * activities.length)];
    const activityList = document.querySelector('.activity-list');
    
    const activityItem = document.createElement('div');
    activityItem.className = 'activity-item';
    activityItem.innerHTML = `
        <div class="activity-icon ${randomActivity.type}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/>
            </svg>
        </div>
        <div class="activity-content">
            <div class="activity-title">${randomActivity.title}</div>
            <div class="activity-time">${randomActivity.time}</div>
        </div>
    `;
    
    activityList.insertBefore(activityItem, activityList.firstChild);
    
    // Remove last item if more than 5 activities
    if (activityList.children.length > 5) {
        activityList.removeChild(activityList.lastChild);
    }
}

// Start real-time updates
function startRealTimeUpdates() {
    setInterval(() => {
        // Simulate small changes in consultation numbers
        const currentConsultations = parseInt(document.getElementById('monthlyConsultations').textContent.replace(/\./g, ''));
        const change = Math.floor(Math.random() * 20) - 10; // Random change between -10 and +10
        const newConsultations = Math.max(0, currentConsultations + change);
        
        document.getElementById('monthlyConsultations').textContent = formatNumber(newConsultations);
        
        // Update progress bars randomly
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const currentWidth = parseInt(bar.style.width);
            const change = Math.floor(Math.random() * 6) - 3; // Random change between -3 and +3
            const newWidth = Math.max(0, Math.min(100, currentWidth + change));
            bar.style.width = newWidth + '%';
            
            // Update corresponding value
            const indicator = bar.closest('.indicator');
            if (indicator) {
                const valueElement = indicator.querySelector('.indicator-value');
                if (valueElement) {
                    valueElement.textContent = newWidth + '%';
                }
            }
        });
    }, 30000); // Update every 30 seconds
}

// Export functions for global access
window.refreshData = refreshData; 