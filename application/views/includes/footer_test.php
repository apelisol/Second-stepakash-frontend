import React, { useState } from 'react';
import { ArrowUpRight, ArrowDownLeft, Plus, Eye, EyeOff, CreditCard, History, Settings, Bell, TrendingUp, RefreshCw, Smartphone, DollarSign } from 'lucide-react';

const StepakashWallet = () => {
  const [showBalance, setShowBalance] = useState(true);
  const [activeTab, setActiveTab] = useState('dashboard');
  const [selectedCurrency, setSelectedCurrency] = useState('KES');
  const [amount, setAmount] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');

  // Mock data
  const balances = {
    KES: 45750.50,
    USD: 342.75
  };

  const exchangeRate = 133.45; // KES per USD

  const transactions = [
    { id: 1, type: 'deposit', amount: 5000, currency: 'KES', method: 'M-Pesa', status: 'completed', date: '2024-06-26', time: '14:30' },
    { id: 2, type: 'withdraw', amount: 2500, currency: 'KES', method: 'M-Pesa', status: 'completed', date: '2024-06-25', time: '09:15' },
    { id: 3, type: 'transfer', amount: 150, currency: 'USD', method: 'Deriv', status: 'completed', date: '2024-06-24', time: '16:45' },
    { id: 4, type: 'deposit', amount: 10000, currency: 'KES', method: 'M-Pesa', status: 'completed', date: '2024-06-23', time: '11:20' },
    { id: 5, type: 'withdraw', amount: 75, currency: 'USD', method: 'Bank', status: 'pending', date: '2024-06-22', time: '13:10' }
  ];

  const convertCurrency = (amount, fromCurrency, toCurrency) => {
    if (fromCurrency === toCurrency) return amount;
    if (fromCurrency === 'KES' && toCurrency === 'USD') {
      return amount / exchangeRate;
    }
    if (fromCurrency === 'USD' && toCurrency === 'KES') {
      return amount * exchangeRate;
    }
    return amount;
  };

  const formatCurrency = (amount, currency) => {
    return new Intl.NumberFormat('en-KE', {
      style: 'currency',
      currency: currency,
      minimumFractionDigits: 2
    }).format(amount);
  };

  const Dashboard = () => (
    <div className="space-y-6">
      {/* Balance Card */}
      <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-lg">
        <div className="flex justify-between items-start mb-4">
          <div>
            <p className="text-blue-100 text-sm">Total Balance</p>
            <div className="flex items-center gap-2">
              {showBalance ? (
                <h2 className="text-3xl font-bold">{formatCurrency(balances[selectedCurrency], selectedCurrency)}</h2>
              ) : (
                <h2 className="text-3xl font-bold">••••••</h2>
              )}
              <button onClick={() => setShowBalance(!showBalance)} className="text-blue-200 hover:text-white">
                {showBalance ? <EyeOff size={20} /> : <Eye size={20} />}
              </button>
            </div>
          </div>
          <select 
            value={selectedCurrency} 
            onChange={(e) => setSelectedCurrency(e.target.value)}
            className="bg-blue-700 border border-blue-500 rounded-lg px-3 py-1 text-sm"
          >
            <option value="KES">KES</option>
            <option value="USD">USD</option>
          </select>
        </div>
        
        <div className="flex justify-between text-sm text-blue-100">
          <span>KES {formatCurrency(balances.KES, 'KES').replace('KES', '')}</span>
          <span>USD {formatCurrency(balances.USD, 'USD').replace('USD', '')}</span>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="grid grid-cols-2 gap-4">
        <button 
          onClick={() => setActiveTab('deposit')}
          className="bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 hover:bg-green-100 transition-colors"
        >
          <div className="bg-green-500 p-2 rounded-lg">
            <ArrowDownLeft className="text-white" size={20} />
          </div>
          <div className="text-left">
            <p className="font-semibold text-gray-800">Deposit</p>
            <p className="text-sm text-gray-600">Add funds via M-Pesa</p>
          </div>
        </button>

        <button 
          onClick={() => setActiveTab('withdraw')}
          className="bg-blue-50 border border-blue-200 p-4 rounded-xl flex items-center gap-3 hover:bg-blue-100 transition-colors"
        >
          <div className="bg-blue-500 p-2 rounded-lg">
            <ArrowUpRight className="text-white" size={20} />
          </div>
          <div className="text-left">
            <p className="font-semibold text-gray-800">Withdraw</p>
            <p className="text-sm text-gray-600">Send to M-Pesa</p>
          </div>
        </button>
      </div>

      {/* Currency Converter */}
      <div className="bg-white p-4 rounded-xl border border-gray-200">
        <div className="flex items-center gap-2 mb-3">
          <RefreshCw size={18} className="text-gray-600" />
          <h3 className="font-semibold text-gray-800">Currency Converter</h3>
        </div>
        <div className="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
          <span className="text-sm text-gray-600">1 USD =</span>
          <span className="font-semibold text-gray-800">KES {exchangeRate.toFixed(2)}</span>
        </div>
      </div>

      {/* Recent Transactions */}
      <div className="bg-white rounded-xl border border-gray-200">
        <div className="p-4 border-b border-gray-100 flex justify-between items-center">
          <h3 className="font-semibold text-gray-800">Recent Transactions</h3>
          <button 
            onClick={() => setActiveTab('history')}
            className="text-blue-600 text-sm hover:text-blue-700"
          >
            View All
          </button>
        </div>
        <div className="divide-y divide-gray-100">
          {transactions.slice(0, 3).map(tx => (
            <div key={tx.id} className="p-4 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className={`p-2 rounded-lg ${
                  tx.type === 'deposit' ? 'bg-green-100 text-green-600' :
                  tx.type === 'withdraw' ? 'bg-blue-100 text-blue-600' :
                  'bg-purple-100 text-purple-600'
                }`}>
                  {tx.type === 'deposit' ? <ArrowDownLeft size={16} /> :
                   tx.type === 'withdraw' ? <ArrowUpRight size={16} /> :
                   <TrendingUp size={16} />}
                </div>
                <div>
                  <p className="font-medium text-gray-800 capitalize">{tx.type}</p>
                  <p className="text-sm text-gray-600">{tx.method}</p>
                </div>
              </div>
              <div className="text-right">
                <p className="font-semibold text-gray-800">
                  {formatCurrency(tx.amount, tx.currency)}
                </p>
                <p className="text-sm text-gray-600">{tx.time}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );

  const DepositScreen = () => (
    <div className="space-y-6">
      <div className="text-center">
        <div className="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <ArrowDownLeft className="text-green-600" size={24} />
        </div>
        <h2 className="text-2xl font-bold text-gray-800">Deposit Funds</h2>
        <p className="text-gray-600">Add money to your Stepakash wallet via M-Pesa</p>
      </div>

      <div className="bg-white p-6 rounded-xl border border-gray-200 space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">Amount (KES)</label>
          <input
            type="number"
            value={amount}
            onChange={(e) => setAmount(e.target.value)}
            placeholder="Enter amount"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">M-Pesa Number</label>
          <input
            type="tel"
            value={phoneNumber}
            onChange={(e) => setPhoneNumber(e.target.value)}
            placeholder="0712345678"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        {amount && (
          <div className="bg-blue-50 p-4 rounded-lg">
            <div className="flex justify-between text-sm">
              <span>Amount:</span>
              <span>KES {parseFloat(amount || 0).toLocaleString()}</span>
            </div>
            <div className="flex justify-between text-sm">
              <span>USD Equivalent:</span>
              <span>USD {(parseFloat(amount || 0) / exchangeRate).toFixed(2)}</span>
            </div>
          </div>
        )}

        <button className="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
          Initiate M-Pesa Deposit
        </button>
      </div>

      <div className="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
        <div className="flex gap-3">
          <Smartphone className="text-yellow-600 flex-shrink-0" size={20} />
          <div>
            <p className="text-sm font-medium text-yellow-800">How it works:</p>
            <p className="text-sm text-yellow-700">
              You'll receive an M-Pesa prompt on your phone. Enter your PIN to complete the transaction.
            </p>
          </div>
        </div>
      </div>
    </div>
  );

  const WithdrawScreen = () => (
    <div className="space-y-6">
      <div className="text-center">
        <div className="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <ArrowUpRight className="text-blue-600" size={24} />
        </div>
        <h2 className="text-2xl font-bold text-gray-800">Withdraw Funds</h2>
        <p className="text-gray-600">Send money from your wallet to M-Pesa</p>
      </div>

      <div className="bg-white p-6 rounded-xl border border-gray-200 space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">Amount (KES)</label>
          <input
            type="number"
            value={amount}
            onChange={(e) => setAmount(e.target.value)}
            placeholder="Enter amount"
            max={balances.KES}
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
          <p className="text-sm text-gray-500 mt-1">Available: KES {balances.KES.toLocaleString()}</p>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">M-Pesa Number</label>
          <input
            type="tel"
            value={phoneNumber}
            onChange={(e) => setPhoneNumber(e.target.value)}
            placeholder="0712345678"
            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        {amount && parseFloat(amount) > 0 && (
          <div className="bg-blue-50 p-4 rounded-lg">
            <div className="flex justify-between text-sm">
              <span>Amount:</span>
              <span>KES {parseFloat(amount || 0).toLocaleString()}</span>
            </div>
            <div className="flex justify-between text-sm text-gray-600">
              <span>Transaction Fee:</span>
              <span>KES 10</span>
            </div>
            <div className="flex justify-between text-sm font-medium border-t pt-2 mt-2">
              <span>Total Deducted:</span>
              <span>KES {(parseFloat(amount || 0) + 10).toLocaleString()}</span>
            </div>
          </div>
        )}

        <button 
          disabled={!amount || parseFloat(amount) > balances.KES}
          className="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:bg-gray-300"
        >
          Send to M-Pesa
        </button>
      </div>
    </div>
  );

  const TransactionHistory = () => (
    <div className="space-y-4">
      <div className="flex justify-between items-center">
        <h2 className="text-2xl font-bold text-gray-800">Transaction History</h2>
        <select className="border border-gray-300 rounded-lg px-3 py-2 text-sm">
          <option>All Types</option>
          <option>Deposits</option>
          <option>Withdrawals</option>
          <option>Transfers</option>
        </select>
      </div>

      <div className="space-y-2">
        {transactions.map(tx => (
          <div key={tx.id} className="bg-white p-4 rounded-lg border border-gray-200">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className={`p-2 rounded-lg ${
                  tx.type === 'deposit' ? 'bg-green-100 text-green-600' :
                  tx.type === 'withdraw' ? 'bg-blue-100 text-blue-600' :
                  'bg-purple-100 text-purple-600'
                }`}>
                  {tx.type === 'deposit' ? <ArrowDownLeft size={16} /> :
                   tx.type === 'withdraw' ? <ArrowUpRight size={16} /> :
                   <TrendingUp size={16} />}
                </div>
                <div>
                  <p className="font-medium text-gray-800 capitalize">{tx.type}</p>
                  <p className="text-sm text-gray-600">{tx.method} • {tx.date} {tx.time}</p>
                </div>
              </div>
              <div className="text-right">
                <p className="font-semibold text-gray-800">
                  {formatCurrency(tx.amount, tx.currency)}
                </p>
                <span className={`text-xs px-2 py-1 rounded-full ${
                  tx.status === 'completed' ? 'bg-green-100 text-green-800' :
                  tx.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                }`}>
                  {tx.status}
                </span>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );

  const renderScreen = () => {
    switch(activeTab) {
      case 'dashboard': return <Dashboard />;
      case 'deposit': return <DepositScreen />;
      case 'withdraw': return <WithdrawScreen />;
      case 'history': return <TransactionHistory />;
      default: return <Dashboard />;
    }
  };

  return (
    <div className="max-w-md mx-auto bg-gray-50 min-h-screen">
      {/* Header */}
      <div className="bg-white px-4 py-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="bg-blue-600 p-2 rounded-lg">
              <CreditCard className="text-white" size={24} />
            </div>
            <div>
              <h1 className="text-xl font-bold text-gray-800">Stepakash</h1>
              <p className="text-sm text-gray-600">Deriv Trading Wallet</p>
            </div>
          </div>
          <Bell className="text-gray-600" size={20} />
        </div>
      </div>

      {/* Main Content */}
      <div className="p-4 pb-20">
        {renderScreen()}
      </div>

      {/* Bottom Navigation */}
      <div className="fixed bottom-0 left-1/2 transform -translate-x-1/2 w-full max-w-md bg-white border-t border-gray-200">
        <div className="grid grid-cols-4 gap-1">
          {[
            { id: 'dashboard', icon: CreditCard, label: 'Home' },
            { id: 'deposit', icon: Plus, label: 'Deposit' },
            { id: 'withdraw', icon: ArrowUpRight, label: 'Withdraw' },
            { id: 'history', icon: History, label: 'History' }
          ].map(item => (
            <button
              key={item.id}
              onClick={() => setActiveTab(item.id)}
              className={`p-3 flex flex-col items-center gap-1 ${
                activeTab === item.id ? 'text-blue-600' : 'text-gray-600'
              }`}
            >
              <item.icon size={20} />
              <span className="text-xs">{item.label}</span>
            </button>
          ))}
        </div>
      </div>
    </div>
  );
};

export default StepakashWallet;