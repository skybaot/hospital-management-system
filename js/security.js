// Security Utilities
class SecurityUtils {
    // Password encryption (for demo purposes - in real app use proper encryption)
    static async hashPassword(password) {
        const encoder = new TextEncoder();
        const data = encoder.encode(password);
        const hash = await crypto.subtle.digest('SHA-256', data);
        return Array.from(new Uint8Array(hash))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
    }

    // Generate secure random token
    static generateSecureToken(length = 32) {
        const array = new Uint8Array(length);
        crypto.getRandomValues(array);
        return Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
    }

    // Validate password strength
    static validatePassword(password) {
        const requirements = {
            minLength: password.length >= 8,
            hasUpper: /[A-Z]/.test(password),
            hasLower: /[a-z]/.test(password),
            hasNumber: /\d/.test(password),
            hasSpecial: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        const score = Object.values(requirements).filter(Boolean).length;
        const messages = [];

        if (!requirements.minLength) messages.push('Password must be at least 8 characters long');
        if (!requirements.hasUpper) messages.push('Include at least one uppercase letter');
        if (!requirements.hasLower) messages.push('Include at least one lowercase letter');
        if (!requirements.hasNumber) messages.push('Include at least one number');
        if (!requirements.hasSpecial) messages.push('Include at least one special character');

        return {
            score,
            isValid: score >= 4,
            messages,
            requirements
        };
    }

    // Sanitize input to prevent XSS
    static sanitizeInput(input) {
        const div = document.createElement('div');
        div.textContent = input;
        return div.innerHTML;
    }

    // Rate limiting for login attempts
    static loginAttempts = new Map();
    
    static checkLoginAttempts(username) {
        const attempts = this.loginAttempts.get(username) || { count: 0, timestamp: Date.now() };
        const now = Date.now();
        const timeWindow = 15 * 60 * 1000; // 15 minutes

        // Reset attempts if time window has passed
        if (now - attempts.timestamp > timeWindow) {
            attempts.count = 0;
            attempts.timestamp = now;
        }

        // Check if too many attempts
        if (attempts.count >= 5) {
            const remainingTime = Math.ceil((timeWindow - (now - attempts.timestamp)) / 1000 / 60);
            throw new Error(`Too many login attempts. Please try again in ${remainingTime} minutes.`);
        }

        // Increment attempt count
        attempts.count++;
        attempts.timestamp = now;
        this.loginAttempts.set(username, attempts);
    }

    // Session timeout checker
    static startSessionTimer(timeout = 30) { // timeout in minutes
        const timeoutMs = timeout * 60 * 1000;
        let lastActivity = Date.now();

        // Update last activity time on user interaction
        document.addEventListener('mousemove', () => lastActivity = Date.now());
        document.addEventListener('keypress', () => lastActivity = Date.now());
        document.addEventListener('click', () => lastActivity = Date.now());

        // Check session every minute
        setInterval(() => {
            if (Date.now() - lastActivity > timeoutMs) {
                Swal.fire({
                    title: 'Session Expired',
                    text: 'Your session has expired due to inactivity.',
                    icon: 'warning',
                    confirmButtonText: 'Login Again'
                }).then(() => {
                    AuthService.logout();
                    window.location.href = 'login.html';
                });
            }
        }, 60000);
    }

    // Activity Logger
    static async logActivity(action, details) {
        const user = AuthService.getCurrentUser();
        const timestamp = new Date().toISOString();
        const logEntry = {
            timestamp,
            user: user ? user.username : 'anonymous',
            action,
            details,
            userAgent: navigator.userAgent,
            ip: 'client-side' // In real app, this would be set server-side
        };

        // In a real app, this would be sent to a server
        console.log('Activity Log:', logEntry);
        
        // Store in local storage for demo
        const logs = JSON.parse(localStorage.getItem('activityLogs') || '[]');
        logs.push(logEntry);
        localStorage.setItem('activityLogs', JSON.stringify(logs));
    }
}
