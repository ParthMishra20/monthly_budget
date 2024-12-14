const mongoose = require('mongoose');

const connectDB = async () => {
  try {
    // Get the Mongo URI from the environment variable
    const mongoURI = process.env.MONGODB_URI;

    // Check if mongoURI is available in .env
    if (!mongoURI) {
      throw new Error('Mongo URI not provided in environment variables');
    }

    // Connect to MongoDB without deprecated options
    await mongoose.connect(mongoURI);

    console.log('MongoDB connected');
  } catch (err) {
    console.error('Error connecting to MongoDB:', err.message);
    process.exit(1); // Exit the process if connection fails
  }
};

module.exports = connectDB;
