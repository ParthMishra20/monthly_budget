// src/api.js
import axios from 'axios';

// Define the base URL for the backend (assuming backend is running on port 5001)
const API_URL = 'http://localhost:5001/api/auth';

// Function to handle user signup
export const signup = async (userData) => {
  try {
    const response = await axios.post(`${API_URL}/signup`, userData);
    return response.data;
  } catch (error) {
    console.error('Error during signup:', error);
    throw error;
  }
};

// Function to handle user login
export const login = async (userData) => {
  try {
    const response = await axios.post(`${API_URL}/login`, userData);
    return response.data;
  } catch (error) {
    console.error('Error during login:', error);
    throw error;
  }
};
