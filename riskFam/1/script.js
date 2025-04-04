// Initial data
const factors = {
  socioeconomic: { 
    name: "Socioeconomic Status", 
    value: 60, 
    weight: 0.20,
    description: "Directly impacts access to essential goods and services."
  },
  familyStructure: { 
    name: "Family Structure", 
    value: 45, 
    weight: 0.15,
    description: "Influences social support, functionality, and household stability."
  },
  healthConditions: { 
    name: "Health Conditions", 
    value: 70, 
    weight: 0.20,
    description: "Determines quality of life and access to medical treatment."
  },
  socialVulnerability: { 
    name: "Social Vulnerability", 
    value: 50, 
    weight: 0.15,
    description: "Considers factors such as violence, displacement, and social exclusion."
  },
  accessToHealth: { 
    name: "Access to Health Services", 
    value: 30, 
    weight: 0.10,
    description: "Key to disease prevention and care."
  },
  livingEnvironment: { 
    name: "Living Environment", 
    value: 65, 
    weight: 0.10,
    description: "Evaluates housing conditions and their impact on health."
  },
  demographics: { 
    name: "Demographic Characteristics", 
    value: 40, 
    weight: 0.10,
    description: "Includes age, gender, and other variables that influence risk exposure."
  }
};

// DOM elements
const generateBtn = document.getElementById('generateBtn');
const riskMeterNeedle = document.getElementById('riskMeterNeedle');
const riskMeterValue = document.getElementById('riskMeterValue');
const riskLevel = document.getElementById('riskLevel');
const totalRiskText = document.getElementById('totalRiskText');
const riskFactorsContainer = document.getElementById('riskFactors');
const themeSwitch = document.getElementById('theme-switch');

// Chart.js instance
let riskChart;

// Initialize the application
function initApp() {
  // Check for saved theme preference
  if (localStorage.getItem('darkTheme') === 'true') {
    document.body.classList.add('dark-theme');
    themeSwitch.checked = true;
  }
  
  // Render initial risk factors
  renderRiskFactors();
  
  // Calculate and display total risk
  calculateTotalRisk();
  
  // Initialize chart
  initChart();
  
  // Add event listeners
  generateBtn.addEventListener('click', generateRandomData);
  themeSwitch.addEventListener('change', toggleTheme);
}

// Toggle dark/light theme
function toggleTheme() {
  document.body.classList.toggle('dark-theme');
  localStorage.setItem('darkTheme', document.body.classList.contains('dark-theme'));
  
  // Update chart colors
  updateChartColors();
}

// Generate random data for all factors
function generateRandomData() {
  // Add pulse animation to button
  generateBtn.classList.add('pulse');
  
  // Remove animation after it completes
  setTimeout(() => {
    generateBtn.classList.remove('pulse');
  }, 2000);
  
  // Generate new random values
  Object.keys(factors).forEach(key => {
    factors[key].value = Math.floor(Math.random() * 100);
  });
  
  // Update UI
  renderRiskFactors();
  calculateTotalRisk();
  updateChart();
}

// Calculate total risk based on all factors and their weights
function calculateTotalRisk() {
  const totalRisk = Object.keys(factors).reduce((sum, key) => {
    return sum + (factors[key].value * factors[key].weight);
  }, 0);
  
  // Update risk meter
  const needleRotation = calculateNeedleRotation(totalRisk);
  riskMeterNeedle.style.transform = `translateX(-50%) rotate(${needleRotation}deg)`;
  riskMeterValue.textContent = `${totalRisk.toFixed(1)}%`;
  totalRiskText.textContent = `${totalRisk.toFixed(1)}%`;
  
  // Update risk level
  let riskLevelText = "";
  riskLevel.classList.remove('low', 'medium', 'high');
  
  if (totalRisk >= 71) {
    riskLevelText = "High Risk";
    riskLevel.classList.add('high');
  } else if (totalRisk >= 41) {
    riskLevelText = "Medium Risk";
    riskLevel.classList.add('medium');
  } else {
    riskLevelText = "Low Risk";
    riskLevel.classList.add('low');
  }
  
  riskLevel.textContent = riskLevelText;
}

// Calculate needle rotation angle based on risk value
function calculateNeedleRotation(riskValue) {
  // Map risk value (0-100) to rotation angle (-90 to 90 degrees)
  return -90 + (riskValue * 180 / 100);
}

// Render risk factors
function renderRiskFactors() {
  riskFactorsContainer.innerHTML = '';
  
  Object.keys(factors).forEach(key => {
    const factor = factors[key];
    const riskClass = getRiskClass(factor.value);
    
    const factorElement = document.createElement('div');
    factorElement.className = 'risk-factor';
    factorElement.innerHTML = `
      <div class="risk-factor-header">
        <div class="risk-factor-name">
          ${factor.name}
          <span class="risk-factor-badge ${riskClass}">${getRiskLevelText(factor.value)}</span>
        </div>
        <div>
          <span class="risk-factor-value">${factor.value}%</span>
          <span class="risk-factor-weight">(${factor.weight * 100}%)</span>
        </div>
      </div>
      <div class="risk-factor-bar">
        <div class="risk-factor-progress ${riskClass}" style="width: ${factor.value}%"></div>
      </div>
      <div class="risk-factor-details">
        <span>${factor.description}</span>
        <span>Contribution: ${(factor.value * factor.weight).toFixed(1)}%</span>
      </div>
    `;
    
    riskFactorsContainer.appendChild(factorElement);
  });
}

// Get risk class based on value
function getRiskClass(value) {
  if (value >= 71) return 'high';
  if (value >= 41) return 'medium';
  return 'low';
}

// Get risk level text based on value
function getRiskLevelText(value) {
  if (value >= 71) return 'High';
  if (value >= 41) return 'Medium';
  return 'Low';
}

// Initialize Chart.js chart
function initChart() {
  const ctx = document.getElementById('riskChart').getContext('2d');
  
  // Prepare data for the chart
  const chartData = {
    labels: Object.values(factors).map(factor => factor.name),
    datasets: [{
      label: 'Risk Factors',
      data: Object.values(factors).map(factor => factor.value),
      backgroundColor: Object.values(factors).map(factor => getChartColor(factor.value, 0.7)),
      borderColor: Object.values(factors).map(factor => getChartColor(factor.value, 1)),
      borderWidth: 1
    }]
  };
  
  // Chart configuration
  const chartConfig = {
    type: 'radar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        r: {
          beginAtZero: true,
          max: 100,
          ticks: {
            stepSize: 20,
            backdropColor: 'transparent'
          },
          grid: {
            color: getComputedStyle(document.body).getPropertyValue('--color-border')
          },
          angleLines: {
            color: getComputedStyle(document.body).getPropertyValue('--color-border')
          },
          pointLabels: {
            font: {
              size: 12
            },
            color: getComputedStyle(document.body).getPropertyValue('--color-text')
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const index = context.dataIndex;
              const factor = Object.values(factors)[index];
              return [
                `${label}: ${value}%`,
                `Weight: ${factor.weight * 100}%`,
                `Contribution: ${(value * factor.weight).toFixed(1)}%`
              ];
            }
          }
        }
      }
    }
  };
  
  // Create chart
  riskChart = new Chart(ctx, chartConfig);
}

// Update chart with new data
function updateChart() {
  riskChart.data.datasets[0].data = Object.values(factors).map(factor => factor.value);
  riskChart.data.datasets[0].backgroundColor = Object.values(factors).map(factor => getChartColor(factor.value, 0.7));
  riskChart.data.datasets[0].borderColor = Object.values(factors).map(factor => getChartColor(factor.value, 1));
  riskChart.update();
}

// Update chart colors when theme changes
function updateChartColors() {
  if (riskChart) {
    riskChart.options.scales.r.grid.color = getComputedStyle(document.body).getPropertyValue('--color-border');
    riskChart.options.scales.r.angleLines.color = getComputedStyle(document.body).getPropertyValue('--color-border');
    riskChart.options.scales.r.pointLabels.color = getComputedStyle(document.body).getPropertyValue('--color-text');
    riskChart.update();
  }
}

// Get chart color based on risk value
function getChartColor(value, alpha) {
  if (value >= 71) {
    return `rgba(239, 68, 68, ${alpha})`; // red
  } else if (value >= 41) {
    return `rgba(245, 158, 11, ${alpha})`; // amber
  } else {
    return `rgba(16, 185, 129, ${alpha})`; // green
  }
}

// Calculate socioeconomic risk
function calculateSocioeconomicRisk(stratum, income) {
  // Stratum score: Stratum 1 = 6 points, Stratum 6 = 1 point
  const stratumScore = 7 - stratum;
  
  // Income score: 1 = 1 point, 2 = 2 points, 3 = 3 points
  const incomeScore = income;
  
  // Total score
  const totalScore = stratumScore + incomeScore;
  
  // Calculate percentage: SE = ((Score - 2) / 7) * 100
  const percentage = ((totalScore - 2) / 7) * 100;
  
  return {
    stratumScore,
    incomeScore,
    totalScore,
    percentage,
    riskLevel: percentage >= 70 ? "High Risk" : percentage >= 40 ? "Medium Risk" : "Low Risk"
  };
}

// Calculate housing risk
function calculateHousingRisk(zone, geographicRisks) {
  // Zone score: Urban = 1, Rural = 2
  const zoneScore = zone;
  
  // Geographic risks score: sum of all risk values
  const risksScore = geographicRisks.reduce((sum, risk) => sum + risk.value, 0);
  
  // Total score
  const totalScore = zoneScore + risksScore;
  
  // Maximum possible score (for percentage calculation)
  const maxScore = 2 + 27; // Rural (2) + all possible risks (27)
  
  // Calculate percentage
  const percentage = (totalScore / maxScore) * 100;
  
  return {
    zoneScore,
    risksScore,
    totalScore,
    percentage,
    riskLevel: percentage >= 70 ? "High Risk" : percentage >= 40 ? "Medium Risk" : "Low Risk"
  };
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', initApp);