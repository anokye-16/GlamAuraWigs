/**
 * GlamAura Admin Core Script
 * Handles authentication, UI components, and API fetching.
 */

const GH_Admin = {
    apiBase: '../api/',
    
    init: function() {
        this.checkAuth();
        this.setupMobileMenu();
    },

    checkAuth: async function() {
        try {
            const res = await fetch(this.apiBase + 'auth_check.php');
            const data = await res.json();
            
            if (!data.authenticated) {
                window.location.href = '../login.html';
                return;
            }
            
            // Set user info in sidebar
            const nameEl = document.getElementById('adminUserName');
            if(nameEl) nameEl.innerText = data.user.name;
            
        } catch (error) {
            console.error("Auth check failed:", error);
            window.location.href = '../login.html';
        }
    },

    logout: async function() {
        try {
            await fetch(this.apiBase + 'auth_logout.php');
            window.location.href = '../login.html';
        } catch(e) {
            console.error(e);
        }
    },

    setupMobileMenu: function() {
        const toggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('ghSidebar');
        if(toggle && sidebar) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    },

    toast: function(message, type = 'success') {
        let container = document.getElementById('gh-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'gh-toast-container';
            container.className = 'gh-toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `gh-toast`;
        
        const icon = type === 'success' ? '<i class="fa-solid fa-check-circle" style="color:var(--success)"></i>' : '<i class="fa-solid fa-exclamation-circle" style="color:var(--danger)"></i>';
        
        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    openModal: function(id) {
        const modal = document.getElementById(id);
        if(modal) modal.classList.add('show');
    },

    closeModal: function(id) {
        const modal = document.getElementById(id);
        if(modal) modal.classList.remove('show');
    },

    // HTML5 Canvas Bar Chart
    drawBarChart: function(canvasId, data, labels) {
        const canvas = document.getElementById(canvasId);
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        
        ctx.clearRect(0, 0, width, height);
        
        const padding = 40;
        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;
        
        const maxData = Math.max(...data) * 1.2 || 1;
        const barWidth = (chartWidth / data.length) - 20;
        
        // Draw axes
        ctx.beginPath();
        ctx.strokeStyle = 'rgba(255,255,255,0.1)';
        ctx.moveTo(padding, padding);
        ctx.lineTo(padding, height - padding);
        ctx.lineTo(width - padding, height - padding);
        ctx.stroke();

        ctx.fillStyle = '#ff4fa3'; // Glam Pink
        ctx.textAlign = 'center';
        ctx.font = '12px Inter';

        data.forEach((val, i) => {
            const barHeight = (val / maxData) * chartHeight;
            const x = padding + 10 + i * (barWidth + 20);
            const y = height - padding - barHeight;
            
            // Draw bar
            ctx.fillRect(x, y, barWidth, barHeight);
            
            // Draw label
            ctx.fillStyle = '#888';
            ctx.fillText(labels[i], x + barWidth/2, height - padding + 20);
            
            // Draw value
            ctx.fillStyle = '#fff';
            ctx.fillText(val, x + barWidth/2, y - 10);
            
            ctx.fillStyle = '#ff4fa3'; // Reset color for next bar
        });
    },

    // HTML5 Canvas Donut Chart
    drawDonutChart: function(canvasId, data, labels, colors) {
        const canvas = document.getElementById(canvasId);
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        const cx = canvas.width / 2;
        const cy = canvas.height / 2;
        const radius = Math.min(cx, cy) - 20;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        const total = data.reduce((a, b) => a + b, 0);
        if(total === 0) return;

        let startAngle = -Math.PI / 2;

        data.forEach((val, i) => {
            const sliceAngle = (val / total) * 2 * Math.PI;
            
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, radius, startAngle, startAngle + sliceAngle);
            ctx.closePath();
            
            ctx.fillStyle = colors[i % colors.length];
            ctx.fill();
            
            startAngle += sliceAngle;
        });
        
        // Inner circle to make it a donut
        ctx.beginPath();
        ctx.arc(cx, cy, radius * 0.6, 0, 2 * Math.PI);
        ctx.fillStyle = '#141414'; // Match card background
        ctx.fill();
        
        // Draw total in center
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 20px Inter';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, cx, cy - 10);
        
        ctx.fillStyle = '#888';
        ctx.font = '12px Inter';
        ctx.fillText('Total', cx, cy + 15);
    }
};
