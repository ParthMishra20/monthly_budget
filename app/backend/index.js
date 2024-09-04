const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config();

const app = express();
const port = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());

// MongoDB connection
mongoose.connect(process.env.MONGO_URI, {
  useNewUrlParser: true,
  useUnifiedTopology: true,
});

const connection = mongoose.connection;
connection.once('open', () => {
  console.log('MongoDB database connection established successfully');
});

// Define a schema and model for storing answers
const AnswerSchema = new mongoose.Schema({
  answer: String,
});

const Answer = mongoose.model('Answer', AnswerSchema);

// Route to handle answer submission
app.post('/submit', async (req, res) => {
  const { answer } = req.body;
  const newAnswer = new Answer({ answer });
  await newAnswer.save();
  res.json('Answer saved!');
});

app.listen(port, () => {
  console.log(`Server is running on port: ${port}`);
});
