// src/Login.js
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { login } from './api.js'; // Import the login function from api.js

const Login = () => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();

    // Create the user data object
    const userData = { username, password };

    try {
      const data = await login(userData);  // Call the login API

      // Check for successful login response
      if (data.success) {
        setSuccess('Login successful!');
        setError('');
        navigate('/dashboard');  // Redirect to dashboard
      } else {
        setError('Invalid credentials!');
        setSuccess('');
      }
    } catch (err) {
      setError('Login failed, please try again.');
      setSuccess('');
    }
  };

  return (
    <div className="form-container">
      <h2>Login</h2>
      <form onSubmit={handleLogin}>
        <input
          type="text"
          placeholder="Username"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit">Login</button>
      </form>
      {error && <p style={{ color: 'red' }}>{error}</p>}
      {success && <p style={{ color: 'green' }}>{success}</p>}
      <p>
        Don’t have an account? <a href="/signup">Sign up here</a>.
      </p>
    </div>
  );
};

export default Login;
