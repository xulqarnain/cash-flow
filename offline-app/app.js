/**
 * Cash Record - Offline Alpine.js App
 * Complete data management with IndexedDB
 */

// Main Alpine.js Component
function cashApp() {
    return {
        // State
        currentView: 'dashboard',
        selectedPerson: null,
        showPersonForm: false,
        showTransactionForm: false,
        showCategoryForm: false,
        showSuccess: false,
        successMessage: '',
        dateFilter: 'all', // 'all', 'today', 'week', 'month', 'custom'
        searchQuery: '',
        chartView: '7days', // '7days', '30days', 'year'

        // Form Data
        personForm: {
            id: null,
            name: '',
            type: 'give',
            amount: '',
            date: new Date().toISOString().split('T')[0],
            category: '',
            description: ''
        },

        transactionForm: {
            id: null,
            person_id: null,
            type: 'give',
            amount: '',
            date: new Date().toISOString().split('T')[0],
            category: '',
            description: ''
        },

        // Data
        people: [],
        transactions: [],
        categories: ['Food', 'Transport', 'Shopping', 'Bills', 'Entertainment', 'Health', 'Other'],
        customCategories: [],

        // Database
        db: null,

        // Initialize
        async init() {
            console.log('Initializing Cash Record App...');
            await this.initDatabase();
            await this.loadCustomCategories();
            await this.loadData();
            console.log('App initialized successfully');
        },

        // Database Initialization
        async initDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('CashRecordDB', 2);

                request.onerror = () => {
                    console.error('Database error:', request.error);
                    reject(request.error);
                };

                request.onsuccess = () => {
                    this.db = request.result;
                    console.log('Database opened successfully');
                    resolve();
                };

                request.onupgradeneeded = (event) => {
                    const db = event.target.result;

                    // Create people store
                    if (!db.objectStoreNames.contains('people')) {
                        const peopleStore = db.createObjectStore('people', { keyPath: 'id', autoIncrement: true });
                        peopleStore.createIndex('name', 'name', { unique: false });
                        peopleStore.createIndex('created_at', 'created_at', { unique: false });
                    }

                    // Create transactions store
                    if (!db.objectStoreNames.contains('transactions')) {
                        const txStore = db.createObjectStore('transactions', { keyPath: 'id', autoIncrement: true });
                        txStore.createIndex('person_id', 'person_id', { unique: false });
                        txStore.createIndex('date', 'date', { unique: false });
                        txStore.createIndex('type', 'type', { unique: false });
                        txStore.createIndex('category', 'category', { unique: false });
                    }

                    // Create categories store
                    if (!db.objectStoreNames.contains('categories')) {
                        db.createObjectStore('categories', { keyPath: 'id', autoIncrement: true });
                    }

                    console.log('Database schema created');
                };
            });
        },

        // Load all data
        async loadData() {
            await this.loadPeople();
            await this.loadTransactions();
        },

        // CRUD - People
        async loadPeople() {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['people'], 'readonly');
                const store = tx.objectStore('people');
                const request = store.getAll();

                request.onsuccess = () => {
                    this.people = request.result.sort((a, b) => b.id - a.id);
                    console.log('Loaded people:', this.people.length);
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        async addPerson(person) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['people'], 'readwrite');
                const store = tx.objectStore('people');
                const request = store.add(person);

                request.onsuccess = () => {
                    console.log('Person added with ID:', request.result);
                    resolve(request.result);
                };

                request.onerror = () => reject(request.error);
            });
        },

        async deletePerson(id) {
            if (!confirm('Delete this person and all their transactions?')) return;

            if (!this.db) return;

            try {
                await this.deleteTransactionsByPerson(id);

                await new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['people'], 'readwrite');
                    const store = tx.objectStore('people');
                    const request = store.delete(id);

                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                if (this.selectedPerson?.id === id) {
                    this.selectedPerson = null;
                }

                await this.loadData();
                this.showSuccessMessage('Person deleted successfully!');
            } catch (error) {
                console.error('Error deleting person:', error);
                alert('Failed to delete person');
            }
        },

        // CRUD - Transactions
        async loadTransactions() {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readonly');
                const store = tx.objectStore('transactions');
                const request = store.getAll();

                request.onsuccess = () => {
                    this.transactions = request.result.sort((a, b) => {
                        return new Date(b.date) - new Date(a.date);
                    });
                    console.log('Loaded transactions:', this.transactions.length);
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        async addTransaction(transaction) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readwrite');
                const store = tx.objectStore('transactions');
                const request = store.add(transaction);

                request.onsuccess = () => {
                    console.log('Transaction added with ID:', request.result);
                    resolve(request.result);
                };

                request.onerror = () => reject(request.error);
            });
        },

        async updateTransaction(id, transaction) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readwrite');
                const store = tx.objectStore('transactions');
                transaction.id = id;
                const request = store.put(transaction);

                request.onsuccess = () => resolve();
                request.onerror = () => reject(request.error);
            });
        },

        async getTransaction(id) {
            if (!this.db) return null;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readonly');
                const store = tx.objectStore('transactions');
                const request = store.get(id);

                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        },

        async deleteTransaction(id) {
            if (!confirm('Delete this transaction?')) return;

            if (!this.db) return;

            try {
                await new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['transactions'], 'readwrite');
                    const store = tx.objectStore('transactions');
                    const request = store.delete(id);

                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                await this.loadTransactions();
                this.showSuccessMessage('Transaction deleted!');
            } catch (error) {
                console.error('Error deleting transaction:', error);
                alert('Failed to delete transaction');
            }
        },

        async deleteTransactionsByPerson(personId) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readwrite');
                const store = tx.objectStore('transactions');
                const index = store.index('person_id');
                const request = index.openCursor(IDBKeyRange.only(personId));

                request.onsuccess = (e) => {
                    const cursor = e.target.result;
                    if (cursor) {
                        cursor.delete();
                        cursor.continue();
                    } else {
                        resolve();
                    }
                };

                request.onerror = () => reject(request.error);
            });
        },

        // Categories
        async loadCustomCategories() {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['categories'], 'readonly');
                const store = tx.objectStore('categories');
                const request = store.getAll();

                request.onsuccess = () => {
                    this.customCategories = request.result.map(c => c.name);
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        async addCategory(name) {
            if (!this.db || !name.trim()) return;

            const category = { name: name.trim() };

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['categories'], 'readwrite');
                const store = tx.objectStore('categories');
                const request = store.add(category);

                request.onsuccess = () => {
                    this.customCategories.push(name.trim());
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        getAllCategories() {
            return [...this.categories, ...this.customCategories];
        },

        // Form handling
        async savePerson() {
            if (!this.personForm.name.trim()) {
                alert('Please enter a name');
                return;
            }

            try {
                const person = {
                    name: this.personForm.name.trim(),
                    created_at: new Date().toISOString()
                };

                const personId = await this.addPerson(person);

                // Add initial transaction if amount provided
                if (this.personForm.amount && parseFloat(this.personForm.amount) > 0) {
                    await this.addTransaction({
                        person_id: personId,
                        type: this.personForm.type,
                        amount: parseFloat(this.personForm.amount),
                        date: this.personForm.date,
                        category: this.personForm.category || 'Other',
                        description: this.personForm.description || '',
                        created_at: new Date().toISOString()
                    });
                }

                await this.loadData();
                this.showSuccessMessage('Person added successfully!');
                this.closePersonForm();
            } catch (error) {
                console.error('Error saving person:', error);
                alert('Failed to save. Please try again.');
            }
        },

        async saveTransaction() {
            if (!this.transactionForm.person_id || !this.transactionForm.amount) {
                alert('Please fill in all required fields');
                return;
            }

            try {
                const transactionData = {
                    person_id: this.transactionForm.person_id,
                    type: this.transactionForm.type,
                    amount: parseFloat(this.transactionForm.amount),
                    date: this.transactionForm.date,
                    category: this.transactionForm.category || 'Other',
                    description: this.transactionForm.description || '',
                    created_at: this.transactionForm.id ? this.transactionForm.created_at : new Date().toISOString()
                };

                if (this.transactionForm.id) {
                    // Update existing transaction
                    await this.updateTransaction(this.transactionForm.id, transactionData);
                    this.showSuccessMessage('Transaction updated!');
                } else {
                    // Add new transaction
                    await this.addTransaction(transactionData);
                    this.showSuccessMessage('Transaction added!');
                }

                await this.loadTransactions();
                this.closeTransactionForm();
            } catch (error) {
                console.error('Error saving transaction:', error);
                alert('Failed to save transaction');
            }
        },

        async editTransaction(transaction) {
            this.transactionForm = {
                id: transaction.id,
                person_id: transaction.person_id,
                type: transaction.type,
                amount: transaction.amount,
                date: transaction.date,
                category: transaction.category || '',
                description: transaction.description || '',
                created_at: transaction.created_at
            };
            this.showTransactionForm = true;
        },

        // Calculations
        getTotalInFlow() {
            return this.transactions
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getTotalOutFlow() {
            return this.transactions
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getTotalBalance() {
            return this.getTotalInFlow() - this.getTotalOutFlow();
        },

        getPersonBalance(personId) {
            const personTxs = this.transactions.filter(t => t.person_id === personId);
            const received = personTxs
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
            const given = personTxs
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
            return received - given;
        },

        getPersonName(personId) {
            const person = this.people.find(p => p.id === personId);
            return person ? person.name : 'Unknown';
        },

        getPersonTransactions(personId) {
            return this.transactions.filter(t => t.person_id === personId);
        },

        getPersonTransactionCount(personId) {
            return this.transactions.filter(t => t.person_id === personId).length;
        },

        getPersonGiveCount(personId) {
            return this.transactions.filter(t => t.person_id === personId && t.type === 'give').length;
        },

        getPersonReceiveCount(personId) {
            return this.transactions.filter(t => t.person_id === personId && t.type === 'receive').length;
        },

        getPersonGiveTotal(personId) {
            return this.transactions
                .filter(t => t.person_id === personId && t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getPersonReceiveTotal(personId) {
            return this.transactions
                .filter(t => t.person_id === personId && t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        // UI helpers
        switchView(view) {
            this.currentView = view;
            if (view === 'dashboard') {
                this.selectedPerson = null;
            }
        },

        selectPerson(person) {
            this.selectedPerson = person;
            this.currentView = 'person-detail';
        },

        openPersonForm() {
            this.personForm = {
                id: null,
                name: '',
                type: 'give',
                amount: '',
                date: new Date().toISOString().split('T')[0],
                category: '',
                description: ''
            };
            this.showPersonForm = true;
        },

        closePersonForm() {
            this.showPersonForm = false;
        },

        openTransactionForm(person) {
            this.transactionForm = {
                id: null,
                person_id: person.id,
                type: 'give',
                amount: '',
                date: new Date().toISOString().split('T')[0],
                category: '',
                description: ''
            };
            this.showTransactionForm = true;
        },

        closeTransactionForm() {
            this.showTransactionForm = false;
        },

        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccess = true;
            setTimeout(() => {
                this.showSuccess = false;
                this.successMessage = '';
            }, 3000);
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        },

        isOnline() {
            return navigator.onLine;
        },

        // Filtering & Search
        getFilteredTransactions() {
            let filtered = this.transactions;

            // Date filter
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            switch(this.dateFilter) {
                case 'today':
                    filtered = filtered.filter(t => {
                        const txDate = new Date(t.date);
                        return txDate >= today;
                    });
                    break;
                case 'week':
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    filtered = filtered.filter(t => new Date(t.date) >= weekAgo);
                    break;
                case 'month':
                    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                    filtered = filtered.filter(t => new Date(t.date) >= monthStart);
                    break;
            }

            // Search filter
            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(t => {
                    const personName = this.getPersonName(t.person_id).toLowerCase();
                    const category = (t.category || '').toLowerCase();
                    const description = (t.description || '').toLowerCase();
                    return personName.includes(query) ||
                           category.includes(query) ||
                           description.includes(query);
                });
            }

            return filtered;
        },

        // Export to CSV
        exportToCSV() {
            const transactions = this.getFilteredTransactions();

            if (transactions.length === 0) {
                alert('No transactions to export');
                return;
            }

            let csv = 'Date,Person,Type,Amount,Category,Description\n';

            transactions.forEach(t => {
                const row = [
                    t.date,
                    this.getPersonName(t.person_id),
                    t.type === 'give' ? 'Given' : 'Received',
                    t.amount,
                    t.category || 'Other',
                    `"${(t.description || '').replace(/"/g, '""')}"`
                ].join(',');
                csv += row + '\n';
            });

            // Download file
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `cash-record-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            this.showSuccessMessage('Exported ' + transactions.length + ' transactions!');
        },

        // Export all data (both people and transactions)
        exportAllData() {
            if (this.transactions.length === 0) {
                alert('No data to export');
                return;
            }

            let csv = 'Date,Person,Type,Amount,Category,Description\n';

            this.transactions.forEach(t => {
                const row = [
                    t.date,
                    this.getPersonName(t.person_id),
                    t.type === 'give' ? 'Given' : 'Received',
                    t.amount,
                    t.category || 'Other',
                    `"${(t.description || '').replace(/"/g, '""')}"`
                ].join(',');
                csv += row + '\n';
            });

            // Download file
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `cash-flow-backup-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            this.showSuccessMessage(`Exported ${this.transactions.length} transactions successfully!`);
        },

        // Import data from CSV
        async importFromCSV(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = async (e) => {
                try {
                    const csv = e.target.result;
                    const lines = csv.split('\n');

                    // Skip header row
                    const dataLines = lines.slice(1).filter(line => line.trim());

                    let importedCount = 0;
                    const peopleMap = new Map();

                    // First, collect unique people
                    for (const line of dataLines) {
                        const match = line.match(/^([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),(.*)$/);
                        if (!match) continue;

                        const [, , personName] = match;
                        if (personName && !peopleMap.has(personName)) {
                            peopleMap.set(personName, null);
                        }
                    }

                    // Create people if they don't exist
                    for (const personName of peopleMap.keys()) {
                        let person = this.people.find(p => p.name === personName);

                        if (!person) {
                            // Create new person
                            const newPerson = {
                                name: personName,
                                type: 'give',
                                initial_amount: 0,
                                created_at: new Date().toISOString()
                            };
                            const id = await this.addPerson(newPerson);
                            peopleMap.set(personName, id);
                        } else {
                            peopleMap.set(personName, person.id);
                        }
                    }

                    // Reload people to get updated list
                    await this.loadPeople();

                    // Now import transactions
                    for (const line of dataLines) {
                        const match = line.match(/^([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),(.*)$/);
                        if (!match) continue;

                        const [, date, personName, type, amount, category, description] = match;

                        const personId = peopleMap.get(personName);
                        if (!personId) continue;

                        const transaction = {
                            person_id: personId,
                            type: type.toLowerCase().includes('given') ? 'give' : 'receive',
                            amount: parseFloat(amount),
                            date: date,
                            category: category === 'Other' ? '' : category,
                            description: description.replace(/^"|"$/g, '').replace(/""/g, '"'),
                            created_at: new Date().toISOString()
                        };

                        await this.addTransaction(transaction);
                        importedCount++;
                    }

                    // Reload all data
                    await this.loadPeople();
                    await this.loadTransactions();

                    this.showSuccessMessage(`Imported ${importedCount} transactions successfully!`);

                    // Reset file input
                    event.target.value = '';
                } catch (error) {
                    console.error('Import error:', error);
                    alert('Failed to import data. Please check the CSV format.');
                    event.target.value = '';
                }
            };

            reader.readAsText(file);
        },

        // Clear all data
        async clearAllData() {
            if (!confirm('⚠️ WARNING: This will delete ALL people and transactions permanently. This action cannot be undone!\n\nAre you sure you want to continue?')) {
                return;
            }

            // Double confirmation for safety
            if (!confirm('This is your final warning! All data will be lost forever. Continue?')) {
                return;
            }

            try {
                if (!this.db) {
                    alert('Database not initialized');
                    return;
                }

                // Clear transactions
                const txTransactions = this.db.transaction(['transactions'], 'readwrite');
                const storeTransactions = txTransactions.objectStore('transactions');
                await new Promise((resolve, reject) => {
                    const request = storeTransactions.clear();
                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                // Clear people
                const txPeople = this.db.transaction(['people'], 'readwrite');
                const storePeople = txPeople.objectStore('people');
                await new Promise((resolve, reject) => {
                    const request = storePeople.clear();
                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                // Reload data
                this.people = [];
                this.transactions = [];
                this.selectedPerson = null;

                this.showSuccessMessage('All data cleared successfully!');
                this.currentView = 'dashboard';
            } catch (error) {
                console.error('Error clearing data:', error);
                alert('Failed to clear data. Please try again.');
            }
        },

        // Chart Data Generation
        getChartData() {
            const days = this.chartView === '7days' ? 7 : this.chartView === '30days' ? 30 : 365;
            const data = [];
            const now = new Date();

            for (let i = days - 1; i >= 0; i--) {
                const date = new Date(now);
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];

                const dayTransactions = this.transactions.filter(t => t.date === dateStr);
                const income = dayTransactions
                    .filter(t => t.type === 'receive')
                    .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
                const expense = dayTransactions
                    .filter(t => t.type === 'give')
                    .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);

                data.push({
                    date: dateStr,
                    label: date.getDate() + ' ' + date.toLocaleDateString('en-US', { month: 'short' }),
                    income,
                    expense,
                    net: income - expense
                });
            }

            return data;
        },

        getChartMaxValue() {
            const data = this.getChartData();
            const max = Math.max(
                ...data.map(d => Math.max(d.income, d.expense))
            );
            return max || 100;
        },

        getChartBarHeight(value) {
            const max = this.getChartMaxValue();
            return (value / max) * 100;
        },

        // Statistics
        getCategoryStats() {
            const stats = {};
            const allCategories = [...new Set(this.transactions.map(t => t.category || 'Other'))];

            allCategories.forEach(category => {
                const categoryTxs = this.transactions.filter(t => (t.category || 'Other') === category);
                const total = categoryTxs.reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
                const count = categoryTxs.length;

                if (count > 0) {
                    stats[category] = { total, count, category };
                }
            });

            return Object.values(stats).sort((a, b) => b.total - a.total);
        },

        getTopPeople(limit = 5) {
            const peopleWithBalance = this.people.map(person => ({
                ...person,
                balance: Math.abs(this.getPersonBalance(person.id)),
                transactions: this.getPersonTransactionCount(person.id)
            }));

            return peopleWithBalance
                .sort((a, b) => b.balance - a.balance)
                .slice(0, limit);
        },

        // Summary Stats
        getTotalTransactions() {
            return this.transactions.length;
        },

        getThisMonthInFlow() {
            const now = new Date();
            const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
            return this.transactions
                .filter(t => t.type === 'receive' && new Date(t.date) >= monthStart)
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getThisMonthOutFlow() {
            const now = new Date();
            const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
            return this.transactions
                .filter(t => t.type === 'give' && new Date(t.date) >= monthStart)
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        }
    };
}

// Make available globally for Alpine.js
window.cashApp = cashApp;
