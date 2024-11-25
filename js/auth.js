// Authentication Service
class AuthService {
    static TOKEN_KEY = 'auth_token';
    static USER_KEY = 'user_data';

    // Mock user database (in real app, this would be on the server)
    static users = [
        {
            id: 1,
            username: 'admin',
            // In real app, this would be hashed
            password: 'admin123',
            role: 'admin',
            name: 'Administrator'
        },
        {
            id: 2,
            username: 'doctor',
            password: 'doctor123',
            role: 'doctor',
            name: 'Dr. John Doe'
        },
        {
            id: 3,
            username: 'nurse',
            password: 'nurse123',
            role: 'nurse',
            name: 'Jane Smith'
        }
    ];

    static generateToken() {
        return Math.random().toString(36).substring(2) + Date.now().toString(36);
    }

    static async login(username, password) {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 800));

        const user = this.users.find(u => u.username === username && u.password === password);
        
        if (!user) {
            throw new Error('Invalid credentials');
        }

        // Generate auth token
        const token = this.generateToken();
        
        // Store auth data
        const userData = {
            id: user.id,
            username: user.username,
            role: user.role,
            name: user.name,
            token
        };

        // Save to session storage
        sessionStorage.setItem(this.TOKEN_KEY, token);
        sessionStorage.setItem(this.USER_KEY, JSON.stringify(userData));

        return userData;
    }

    static async register(userData) {
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 800));

        // Check if username already exists
        if (this.users.some(u => u.username === userData.username)) {
            throw new Error('Username already exists');
        }

        // In a real app, this would be handled by the server
        const newUser = {
            id: this.users.length + 1,
            username: userData.username,
            password: userData.password, // In real app, this would be hashed
            role: 'staff',
            name: userData.fullname
        };

        this.users.push(newUser);
        
        // Auto login after registration
        return this.login(userData.username, userData.password);
    }

    static logout() {
        sessionStorage.removeItem(this.TOKEN_KEY);
        sessionStorage.removeItem(this.USER_KEY);
    }

    static isAuthenticated() {
        const token = sessionStorage.getItem(this.TOKEN_KEY);
        const userData = sessionStorage.getItem(this.USER_KEY);
        return !!(token && userData);
    }

    static getCurrentUser() {
        const userData = sessionStorage.getItem(this.USER_KEY);
        return userData ? JSON.parse(userData) : null;
    }

    static hasRole(role) {
        const user = this.getCurrentUser();
        return user && user.role === role;
    }
}
