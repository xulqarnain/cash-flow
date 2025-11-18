/**
 * Cash Record - Offline Alpine.js App
 * Complete data management and business logic
 * Uses IndexedDB for reliable offline storage (works in browser and native app)
 */

// Import Capacitor (will be available after Capacitor setup)
const { Capacitor } = window.Capacitor || {};

// Main Alpine.js Component
function cashApp() {
    return {
        // State
        currentView: 'dashboard',
        selectedMonth: 'January',
        showPersonForm: false,
        showSuccess: false,
        successMessage: '',

        // Form Data
        personForm: {
            id: null,
            name: '',
            type: 'give', // 'give' or 'receive'
            amount: '',
            date: new Date().toISOString().split('T')[0]
        },

        // Data
        people: [],
        transactions: [],

        // Database
        db: null,

        // Initialize
        async init() {
            console.log('Initializing Cash Record App...');

            // Initialize database
            await this.initDatabase();

            // Load data
            await this.loadPeople();
            await this.loadTransactions();

            console.log('App initialized successfully');
        },

        // Database Initialization - Uses IndexedDB for all platforms
        async initDatabase() {
            try {
                await this.initIndexedDB();
                console.log('IndexedDB initialized successfully');
            } catch (error) {
                console.error('Database initialization error:', error);
                // Fallback to localStorage
                this.people = JSON.parse(localStorage.getItem('people') || '[]');
                this.transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
            }
        },

        // IndexedDB Fallback for Web
        async initIndexedDB() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('CashRecordDB', 1);

                request.onerror = () => reject(request.error);
                request.onsuccess = () => {
                    this.db = request.result;
                    resolve();
                };

                request.onupgradeneeded = (event) => {
                    const db = event.target.result;

                    // Create people store
                    if (!db.objectStoreNames.contains('people')) {
                        const peopleStore = db.createObjectStore('people', { keyPath: 'id', autoIncrement: true });
                        peopleStore.createIndex('name', 'name', { unique: false });
                    }

                    // Create transactions store
                    if (!db.objectStoreNames.contains('transactions')) {
                        const txStore = db.createObjectStore('transactions', { keyPath: 'id', autoIncrement: true });
                        txStore.createIndex('person_id', 'person_id', { unique: false });
                        txStore.createIndex('date', 'date', { unique: false });
                    }
                };
            });
        },

        // CRUD Operations - People
        async loadPeople() {
            try {
                if (this.db && this.db.objectStoreNames) {
                    // IndexedDB
                    const tx = this.db.transaction(['people'], 'readonly');
                    const store = tx.objectStore('people');
                    const request = store.getAll();

                    request.onsuccess = () => {
                        this.people = request.result;
                    };
                } else {
                    // LocalStorage fallback
                    this.people = JSON.parse(localStorage.getItem('people') || '[]');
                }
            } catch (error) {
                console.error('Error loading people:', error);
                this.people = [];
            }
        },

        async savePerson() {
            try {
                const person = {
                    name: this.personForm.name,
                    created_at: new Date().toISOString()
                };

                if (this.personForm.id) {
                    // Update existing person
                    await this.updatePerson(this.personForm.id, person);
                } else {
                    // Add new person
                    await this.addPerson(person);
                }

                // Add initial transaction if amount provided
                if (this.personForm.amount && parseFloat(this.personForm.amount) > 0) {
                    const lastPerson = this.people[0]; // Get the newly added person
                    await this.addTransaction({
                        person_id: lastPerson.id,
                        type: this.personForm.type,
                        amount: parseFloat(this.personForm.amount),
                        date: this.personForm.date,
                        created_at: new Date().toISOString()
                    });
                }

                await this.loadPeople();
                await this.loadTransactions();

                this.showSuccessMessage('Person saved successfully!');
                this.closePersonForm();
            } catch (error) {
                console.error('Error saving person:', error);
                alert('Failed to save person. Please try again.');
            }
        },

        async addPerson(person) {
            if (this.sqlite && this.db) {
                // SQLite
                const sql = 'INSERT INTO people (name, created_at) VALUES (?, ?)';
                await this.db.run(sql, [person.name, person.created_at]);
            } else if (this.db && this.db.objectStoreNames) {
                // IndexedDB
                return new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['people'], 'readwrite');
                    const store = tx.objectStore('people');
                    const request = store.add(person);
                    request.onsuccess = () => resolve(request.result);
                    request.onerror = () => reject(request.error);
                });
            } else {
                // LocalStorage fallback
                const people = JSON.parse(localStorage.getItem('people') || '[]');
                person.id = Date.now();
                people.unshift(person);
                localStorage.setItem('people', JSON.stringify(people));
            }
        },

        async updatePerson(id, person) {
            if (this.sqlite && this.db) {
                const sql = 'UPDATE people SET name = ? WHERE id = ?';
                await this.db.run(sql, [person.name, id]);
            } else if (this.db && this.db.objectStoreNames) {
                return new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['people'], 'readwrite');
                    const store = tx.objectStore('people');
                    person.id = id;
                    const request = store.put(person);
                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });
            } else {
                const people = JSON.parse(localStorage.getItem('people') || '[]');
                const index = people.findIndex(p => p.id === id);
                if (index !== -1) {
                    people[index] = { ...people[index], ...person };
                    localStorage.setItem('people', JSON.stringify(people));
                }
            }
        },

        async deletePerson(id) {
            if (!confirm('Are you sure you want to delete this person? All their transactions will be deleted too.')) {
                return;
            }

            try {
                if (this.sqlite && this.db) {
                    await this.db.run('DELETE FROM transactions WHERE person_id = ?', [id]);
                    await this.db.run('DELETE FROM people WHERE id = ?', [id]);
                } else if (this.db && this.db.objectStoreNames) {
                    return new Promise((resolve, reject) => {
                        const tx = this.db.transaction(['people', 'transactions'], 'readwrite');

                        // Delete transactions first
                        const txStore = tx.objectStore('transactions');
                        const txIndex = txStore.index('person_id');
                        const txRequest = txIndex.openCursor(IDBKeyRange.only(id));

                        txRequest.onsuccess = (e) => {
                            const cursor = e.target.result;
                            if (cursor) {
                                cursor.delete();
                                cursor.continue();
                            } else {
                                // Delete person
                                const peopleStore = tx.objectStore('people');
                                peopleStore.delete(id);
                            }
                        };

                        tx.oncomplete = () => resolve();
                        tx.onerror = () => reject(tx.error);
                    });
                } else {
                    let people = JSON.parse(localStorage.getItem('people') || '[]');
                    let transactions = JSON.parse(localStorage.getItem('transactions') || '[]');

                    people = people.filter(p => p.id !== id);
                    transactions = transactions.filter(t => t.person_id !== id);

                    localStorage.setItem('people', JSON.stringify(people));
                    localStorage.setItem('transactions', JSON.stringify(transactions));
                }

                await this.loadPeople();
                await this.loadTransactions();

                this.showSuccessMessage('Person deleted successfully!');
            } catch (error) {
                console.error('Error deleting person:', error);
                alert('Failed to delete person. Please try again.');
            }
        },

        // CRUD Operations - Transactions
        async loadTransactions() {
            try {
                if (this.sqlite && this.db) {
                    const result = await this.db.query('SELECT * FROM transactions ORDER BY date DESC, created_at DESC');
                    this.transactions = result.values || [];
                } else if (this.db && this.db.objectStoreNames) {
                    const tx = this.db.transaction(['transactions'], 'readonly');
                    const store = tx.objectStore('transactions');
                    const request = store.getAll();

                    request.onsuccess = () => {
                        this.transactions = request.result.sort((a, b) => {
                            return new Date(b.date) - new Date(a.date);
                        });
                    };
                } else {
                    this.transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
                this.transactions = [];
            }
        },

        async addTransaction(transaction) {
            if (this.sqlite && this.db) {
                const sql = 'INSERT INTO transactions (person_id, type, amount, date, created_at) VALUES (?, ?, ?, ?, ?)';
                await this.db.run(sql, [
                    transaction.person_id,
                    transaction.type,
                    transaction.amount,
                    transaction.date,
                    transaction.created_at
                ]);
            } else if (this.db && this.db.objectStoreNames) {
                return new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['transactions'], 'readwrite');
                    const store = tx.objectStore('transactions');
                    const request = store.add(transaction);
                    request.onsuccess = () => resolve(request.result);
                    request.onerror = () => reject(request.error);
                });
            } else {
                const transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
                transaction.id = Date.now();
                transactions.unshift(transaction);
                localStorage.setItem('transactions', JSON.stringify(transactions));
            }
        },

        async deleteTransaction(id) {
            if (!confirm('Are you sure you want to delete this transaction?')) {
                return;
            }

            try {
                if (this.sqlite && this.db) {
                    await this.db.run('DELETE FROM transactions WHERE id = ?', [id]);
                } else if (this.db && this.db.objectStoreNames) {
                    return new Promise((resolve, reject) => {
                        const tx = this.db.transaction(['transactions'], 'readwrite');
                        const store = tx.objectStore('transactions');
                        const request = store.delete(id);
                        request.onsuccess = () => resolve();
                        request.onerror = () => reject(request.error);
                    });
                } else {
                    let transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
                    transactions = transactions.filter(t => t.id !== id);
                    localStorage.setItem('transactions', JSON.stringify(transactions));
                }

                await this.loadTransactions();
                this.showSuccessMessage('Transaction deleted successfully!');
            } catch (error) {
                console.error('Error deleting transaction:', error);
                alert('Failed to delete transaction. Please try again.');
            }
        },

        // Calculations
        getTotalInFlow() {
            return this.transactions
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount), 0);
        },

        getTotalOutFlow() {
            return this.transactions
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount), 0);
        },

        getTotalBalance() {
            return this.getTotalInFlow() - this.getTotalOutFlow();
        },

        getPersonBalance(personId) {
            const personTransactions = this.transactions.filter(t => t.person_id === personId);
            const received = personTransactions
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount), 0);
            const given = personTransactions
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount), 0);

            return received - given;
        },

        getPersonName(personId) {
            const person = this.people.find(p => p.id === personId);
            return person ? person.name : 'Unknown';
        },

        // UI Helpers
        switchView(view) {
            this.currentView = view;
        },

        openPersonForm() {
            this.personForm = {
                id: null,
                name: '',
                type: 'give',
                amount: '',
                date: new Date().toISOString().split('T')[0]
            };
            this.showPersonForm = true;
        },

        closePersonForm() {
            this.showPersonForm = false;
        },

        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccess = true;
            setTimeout(() => {
                this.showSuccess = false;
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
            }).format(amount);
        },

        getTypeColor(type) {
            return type === 'receive' ? 'text-green-600' : 'text-red-600';
        },

        getTypeIcon(type) {
            return type === 'receive' ? '↓' : '↑';
        },

        // Chart Data (simplified for demo)
        getChartData() {
            // This would generate SVG path data for the line chart
            // For now, returning a static curved line
            return 'M 20 100 Q 50 80, 100 90 T 180 85 T 260 95 T 340 80';
        }
    };
}

// Make available globally for Alpine.js
window.cashApp = cashApp;
