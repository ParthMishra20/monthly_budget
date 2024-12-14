import React, { useState } from 'react';

const Dashboard = () => {
  const [income, setIncome] = useState('');
  const [subscriptions, setSubscriptions] = useState('');
  const [expenses, setExpenses] = useState([]);
  const [expenseDesc, setExpenseDesc] = useState('');
  const [expenseTag, setExpenseTag] = useState('');

  const handleAddExpense = () => {
    if (expenseDesc && expenseTag) {
      setExpenses([...expenses, { description: expenseDesc, tag: expenseTag }]);
      setExpenseDesc('');
      setExpenseTag('');
    }
  };

  const handleExportCSV = () => {
    const csvContent = 'data:text/csv;charset=utf-8,Description,Tag\n' +
      expenses.map(e => `${e.description},${e.tag}`).join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'expenses.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return (
    <div className="dashboard-container">
      <h1>Budget Buddy</h1>
      <div className="input-section">
        <div className="input-group">
          <label>Income/Salary:</label>
          <input
            type="number"
            placeholder="Enter your income"
            value={income}
            onChange={(e) => setIncome(e.target.value)}
          />
        </div>
        <div className="input-group">
          <label>Monthly Subscriptions:</label>
          <input
            type="text"
            placeholder="Enter subscriptions"
            value={subscriptions}
            onChange={(e) => setSubscriptions(e.target.value)}
          />
        </div>
        <button>Track</button>
      </div>
      <div className="expenses-section">
        <h2>Day-to-Day Expenses</h2>
        <div className="expense-inputs">
          <input
            type="text"
            placeholder="Description"
            value={expenseDesc}
            onChange={(e) => setExpenseDesc(e.target.value)}
          />
          <select
            value={expenseTag}
            onChange={(e) => setExpenseTag(e.target.value)}
          >
            <option value="">Select Tag</option>
            <option value="Food">Food</option>
            <option value="Travel">Travel</option>
            <option value="Needs">Needs</option>
            <option value="Wants">Wants</option>
          </select>
          <button onClick={handleAddExpense}>Add Expense</button>
        </div>
        <ul className="expense-list">
          {expenses.map((expense, index) => (
            <li key={index}>
              {expense.description} - <strong>{expense.tag}</strong>
            </li>
          ))}
        </ul>
        <button onClick={handleExportCSV}>Export to CSV</button>
      </div>
    </div>
  );
};

export default Dashboard;
